#!/usr/bin/env node
"use strict";

process.env.CK_NODE_DIR = __dirname;
process.env.CK_ORIGIN = "https://www.tk999.org";
process.env.CK_NODE_NAME = "tk999";

require("../lib/cli/login.js");
