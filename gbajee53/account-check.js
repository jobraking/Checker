#!/usr/bin/env node
"use strict";

process.env.CK_NODE_DIR = __dirname;
process.env.CK_ORIGIN = "https://www.g0lbajee.com";
process.env.CK_NODE_NAME = "gbajee53";

require("../lib/cli/account-check.js");
