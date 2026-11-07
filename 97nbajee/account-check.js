#!/usr/bin/env node
"use strict";

process.env.CK_NODE_DIR = __dirname;
process.env.CK_ORIGIN = "https://www.97nbajee.club";
process.env.CK_NODE_NAME = "97nbajee";

require("../lib/cli/account-check.js");
