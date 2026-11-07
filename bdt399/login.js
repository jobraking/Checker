#!/usr/bin/env node
"use strict";

process.env.CK_NODE_DIR = __dirname;
process.env.CK_ORIGIN = "https://www.399bdt.com";
process.env.CK_NODE_NAME = "bdt399";

require("../lib/cli/login.js");
