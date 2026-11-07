#!/usr/bin/env node
"use strict";

process.env.CK_NODE_DIR = __dirname;
process.env.CK_ORIGIN = "https://www.88hbajee.com";
process.env.CK_NODE_NAME = "88hbajee";

require("../lib/cli/fetch-session.js");
