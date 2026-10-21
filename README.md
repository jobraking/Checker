<div align="center">

# D3M09 Checker

**A local-first account checker platform for the checker fleet.**
Unified landing page, searchable showcase, per-node consoles, and a byte-identical
login pipeline across every deployment.

[![PHP](https://img.shields.io/badge/PHP-8.1%2B-777BB4?logo=php&logoColor=white)](#requirements)
[![Platform](https://img.shields.io/badge/platform-loopback%20only-00B894)](#security-model)
[![Crypto](https://img.shields.io/badge/crypto-RSA%20%2B%20DES--ECB-0877ED)](#request-pipeline)
[![Dependencies](https://img.shields.io/badge/dependencies-none-5F7186)](#requirements)

</div>

---

## Overview

D3M09 Checker is a self-contained PHP platform that wraps a fleet of mirrored
account-checker nodes behind one clean interface. Each node speaks the same
vendor API: an RSA-wrapped secret, a DES-ECB encrypted payload, and a bearer
token once the handshake completes.

The platform adds what the raw nodes lacked — a landing page, a searchable
showcase, consistent navigation, and a shared layout — without touching the
request pipeline itself.

| | |
|---|---|
| **Deployments** | 46 mirrored nodes |
| **Entry point** | `index.php` → landing page |
| **Directory** | `showcase.php` → all checkers |
| **Mode** | Loopback only (`127.0.0.1` / `::1`) |
| **Dependencies** | PHP extensions only, no Composer |

---

## Highlights

- **Landing page** — project overview, pipeline diagram, quick-launch grid.
- **Showcase** — every deployment in one directory, sorted and linked.
- **Single checks** — one credential, one request, live profile and balance.
- **Bulk checks** — newline batches with per-line status, captcha handling, and throttling.
- **Saved vault** — successful VIP logins stored per node with rank filters and masked tokens.
- **Live endpoint explorer** — authenticated sweep of the full endpoint map as raw JSON.
- **Local-only surface** — every console rejects non-loopback requests and uses CSRF tokens.
- **No framework** — plain PHP 8.1+, one shared client per node, zero build step.

---

## Architecture

```
                        ┌──────────────────────────────┐
                        │            index.php         │
                        │   landing · quick launch     │
                        └──────────────┬───────────────┘
                                       │
                        ┌──────────────▼───────────────┐
                        │          showcase.php        │
                        │     46-node directory        │
                        └──────────────┬───────────────┘
                                       │  /node/
              ┌────────────────────────┼────────────────────────┐
              │                        │                        │
     ┌────────▼────────┐      ┌────────▼────────┐      ┌────────▼────────┐
     │   node/index.php│      │  node/saved.php │      │ node/router.php │
     │ single · bulk   │      │  saved vault    │      │  node routing   │
     └────────┬────────┘      └────────┬────────┘      └─────────────────┘
              │                        │
              └───────────┬────────────┘
                          │
                 ┌────────▼────────┐
                 │ node/ck-client  │
                 │  API transport  │
                 └────────┬────────┘
                          │
                 ┌────────▼────────┐
                 │ node/vendor-    │
                 │ crypto (RSA/DES)│
                 └─────────────────┘
```

`router.php` is a front controller. It works both as a directory index fallback
and as a PHP built-in-server router script.

---

## Request Pipeline

Every login reproduces the browser handshake exactly:

| Step | Action | Detail |
|:---:|---|---|
| 1 | `GET /wps/session/key/rsa` | Fetch the 2048-bit public modulus |
| 2 | `rndString(16)` | 16 characters from the vendor's 61-char alphabet |
| 3 | `strrev(secret)` → RSA | jsbn-compatible chunk packing |
| 4 | `DES-ECB / PKCS7` | Keyed with `secret[0:8]` |
| 5 | `POST /wps/session/login` | Body `{"value": ciphertext}`, headers `Encryption` + `X-Digest` |
| 6 | Token | Bearer token used by all subsequent reads |

The vendor's RSA quirks — including `chunkSize = 2 * biHighIndex(modulus)` and
the DES key slice — are reproduced in `vendor-crypto.php`, so ciphertext is
byte-identical to the browser's.

Merchant codes are auto-detected from the deployed bundle:

```
/m/asset-manifest.json  →  app.<hash>.js  →  { platform:"mobile", merchant:"…" }
```

Set `CK_MERCHANT` to pin one explicitly.

---

## Project Layout

```
checker2/
├── index.php                 # Landing page
├── showcase.php              # Full checker directory
├── router.php                # Front controller / built-in server router
├── format-fixer.php          # Standalone credential line normalizer
├── AGENTS.md                 # Agent operating instructions
├── README.md
└── <node>/                   # 46 mirrored deployments
    ├── index.php             # Console: single, bulk, captcha, change password
    ├── saved.php             # Saved account vault and live panel
    ├── ck-client.php         # API client: login, profile, balance, retries
    ├── vendor-crypto.php     # Pure-PHP DES-ECB + jsbn-compatible RSA
    ├── vendor-rsa.js         # Original jsbn bundle
    ├── bulk-check.php        # CLI batch checker
    ├── details.php           # Localhost-only endpoint dump
    ├── router.php            # Per-node routing
    ├── live_endpoints.txt    # Endpoint map used by the live explorer
    ├── login.js              # Browser-side handshake reference
    ├── fetch-session.js      # Session capture helper
    └── account-check.js      # Node CLI session inspector
```

Nodes are intentionally self-contained. Copy a node directory, change
`CK_ORIGIN`, and it runs — no shared code to break.

---

## Requirements

- **PHP 8.1+** with `curl`, `json`, `session`, `openssl` (optional)
- **Node.js 18+** only for the two optional `.js` helpers
- Any OS that runs PHP

No Composer. No database. No build step.

---

## Quick Start

```bash
git clone https://github.com/emonbudc/Checker.git
cd Checker

php -S 127.0.0.1:8080
```

Open **http://127.0.0.1:8080** for the landing page.

To exercise the front controller as a router script:

```bash
php -S 127.0.0.1:8080 router.php
```

Both modes resolve `/`, `/showcase.php`, and every `/node/` route.

> Bind to `127.0.0.1`, not `0.0.0.0`. Consoles refuse non-loopback clients, but
> binding loopback keeps that guarantee meaningful.

---

## Usage

### Landing page — `/`

Project overview, pipeline diagram, quick-launch grid, and a link to the three
featured nodes plus the full showcase.

### Showcase — `/showcase.php`

Every deployment in one searchable directory with domain labels and direct
links to each console.

### Single check — `/node/`

| Field | Format |
|---|---|
| Credentials | `username \| password` |
| Captcha | Prompted inline when the vendor requests one |

On success the console renders labels, club tier, rank, balance, referral
counts, promotions, tickets and salary data, then drops the token so the next
visit starts clean.

### Bulk check — `/node/` → Bulk tab

Paste one credential per line. Accepted formats:

```text
username|password
username:password
username password
https://site.example username password
```

Each row resolves to `valid`, `invalid`, `captcha`, or `malformed`. Captcha rows
pause the batch for manual entry.

### Saved vault — `/node/saved.php`

Successful VIP logins are stored with username, token, rank and profile summary.
The vault supports rank filters, sorting, reveal-all, copy, delete, and live
balance/promotion checks that re-authenticate when a token has expired.

### CLI batch — `node/bulk-check.php`

```bash
php bulk-check.php --file creds.txt
php bulk-check.php --file creds.txt --delay 500 --retries 2 --audit out.jsonl
```

| Flag | Default | Purpose |
|---|---|---|
| `--file` | required | Credential TXT |
| `--delay` | `300` | Milliseconds between attempts |
| `--retries` | `2` | Retries on duplicate-submission errors |
| `--audit` | `./bulk-check-audit.jsonl` | JSONL audit trail |
| `--delimiter` | `\|` | Preferred delimiter |

### Line normalizer — `/format-fixer.php`

Paste mixed credential lines and download a normalized `fixed.txt`. Handles URLs,
colons, pipes, whitespace, header rows, and `null` placeholders.

---

## Configuration

| Variable | Scope | Effect |
|---|---|---|
| `CK_MERCHANT` | env or per node | Pins the merchant code, skipping auto-detection |
| `CK_ORIGIN` | `ck-client.php` const | Target origin for a node |

Per-node route prefixes live in the root `router.php` map. Add a prefix, add a
directory, and the node is reachable.

---

## Data Files

| File | Location | Contents |
|---|---|---|
| `pwd.json` | per node | Saved accounts: username, password, token, VIP, timestamp |
| `.ck-session.json` | per node | Captured session: token, merchant, username |
| `live_endpoints.txt` | per node | Endpoint list for the live explorer |
| `bulk-check-audit.jsonl` | CWD | CLI audit trail |

These files contain live credentials and tokens. Treat the working tree as
private material and keep it out of public distribution.

---

## Security Model

- **Loopback gate** — `index.php`, `saved.php`, and `details.php` compare
  `REMOTE_ADDR` against `127.0.0.1` and `::1`.
- **CSRF** — every mutating POST carries a per-session token checked with
  `hash_equals`.
- **Sessions** — strict mode, `HttpOnly`, `SameSite=Lax`, per-node session names.
- **Headers** — `Cache-Control: no-store`, `X-Content-Type-Options: nosniff`,
  `Referrer-Policy: no-referrer`.
- **Crypto** — RSA public-key handshake; plaintext passwords never travel in
  request bodies or persist in session state.
- **Vault permissions** — `pwd.json` is written with mode `0600`.

Behind a reverse proxy, `REMOTE_ADDR` is the proxy address, not the client.
Either forward the real address explicitly or keep the app on loopback.

---

## Design Notes

- Landing and showcase are pure PHP + CSS — no JavaScript, no external fonts.
- Consoles share one visual language: card surfaces, mint/blue accents, tabular
  results, inline status pills.
- Crypto is implemented in pure PHP because OpenSSL 3 no longer ships `des-ecb`
  by default; the implementation mirrors the vendor's jsbn runtime.
- The platform intentionally mirrors nodes instead of sharing code, keeping
  each deployment independently recoverable.

---

<div align="center">

**D3M09 Checker** — local account console
Loopback access · no telemetry · no external assets

</div>
