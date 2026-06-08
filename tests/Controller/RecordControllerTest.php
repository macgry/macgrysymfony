<?php

/**
 * Record controller tests.
 */

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class RecordControllerTest.
 */
class RecordControllerTest extends WebTestCase
{
    /**
     * Test route.
     *
     * @var string
     */
    public const TEST_ROUTE = '/record';

    /**
     * Test client.
     */
    private KernelBrowser $httpClient;

    /**
     * Set up tests.
     */
    public function setUp(): void
    {
        $this->httpClient = static::createClient();
    }

    /**
     * Test index route.
     */
    public function testIndexRoute(): void
    {
        $this->httpClient->request('GET', self::TEST_ROUTE);

        $this->assertEquals(302, $this->httpClient->getResponse()->getStatusCode());
    }

    public function testViewRouteNotFound(): void
    {
        $this->httpClient->request('GET', self::TEST_ROUTE . '/999999');

        $this->assertEquals(302, $this->httpClient->getResponse()->getStatusCode());
    }

    public function testBookmarksRoute(): void
    {
        $this->httpClient->request('GET', self::TEST_ROUTE . '/bookmarks');

        $this->assertEquals(302, $this->httpClient->getResponse()->getStatusCode());
    }
}
