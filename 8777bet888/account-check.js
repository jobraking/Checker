#!/usr/bin/env node
"use strict";

process.env.CK_NODE_DIR = __dirname;
process.env.CK_ORIGIN = "https://www.8777bet888.com";
process.env.CK_NODE_NAME = "8777bet888";

require("../lib/cli/account-check.js");
