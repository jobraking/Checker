#!/usr/bin/env node
"use strict";

process.env.CK_NODE_DIR = __dirname;
process.env.CK_ORIGIN = "https://www.bdtk3.club";
process.env.CK_NODE_NAME = "bdtk3";

require("../lib/cli/fetch-session.js");
