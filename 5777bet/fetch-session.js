#!/usr/bin/env node
"use strict";

process.env.CK_NODE_DIR = __dirname;
process.env.CK_ORIGIN = "https://www.5777bet.win";
process.env.CK_NODE_NAME = "5777bet";

require("../lib/cli/fetch-session.js");
