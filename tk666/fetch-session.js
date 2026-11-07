#!/usr/bin/env node
"use strict";

process.env.CK_NODE_DIR = __dirname;
process.env.CK_ORIGIN = "https://www.tk666.best";
process.env.CK_NODE_NAME = "tk666";

require("../lib/cli/fetch-session.js");
