#!/usr/bin/env node
"use strict";

process.env.CK_NODE_DIR = __dirname;
process.env.CK_ORIGIN = "https://www.3333bk.com";
process.env.CK_NODE_NAME = "3333bk";

require("../lib/cli/login.js");
