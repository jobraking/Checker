#!/usr/bin/env node
"use strict";

process.env.CK_NODE_DIR = __dirname;
process.env.CK_ORIGIN = "https://www.cbaji8.club";
process.env.CK_NODE_NAME = "cbaji8";

require("../lib/cli/login.js");
