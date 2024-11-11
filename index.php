<?php

namespace konzentrik\Sociabli;

use Kirby\Cms\App as Kirby;

@include_once __DIR__ . '/vendor/autoload.php';

Kirby::plugin('konzentrik/sociabli', [
    'hooks' => require_once __DIR__ . '/plugin/hooks.php',
]);
