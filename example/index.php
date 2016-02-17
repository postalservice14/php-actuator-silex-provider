<?php

require_once __DIR__.'/../vendor/autoload.php';

use Silex\Application;
use Actuator\Health\Indicator\DiskSpaceHealthIndicator;
use Actuator\Health\Indicator\MemcachedHealthIndicator;
use Actuator\Silex\Provider\HealthServiceProvider;

$memcached = new Memcached();
$memcached->addServer('127.0.0.1', 11211);

$app = new Application();
$app->register(new HealthServiceProvider());
$app['debug'] = true;
$app['health.indicators'] = array(
    'diskspace' => new DiskSpaceHealthIndicator(),
    'memcached' => new MemcachedHealthIndicator($memcached),
);
$app->run();
