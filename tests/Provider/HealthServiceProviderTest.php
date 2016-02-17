<?php

namespace Actuator\Silex\Test\Provider;

use Actuator\Health\Indicator\DiskSpaceHealthIndicator;
use Actuator\Silex\Provider\HealthServiceProvider;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class HealthServiceProviderTest extends \PHPUnit_Framework_TestCase
{
    public function testConfigServiceProviders()
    {
        $app = new Application();
        $app->register(new HealthServiceProvider());

        $this->assertNotNull($app['health.aggregator']);
        $this->assertInternalType('array', $app['health.indicators']);
        $this->assertEquals('/health', $app['health.endpoint']);
    }

    public function testViewTranslation()
    {
        $app = new Application();
        $app->register(new HealthServiceProvider());
        $app['health.indicators'] = array(new DiskSpaceHealthIndicator());

        $response = $app->handle(Request::create('/health'));

        $this->assertNotNull($app['health.aggregator']);
        $this->assertInternalType('array', $app['health.indicators']);
        $this->assertEquals('/health', $app['health.endpoint']);
    }
}
