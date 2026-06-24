<?php

/**
 * Admin profile controller tests.
 */

namespace App\Tests\Controller\Admin;

use App\Entity\Enum\UserRole;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class ProfileControllerTest.
 */
class ProfileControllerTest extends WebTestCase
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
     * Test edit profile route for anonymous user.
     */
    public function testEditProfileRouteAnonymousUser(): void
    {
        // when
        $this->httpClient->request('GET', '/admin/profile/edit');

        // then
        $this->assertEquals(302, $this->httpClient->getResponse()->getStatusCode());
    }

    /**
     * Test password route for anonymous user.
     */
    public function testPasswordRouteAnonymousUser(): void
    {
        // when
        $this->httpClient->request('GET', '/admin/profile/password');

        // then
        $this->assertEquals(302, $this->httpClient->getResponse()->getStatusCode());
    }

    /**
     * Test edit profile route for admin user.
     */
    public function testEditProfileRouteAdminUser(): void
    {
        // given
        $adminUser = $this->createUser([UserRole::ROLE_ADMIN->value]);
        $this->httpClient->loginUser($adminUser);

        // when
        $this->httpClient->request('GET', '/admin/profile/edit');

        // then
        $this->assertEquals(200, $this->httpClient->getResponse()->getStatusCode());
    }

    /**
     * Test password route for admin user.
     */
    public function testPasswordRouteAdminUser(): void
    {
        // given
        $adminUser = $this->createUser([UserRole::ROLE_ADMIN->value], 'admin-password@example.com');
        $this->httpClient->loginUser($adminUser);

        // when
        $this->httpClient->request('GET', '/admin/profile/password');

        // then
        $this->assertEquals(200, $this->httpClient->getResponse()->getStatusCode());
    }

    /**
     * Create user.
     *
     * @param array<int, string> $roles User roles
     * @param string             $email User email
     *
     * @return User User entity
     */
    private function createUser(array $roles, string $email = 'admin-profile@example.com'): User
    {
        $passwordHasher = static::getContainer()->get('security.password_hasher');

        $user = new User();
        $user->setEmail($email);
        $user->setRoles($roles);
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
