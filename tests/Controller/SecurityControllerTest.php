<?php

/**
 * Security controller tests.
 */

namespace App\Tests\Controller;

use App\Entity\Enum\UserRole;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class SecurityControllerTest.
 */
class SecurityControllerTest extends WebTestCase
{
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
     * Test login route for anonymous user.
     */
    public function testLoginRouteAnonymousUser(): void
    {
        // when
        $this->httpClient->request('GET', '/login');

        // then
        $this->assertEquals(200, $this->httpClient->getResponse()->getStatusCode());
    }

    /**
     * Test login route for logged user.
     */
    public function testLoginRouteLoggedUser(): void
    {
        // given
        $user = $this->createUser();

        $this->httpClient->loginUser($user);

        // when
        $this->httpClient->request('GET', '/login');

        // then
        $this->assertEquals(302, $this->httpClient->getResponse()->getStatusCode());
    }

    /**
     * Create user.
     *
     * @return User User entity
     */
    private function createUser(): User
    {
        $passwordHasher = static::getContainer()->get('security.password_hasher');

        $user = new User();
        $user->setEmail('security-user@example.com');
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

    /**
     * Test logout throws logic exception.
     */
    public function testLogoutThrowsLogicException(): void
    {
        // given
        $controller = new \App\Controller\SecurityController();

        // then
        $this->expectException(\LogicException::class);

        // when
        $controller->logout();
    }
}
