<?php

/**
 * Record controller tests.
 */

namespace App\Tests\Controller;

use App\Entity\Enum\UserRole;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
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
     * Test index route for anonymous user.
     */
    public function testIndexRouteAnonymousUser(): void
    {
        $this->httpClient->request('GET', self::TEST_ROUTE);

        $this->assertEquals(302, $this->httpClient->getResponse()->getStatusCode());
    }

    /**
     * Test index route for logged user.
     */
    public function testIndexRouteLoggedUser(): void
    {
        $user = $this->createUser('record-index@example.com');
        $this->httpClient->loginUser($user);

        $this->httpClient->request('GET', self::TEST_ROUTE);

        $this->assertEquals(200, $this->httpClient->getResponse()->getStatusCode());
    }

    /**
     * Test view route for logged user.
     */
    public function testViewRouteLoggedUser(): void
    {
        $user = $this->createUser('record-view@example.com');
        $this->httpClient->loginUser($user);

        $this->httpClient->request('GET', self::TEST_ROUTE.'/1');

        $this->assertEquals(200, $this->httpClient->getResponse()->getStatusCode());
    }

    /**
     * Test view route not found for logged user.
     */
    public function testViewRouteNotFoundLoggedUser(): void
    {
        $user = $this->createUser('record-not-found@example.com');
        $this->httpClient->loginUser($user);

        $this->httpClient->request('GET', self::TEST_ROUTE.'/999999');

        $this->assertEquals(404, $this->httpClient->getResponse()->getStatusCode());
    }

    /**
     * Create user.
     *
     * @param string $email User email
     *
     * @return User User entity
     */
    private function createUser(string $email): User
    {
        $passwordHasher = static::getContainer()->get('security.password_hasher');

        $user = new User();
        $user->setEmail($email);
        $user->setRoles([UserRole::ROLE_USER->value]);
        $user->setPassword(
            $passwordHasher->hashPassword(
                $user,
                'password'
            )
        );

        $entityManager = static::getContainer()->get(EntityManagerInterface::class);
        $entityManager->persist($user);
        $entityManager->flush();

        return $user;
    }
}
