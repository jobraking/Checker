#!/usr/bin/env node
"use strict";

/**
 * ck44 self-account checker.
 *
 * Reads the session you already have and reports account status. It never
 * submits a password and never calls /wps/session/login -- the only requests
 * it can make are reads of your own profile.
 *
 * Session comes from login.js (./.ck-session.json), from fetch-session.js, or
 * from --token/--merchant.
 *
 * Usage:
 *   node account-check.js --session "$(cat .ck-session.json)"
 *   node account-check.js --session "$(cat .ck-session.json)" --agent-funds
 *   node account-check.js --session "$(cat .ck-session.json)" --json
 *
 * Exit codes: 0 ok, 1 usage error, 2 no token, 3 session rejected.
 */

const fs = require("fs");
const path = require("path");

const ORIGIN = "https://www.499bet999.com";
const PROFILE_PATH = "/wps/member/info";
const AGENT_FUNDS_PATH = "/wps/member/info/funds/consolidated";

function die(msg, code = 1) {
  console.error(`error: ${msg}`);
  process.exit(code);
}

function parseArgs(argv) {
  const o = { agentFunds: false, json: false };
  for (let i = 0; i < argv.length; i++) {
    const a = argv[i];
    if (a === "--agent-funds" || a === "--balance") o.agentFunds = true;
    else if (a === "--json") o.json = true;
    else if (a === "--token" || a === "-t") o.token = argv[++i];
    else if (a === "--merchant" || a === "-m") o.merchant = argv[++i];
    else if (a === "--session" || a === "-s") o.session = argv[++i];
    else if (a === "--help" || a === "-h") o.help = true;
    else if (a.startsWith("-")) die(`unknown flag: ${a}`);
  }
  return o;
}

function mask(t) {
  if (!t) return "(none)";
  return t.length <= 10 ? "***" : `${t.slice(0, 6)}…${t.slice(-4)}`;
}

/**
 * A username/password pair is never accepted here -- there is no code path
 * for one. If .ck-session.json exists and no explicit args were given, use it.
 */
function resolveSession(opts) {
  let token = opts.token || process.env.CK_TOKEN;
  let merchant = opts.merchant || process.env.CK_MERCHANT;

  const blob = opts.session || process.env.CK_SESSION;
  if (blob) {
    let parsed;
    try {
      parsed = JSON.parse(blob);
    } catch {
      die("--session is not valid JSON (expected the session object)");
    }
    token = token || parsed.token;
    merchant = merchant || parsed.merchantCode;
  }

  if (!token) {
    const f = path.join(__dirname, ".ck-session.json");
    if (!opts.session && !opts.token && fs.existsSync(f)) {
      try {
        const saved = JSON.parse(fs.readFileSync(f, "utf8"));
        token = saved.token;
        merchant = merchant || saved.merchantCode;
      } catch {
        /* fall through to the usage message */
      }
    }
  }

  if (!token) {
    console.error(
      [
        "",
        "No session token found.",
        "",
        "Run login.js first, or pass a session explicitly:",
        "",
        "  node login.js -u <username> -p <password>",
        '  node account-check.js --session "$(cat .ck-session.json)"',
        "",
      ].join("\n")
    );
    process.exit(2);
  }

  return { token: String(token).trim(), merchant: merchant ? String(merchant).trim() : "" };
}

async function callApi(p, session) {
  const headers = {
    Authorization: session.token,
    "X-Real-UA": Buffer.from("bdt399-self-account-check").toString("base64"),
    Language: process.env.CK_LANGUAGE || "EN",
  };
  if (session.merchant) headers.Merchant = session.merchant;

  const res = await fetch(ORIGIN + p, {
    method: "GET",
    headers,
    credentials: "include",
    signal: AbortSignal.timeout(20000),
    redirect: "manual",
  });

  const text = await res.text();
  let body;
  try {
    body = JSON.parse(text);
  } catch {
    body = { success: false, message: text.slice(0, 200) };
  }
  return { status: res.status, body };
}

const fmtDate = (ms) =>
  typeof ms === "number" && ms > 0
    ? `${new Date(ms).toISOString().replace("T", " ").slice(0, 19)} UTC`
    : null;

const fmtBool = (b) => (b === true ? "yes" : b === false ? "no" : null);

// The web client treats type 0, 4 and 40 as non-agent (see checkAgent() in
// app.js). Anything else it routes to the agent UI.
function memberType(t) {
  if (t === 0) return "0 (standard member)";
  if (t === 4 || t === 40) return `${t} (non-agent)`;
  if (t === undefined || t === null) return "unknown";
  return `${t} (client routes this to agent UI)`;
}

function renderProfile(v) {
  const labels = Array.isArray(v.memberLabel)
    ? v.memberLabel.map((l) => l?.labelName).filter(Boolean)
    : [];

  const rows = [
    ["Account", v.account],
    ["Nickname", v.nickname],
    ["Member type", memberType(v.type)],
    ["Phone", v.mobile],
    ["Email", v.email],
    ["Registered", fmtDate(v.regDate)],
    ["Last login", fmtDate(v.lastLogin)],
    ["Last login IP", v.lastLoginIp],
    ["Last login place", v.lastLoginLocation],
    ["Labels", labels.join(", ")],
    ["Club tier", v.clubLabel?.labelName],
    ["Rank level", v.clubLabel?.rankLevel],
    ["Phone verified", fmtBool(v.mobileVerified)],
    ["Email verified", fmtBool(v.isEmailVerification)],
    ["ID verification", v.idVerificationStatus],
    ["Register source", v.registerSource],
    ["Register method", v.registerMethod],
    ["Language", v.language],
    ["Dial code", v.countryDialingCode],
    ["Payee name", v.payee],
    ["Official app login", fmtBool(v.isOfficialAppLogin)],
  ];

  const shown = rows.filter(([, v2]) => v2 !== null && v2 !== undefined && v2 !== "");
  if (!shown.length) return;
  const w = Math.max(...shown.map((r) => r[0].length));
  console.log("\nAccount");
  console.log("--------");
  for (const [k, val] of shown) console.log(`${k.padEnd(w)}  ${val}`);
}

async function main() {
  const opts = parseArgs(process.argv.slice(2));
  if (opts.help) {
    console.log(
      [
        "ck44 self-account checker",
        "",
        "  node account-check.js [--session JSON] [--token T] [--merchant M]",
        "                               [--agent-funds] [--json]",
        "",
        "Reads your own profile using an existing session. It does not log in",
        "and does not accept a password. With no arguments it reads",
        "./.ck-session.json if that file exists.",
        "",
        "Exit codes: 0 ok, 1 usage, 2 no token, 3 session rejected.",
      ].join("\n")
    );
    return;
  }

  const session = resolveSession(opts);
  if (!opts.json) {
    console.log(`session  ${mask(session.token)}`);
    console.log(`merchant ${session.merchant || "(not set)"}`);
  }

  let profile;
  try {
    profile = await callApi(PROFILE_PATH, session);
  } catch (err) {
    die(`could not reach ${ORIGIN}: ${err.message}`);
  }

  const rejected = profile.status === 401 || profile.body?.success === false;

  if (opts.json) {
    console.log(JSON.stringify(profile.body, null, 2));
    if (rejected) process.exitCode = 3;
    return;
  }

  if (rejected) {
    console.log("\nstatus   NOT LOGGED IN / session rejected");
    console.log(`code     ${profile.body?.errorCode ?? profile.status}`);
    if (profile.body?.message) console.log(`message  ${profile.body.message}`);
    console.log(
      "\nThe session token is missing, expired, or was invalidated by a login\n" +
        "from another device. Log in again and re-run."
    );
    process.exitCode = 3;
    return;
  }

  // Profile lives at body.value on this endpoint.
  const value = profile.body?.value ?? profile.body?.data ?? {};
  console.log("\nstatus   LOGGED IN (session valid)");
  renderProfile(value);

  if (opts.agentFunds) {
    console.log("\nAgent funds (consolidated)");
    console.log("-----------------------");
    try {
      const f = await callApi(AGENT_FUNDS_PATH, session);
      if (f.status === 200 && f.body?.success !== false) {
        const entries = Object.entries(f.body?.value ?? {}).filter(
          ([, v]) => v !== null && v !== undefined
        );
        if (!entries.length) {
          console.log("(all null - this endpoint carries agent contract/salary data,");
          console.log(" which a standard member account does not have)");
        } else {
          const w = Math.max(...entries.map(([k]) => k.length));
          for (const [k, v] of entries) {
            console.log(`${k.padEnd(w)}  ${typeof v === "object" ? JSON.stringify(v) : v}`);
          }
        }
      } else {
        console.log(`unavailable (${f.body?.errorCode ?? f.status})`);
      }
    } catch (err) {
      console.log(`unavailable (${err.message})`);
    }
  }
}

main().catch((err) => die(err.message));
