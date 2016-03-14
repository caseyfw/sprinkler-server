<?php

define("ROOT_PATH", __DIR__ . "/..");

// date_default_timezone_set('Europe/London');

$app['api.version'] = "v1";

$app['api.endpoint'] = "/api";

$app['db.options'] = array(
  'driver' => 'pdo_sqlite',
  'path' => realpath(ROOT_PATH . '/app.db'),
);