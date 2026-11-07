#!/usr/bin/env node
"use strict";

process.env.CK_NODE_DIR = __dirname;
process.env.CK_ORIGIN = "https://www.399bet02.com";
process.env.CK_NODE_NAME = "399bet02";

require("../lib/cli/account-check.js");
