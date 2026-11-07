#!/usr/bin/env node
"use strict";

process.env.CK_NODE_DIR = __dirname;
process.env.CK_ORIGIN = "https://www.12xbajee.com";
process.env.CK_NODE_NAME = "12xbajee";

require("../lib/cli/account-check.js");
