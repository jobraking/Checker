#!/usr/bin/env node
"use strict";

process.env.CK_NODE_DIR = __dirname;
process.env.CK_ORIGIN = "https://www.cv666app.net";
process.env.CK_NODE_NAME = "cv666";

require("../lib/cli/account-check.js");
