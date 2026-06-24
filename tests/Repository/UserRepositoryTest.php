<?php

/**
 * User repository tests.
 */

namespace App\Tests\Repository;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

/**
 * Class UserRepositoryTest.
 */
class UserRepositoryTest extends KernelTestCase
{
    /**
     * Entity manager.
     */
    private EntityManagerInterface $entityManager;

    /**
     * User repository.
     */
    private UserRepository $userRepository;

    /**
     * Set up test.
     */
    public function setUp(): void
    {
        $this->entityManager = static::getContainer()->get(EntityManagerInterface::class);
        $this->userRepository = static::getContainer()->get(UserRepository::class);
    }

    /**
     * Test upgrade password.
     */
    public function testUpgradePassword(): void
    {
        // given
        $user = new User();
        $user->setEmail('user-repository@example.com');
        $user->setPassword('old-password');

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        // when
        $this->userRepository->upgradePassword($user, 'new-hashed-password');

        // then
        $this->assertSame('new-hashed-password', $user->getPassword());
    }

    /**
     * Test upgrade password throws exception for unsupported user.
     */
    public function testUpgradePasswordThrowsExceptionForUnsupportedUser(): void
    {
        // given
        $user = $this->createMock(PasswordAuthenticatedUserInterface::class);

        // then
        $this->expectException(UnsupportedUserException::class);

        // when
        $this->userRepository->upgradePassword($user, 'new-hashed-password');
    }
}
