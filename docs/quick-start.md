# Quick Start

This is a step-by-step guide to set up a basic community with Waterhole. We'll go through the essentials of installation, configuration, and customization.

## Overview

Waterhole requires a little bit of technical know-how to install and configure. If you're not a technical person, you may need to get some assistance from an Expert. In any case, this guide makes a few assumptions:

1. You have a local dev environment with Composer installed.
2. You are comfortable running commands on the command line.

If these didn't make you gawk, then let's get started. 

## Install Waterhole

There are a few ways to install Waterhole, but the simplest is to use Composer's `create-project` command. Run this command in your `~/Sites` directory or similar:

```sh
composer create-project --prefer-dist waterhole/waterhole outback-adventurers
```

This will clone a copy of our skeleton project, install the Composer dependencies, and kick off the Waterhole installation process.

