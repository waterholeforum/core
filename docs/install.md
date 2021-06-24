# Installation

## Server Requirements

Before you install Waterhole, it's important to check that your server meets the requirements. To run Waterhole, you will need:

* **PHP 7.4+** with the following extensions: dom, gd, json, mbstring, openssl, pdo_mysql, tokenizer, intl ?
* **MySQL 8+** or **MariaDB 10.?+**

## Installing Waterhole

Waterhole uses [Composer](https://getcomposer.org) to manage its dependencies and extensions. Before installing Waterhole, you will need to [install Composer](https://getcomposer.org) on your machine. Afterwards, run this command (substituting `<path>` with the path where Composer should create the project):

```bash
composer create-project waterhole/waterhole <path>
```

Once the command has finished running, your project directory should look like this:

```
.
├── bootstrap/
├── public/
├── storage/
├── vendor/
├── .env
├── .env.example
├── .nginx.conf
├── composer.json
├── composer.lock
└── waterhole
```

The `public` folder represents your forum's web root – you will need to configure your web server to point to this directory, and set up [URL Rewriting]() as per the instructions below.

When everything is ready, navigate to your `http://yourforum.com/install` in a web browser and follow the instructions to complete the installation.

::: danger Sub-directory
You should not attempt to serve a Waterhole installation out of a sub-directory. Attempting to do so could expose sensitive files present within your Waterhole installation.
:::

## URL Rewriting

### Apache

Waterhole includes a `public/.htaccess` file that is used to provide URLs without the `index.php` front controller in the path. Before serving Waterhole with Apache, be sure to enable the `mod_rewrite` module and set `AllowOverride All` so the `.htaccess` file will be honored by the server.

### Nginx

Waterhole includes a `.nginx.conf` file – make sure it has been uploaded correctly. Then, assuming you have a PHP site set up within Nginx, add the following to your server's configuration block:

```nginx
include /path/to/flarum/.nginx.conf;
```
