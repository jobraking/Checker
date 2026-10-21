#!/usr/bin/env node
"use strict";

/**
 * ck44 login -- obtains YOUR OWN session so account-check.js can use it.
 *
 * This reproduces the site's client-side crypto exactly:
 *   1. GET  /wps/session/key/rsa          -> RSA public modulus (hex)
 *   2. k    = rndString()                 -> 16 chars from the vendor alphabet
 *   3. RSA  = encryptedString(RSAKeyPair("10001","",modulus), reverse(k))
 *   4. DES  = DES-ECB-PKCS7(JSON payload), key = k[0..8]   (CryptoJS drops the
 *            remaining 8 bytes into the IV, which ECB ignores)
 *   5. POST /wps/session/login
 *        body    {"value": RSA}
 *        headers Encryption: RSA, X-Digest: DES, X-RSA: modulus
 *
 * Single account only -- there is no batch mode and no way to point this at a
 * list of credentials. Use it on an account you are authorised to sign in to.
 *
 * Usage:
 *   node login.js -u 01336710020 -p 'mypassword'
 *   node login.js -u 01336710020 -p 'mypassword' --print-token
 *   CK_USER=... CK_PASS=... node login.js
 */

const crypto = require("crypto");
const fs = require("fs");
const path = require("path");
const V = require("./vendor-rsa.js");

const ORIGIN = "https://www.bb44.best";
const SESSION_FILE = path.join(__dirname, ".ck-session.json");

function parseArgs(argv) {
  const o = { printToken: false, merchant: process.env.CK_MERCHANT || null, deviceId: null };
  for (let i = 0; i < argv.length; i++) {
    const a = argv[i];
    if (a === "-u" || a === "--user") o.user = argv[++i];
    else if (a === "-p" || a === "--pass") o.pass = argv[++i];
    else if (a === "-m" || a === "--merchant") o.merchant = argv[++i];
    else if (a === "--device-id") o.deviceId = argv[++i];
    else if (a === "--print-token") o.printToken = true;
    else if (a === "-h" || a === "--help") o.help = true;
    else die(`unknown flag: ${a}`);
  }
  return o;
}

function die(msg, code = 1) {
  console.error(`error: ${msg}`);
  process.exit(code);
}

// OpenSSL 3 hides single DES behind the legacy provider. Rather than make the
// caller remember a flag, re-exec once with it enabled.
if (!crypto.getCiphers().includes("des-ecb") && !process.env.CK_LEGACY_RESPAWNED) {
  const { spawnSync } = require("child_process");
  const r = spawnSync(
    process.execPath,
    ["--openssl-legacy-provider", __filename, ...process.argv.slice(2)],
    { stdio: "inherit", env: { ...process.env, CK_LEGACY_RESPAWNED: "1" } }
  );
  process.exit(r.status ?? 1);
}

function mask(t) {
  return !t ? "(none)" : t.length <= 10 ? "***" : `${t.slice(0, 6)}…${t.slice(-4)}`;
}

/**
 * DES-ECB with PKCS#7 padding, matching
 *   CryptoJS.DES.encrypt(payload, CryptoJS.enc.Utf8.parse(secret),
 *                        {mode:ECB, padding:Pkcs7}).toString()
 *
 * Two details matter and both are easy to get wrong:
 *  - CryptoJS's DES keySize is 2 words (8 bytes); the other 8 bytes of the
 *    16-char secret are never read by the key schedule, and ECB ignores the IV.
 *  - CipherParams.toString() with no argument uses the OpenSSL formatter, which
 *    serialises as Base64 -- not hex.
 */
function desEncryptBase64(plaintext, secret16) {
  const key = Buffer.from(secret16.slice(0, 8), "latin1");
  const data = Buffer.from(plaintext, "utf8");
  const pad = 8 - (data.length % 8);
  const padded = Buffer.concat([data, Buffer.alloc(pad, pad)]);
  const cipher = crypto.createCipheriv("des-ecb", key, null);
  cipher.setAutoPadding(false);
  return Buffer.concat([cipher.update(padded), cipher.final()]).toString("base64");
}

async function fetchRsaModulus() {
  const url = `${ORIGIN}/wps/session/key/rsa?_=${Date.now()}`;
  const res = await fetch(url, {
    headers: { "Cache-Control": "no-cache", Accept: "text/plain" },
    signal: AbortSignal.timeout(20000),
  });
  if (!res.ok) throw new Error(`rsa key endpoint returned HTTP ${res.status}`);
  const text = (await res.text()).trim();
  if (!/^[0-9a-f]{256,}$/i.test(text)) throw new Error("rsa key endpoint returned unexpected body");
  return text;
}

async function fetchCaptcha() {
  const res = await fetch(`${ORIGIN}/wps/captcha`, {
    headers: { "User-Agent": "Mozilla/5.0", "Merchant": (await detectMerchant({})).merchant || "", "Language": "EN" },
    signal: AbortSignal.timeout(15000),
  });
  const cookie = (res.headers.getSetCookie ? res.headers.getSetCookie()[0] : res.headers.get("set-cookie")) || "";
  const captchaId = (cookie.match(/captchaId=([^;]+)/) || [])[1];
  const json = await res.json();
  const img = json?.value;
  if (img) {
    require("fs").writeFileSync(require("path").join(__dirname, "captcha.png"), Buffer.from(img, "base64"));
  }
  return { captchaId: captchaId ? decodeURIComponent(captchaId) : null, cookie };
}

function realUaHeader() {
  const ua =
    "Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) " +
    "Chrome/127.0.0.0 Mobile Safari/537.36";
  return Buffer.from(ua).toString("base64");
}

async function login(creds, opts) {
  const modulus = await fetchRsaModulus();

  const secret = V.rndString();
  const reversed = secret.split("").reverse().join("");

  V.setMaxDigits(130);
  const rsa = V.encryptedString(new V.RSAKeyPair("10001", "", modulus), reversed);
  if (!rsa) throw new Error("RSA encryption produced no output");

  const payload = {
    username: creds.user,
    password: creds.pass,
    captcha: opts.captcha || "",
    type: "username",
    loginDeviceId: opts.deviceId || crypto.randomUUID(),
    isOfficialAppLogin: true,
    device: "",
  };

  const des = desEncryptBase64(JSON.stringify(payload), secret);

  const headers = {
    "Content-Type": "application/json;charset=utf-8",
    accept: "application/json, text/plain, */*",
    Encryption: rsa,
    "X-Digest": des,
    "X-RSA": modulus,
    Device: "",
    Merchant: opts.merchant,
    Language: "EN",
    "X-Real-UA": realUaHeader(),
    Referer: `${ORIGIN}/m/login`,
  };
  if (opts.cookie) headers.Cookie = opts.cookie;
  if (opts.captchaId) headers["X-Captcha-Id"] = opts.captchaId;

  const res = await fetch(`${ORIGIN}/wps/session/login`, {
    method: "POST",
    headers,
    body: JSON.stringify({ value: des }),
    signal: AbortSignal.timeout(25000),
  });

  const text = await res.text();
  let body;
  try {
    body = JSON.parse(text);
  } catch {
    body = { success: false, message: text.slice(0, 300) };
  }
  return { status: res.status, body };
}

/**
 * Work out which merchant this deployment uses, rather than hardcoding it.
 *
 * The API will not tell us: GET /wps/system/settings/consolidated returns
 * "merchantCode": null when no Merchant header is sent. It is baked into the
 * build config of the deployed frontend bundle, so we resolve the current
 * hashed bundle name from /m/asset-manifest.json and read it out of there.
 */
async function detectMerchant(opts) {
  const override = opts.merchant;
  if (override) return { merchant: override, source: "explicit argument / CK_MERCHANT" };

  const candidates = [];
  try {
    const man = await fetch(`${ORIGIN}/m/asset-manifest.json`, { signal: AbortSignal.timeout(15000) });
    if (man.ok) {
      const j = await man.json();
      if (j["app.js"]) candidates.push(`/m/${String(j["app.js"]).replace(/^\//, "")}`);
    }
  } catch { /* fall through to the login-page scrape */ }

  try {
    const page = await fetch(`${ORIGIN}/m/login`, { signal: AbortSignal.timeout(15000) });
    if (page.ok) {
      const html = await page.text();
      for (const m of html.matchAll(/\/m\/app\.[0-9a-f]+\.js/g)) candidates.push(m[0]);
    }
  } catch { /* ignore */ }

  for (const path of [...new Set(candidates)]) {
    try {
      const res = await fetch(ORIGIN + path, { signal: AbortSignal.timeout(30000) });
      if (!res.ok) continue;
      const js = await res.text();
      // Tighten around the build config so an unrelated "merchant" cannot match.
      const m = js.match(/\{[^{}]{0,200}?platform\s*:\s*"mobile"[^{}]{0,400}?merchant\s*:\s*"([A-Za-z0-9_-]+)"/s);
      if (m) return { merchant: m[1], source: `auto-detected from ${path}` };
    } catch { /* try the next candidate */ }
  }

  return { merchant: null, source: "not detected" };
}

async function main() {
  const opts = parseArgs(process.argv.slice(2));
  if (opts.help) {
    console.log(
      [
        "bb44 login (your own account)",
        "",
        "  node login.js -u <username> -p <password> [--print-token]",
        "                    [--merchant M] [--device-id UUID]",
        "",
        "Environment: CK_USER, CK_PASS, CK_MERCHANT",
        "",
        "The merchant is auto-detected from the deployed bundle; --merchant or",
        "CK_MERCHANT overrides it.",
        "",
        "On success the session is written to ./.ck-session.json (mode 600) and",
        "account-check.js can read it with --session.",
      ].join("\n")
    );
    return;
  }

  const user = opts.user || process.env.CK_USER;
  const pass = opts.pass || process.env.CK_PASS;
  if (!user || !pass) {
    die("both a username and a password are required (-u/-p, or CK_USER/CK_PASS)");
  }

  const detected = await detectMerchant(opts);
  if (!detected.merchant) {
    die(
      `could not auto-detect the merchant. Pass --merchant <code> or set CK_MERCHANT. (${detected.source})`
    );
  }
  opts.merchant = detected.merchant;

  console.log(`user     ${user}`);
  console.log(`merchant ${opts.merchant}  (${detected.source})`);

  let result;
  try {
    result = await login({ user, pass }, opts);
  } catch (err) {
    die(err.message);
  }

  const firstVal = result.body?.value ?? result.body?.data?.value ?? result.body?.data;
  const firstCode = result.body?.errorCode;
  const firstMsg = result.body?.message || "";
  if (result.status !== 200 || !firstVal?.token) {
    if (String(firstCode).toLowerCase().includes("captcha") || /captcha/i.test(firstMsg)) {
      console.log("\nCaptcha required. Fetching captcha image...");
      const cap = await fetchCaptcha();
      if (cap.captchaId) opts.captchaId = cap.captchaId;
      if (cap.cookie) opts.cookie = cap.cookie;
      console.log("Captcha saved to ./captcha.png — open it, then enter the code.");
      const readline = require("readline").createInterface({ input: process.stdin, output: process.stdout });
      const code = await new Promise((resolve) => readline.question("Captcha code: ", (a) => { readline.close(); resolve(a.trim()); }));
      opts.captcha = code;
      try {
        result = await login({ user, pass }, opts);
      } catch (err) {
        die(err.message);
      }
    }
  }

  const { status, body } = result;
  const value = body?.value ?? body?.data?.value ?? body?.data;

  if (status !== 200 || body?.success === false || !value?.token) {
    console.log(`\nlogin    FAILED (HTTP ${status})`);
    console.log(`code     ${body?.errorCode ?? "-"}`);
    if (body?.message) console.log(`message  ${body.message}`);
    if (status === 200) console.log(`body     ${JSON.stringify(body).slice(0, 400)}`);
    process.exitCode = 4;
    return;
  }

  const session = {
    token: value.token,
    merchantCode: opts.merchant,
    userName: value.userName ?? user,
    type: value.type,
    firstTimeLogin: value.firstTimeLogin,
  };

  fs.writeFileSync(SESSION_FILE, JSON.stringify(session, null, 2), { mode: 0o600 });
  fs.chmodSync(SESSION_FILE, 0o600);

  console.log(`\nlogin    OK`);
  console.log(`user     ${session.userName}`);
  console.log(`type     ${session.type ?? "-"}`);
  console.log(`token    ${mask(session.token)}`);
  console.log(`\nsaved to ${SESSION_FILE} (mode 600)`);
  console.log(`\nnext: node account-check.js --session "$(cat ${SESSION_FILE})" --agent-funds`);
  if (opts.printToken) {
    console.log(`\nfull token (you asked for it):\n${session.token}`);
  }
}

main().catch((err) => die(err.message));
