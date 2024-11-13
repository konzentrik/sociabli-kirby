# Kirby Sociabli

![GitHub release](https://img.shields.io/github/release/konzentrik/sociabliKirby.svg?maxAge=1800) ![License](https://img.shields.io/github/license/mashape/apistatus.svg) ![Kirby Version](https://img.shields.io/badge/Kirby-4%2B-black.svg)

---

## Installation

Use one of these methods to install the plugin:

-   composer (recommended): `composer require konzentrik/sociabli-kirby`
-   zip file: unzip [main.zip](https://github.com/konzentrik/sociabli-kirby/releases/latest) as folder `site/plugins/sociabli`

## Usage

After installing the plugin, it will trigger the Sociabli Webhook whenever you publish a page. Triggering the workflows you activated in Sociabli.
Yo can configure the plugin to only trigger the webhook when a page has a certain template or blueprint.

In order to use this Plugin, you have at least to set the `sociabli.webhook` options in your `config.php` file.

```php
<?php

return [
'konzentrik.sociabli.webhookId' => 'YOUR-ID',
'konzentrik.sociabli.token' => 'YOUR-TOKEN',
];
```

You can find the `webhookId` and `token` in your Sociabli account.

## Configuring when to cross post

Currently you can configure the plugin to only cross post pages with a certain template or blueprint. You can also block certain templates or blueprints from being cross posted.

For example, if you only want to cross post pages with the template `article` you can configure the plugin like this:

```php
<?php
'konzentrik.sociabli.templates.allowed' => ['article'],
```

If you want to allow any template to cross post, you can leave the `templates.allowed` array empty.

If you want to block certain templates from cross posting, you can use the `templates.blocked` option. For example if you have a templates for legal pages, like your privacy page, you can block them from cross posting like this:

```php
<?php
'konzentrik.sociabli.templates.blocked' => ['legal'],
```

All pages with other templates will be cross posted.


## Options

Please make sure to prefix all options with `konzentrik.sociabli` or use the array notation.

| Option                            | Default  | Description                                                                                      |
| --------------------------------- | -------- | ------------------------------------------------------------------------------------------------ |
| `webhookId` | `null` | can be found on your account page |
| `token` | `null` | can be found on your account page |
| `publishStatus` | `draft` | initial status when posting to for example medium.com - set to `draft` or `public` |
| `fields.intro` | `description` | the name of your kirby field used for the post intro |
| `fields.text` | `text` | the name of your kirby field used for the main text |
| `fields.image` | `null` | the name of your kirby field used for the hero image |
| `fields.tags` | `tags` | the name of your kirby tags field used for tagging |
| `templates.allowed` | `[]` | a list of template/blueprint names which should be cross posted |
| `templates.blocked` | `[]` | a list of template/blueprint names which should never cross post |
