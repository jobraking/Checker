#!/usr/bin/env node
"use strict";

process.env.CK_NODE_DIR = __dirname;
process.env.CK_ORIGIN = "https://www.kx8.info";
process.env.CK_NODE_NAME = "kx8";

require("../lib/cli/fetch-session.js");
