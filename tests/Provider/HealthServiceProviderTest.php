<?php

namespace Actuator\Silex\Test\Provider;

use Actuator\Health\Indicator\DiskSpaceHealthIndicator;
use Actuator\Health\Indicator\DiskSpaceHealthIndicatorProperties;
use Actuator\Health\Status;
use Actuator\Silex\Provider\HealthServiceProvider;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
        $app['health.indicators'] = array('diskspace' => new DiskSpaceHealthIndicator());

        $response = $app->handle(Request::create('/health'));
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));

        $jsonBody = json_decode($response->getContent(), true);
        $this->assertEquals(Status::UP, $jsonBody['status']);
        $this->assertEquals(Status::UP, $jsonBody['diskspace']['status']);
        $this->assertGreaterThan(0, $jsonBody['diskspace']['free']);
        $this->assertGreaterThan(0, $jsonBody['diskspace']['total']);
        $this->assertEquals(
            DiskSpaceHealthIndicatorProperties::DEFAULT_THRESHOLD,
            $jsonBody['diskspace']['threshold']
        );
    }
}
