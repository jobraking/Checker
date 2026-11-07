#!/usr/bin/env node
"use strict";

process.env.CK_NODE_DIR = __dirname;
process.env.CK_ORIGIN = "https://www.1333bet.ai";
process.env.CK_NODE_NAME = "bet1333";

require("../lib/cli/account-check.js");
