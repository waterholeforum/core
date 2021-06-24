# Configuration

## Environment Configuration

Waterhole configuration that is related to your server environment (eg. database and service credentials, URL information) is stored in the `.env` file in your installation's root directory. You can also set these environment variables at the server- or system-level.

**The `.env` file should not be committed to source control.** This way if you are running your forum in different environments (eg. a local staging environment and a production environment) you can have different configuration for each of them.

## Project Configuration

Configuration related to your project (eg. the forum name and logo, extension settings) is stored in `bootstrap/config.yaml`. This file can be committed to source control, as it contains settings that you'll want to sync across different environments.

