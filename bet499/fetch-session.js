#!/usr/bin/env node
"use strict";

process.env.CK_NODE_DIR = __dirname;
process.env.CK_ORIGIN = "https://www.499bet444.com";
process.env.CK_NODE_NAME = "bet499";

require("../lib/cli/fetch-session.js");
