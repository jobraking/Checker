#!/usr/bin/env node
"use strict";

process.env.CK_NODE_DIR = __dirname;
process.env.CK_ORIGIN = "https://ppvip1.ppvip01.com";
process.env.CK_NODE_NAME = "ppvip10";

require("../lib/cli/account-check.js");
