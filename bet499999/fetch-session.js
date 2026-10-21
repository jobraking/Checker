#!/usr/bin/env node
"use strict";

/**
 * Helper for account-check.js.
 *
 * Prints a one-liner to paste into the browser console on a page where you
 * are already logged in. It reads your own MC_SESSION_INFO from localStorage
 * and copies it to your clipboard. It does not log in and does not read your
 * password -- the site never puts the password in localStorage for this flow.
 *
 * Usage:
 *   node fetch-session.js        # show the snippet
 *   node fetch-session.js --bookmarklet
 */

const SNIPPET = `(()=>{const s=localStorage.getItem("MC_SESSION_INFO");if(!s)return console.warn("No session here - log in at https://www.499bet999.com/m/login first.");const j=JSON.parse(s);const out={token:j.token,merchantCode:j.merchantCode,userName:j.userName};navigator.clipboard?.writeText(JSON.stringify(out)).then(()=>console.log("Session copied to clipboard:",JSON.stringify(out)),()=>console.log("Clipboard blocked, copy manually:",JSON.stringify(out)));})()`;

if (process.argv.includes("--bookmarklet")) {
  console.log(`javascript:${encodeURIComponent(SNIPPET)}`);
} else {
  console.log(
    [
      "1. Open https://www.499bet999.com/m/login and log in as yourself.",
      "2. Stay on the site, open DevTools -> Console.",
      "3. Paste this and press Enter:",
      "",
      SNIPPET,
      "",
      "4. Your session is copied to the clipboard. Then run:",
      "",
      '   node account-check.js --session \'<paste>\' --balance',
      "",
      "If the clipboard is blocked the console prints the JSON -- copy it by hand.",
      "",
    ].join("\n")
  );
}
