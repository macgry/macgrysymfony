<?php

/**
 * User entity tests.
 */

namespace App\Tests\Entity;

use App\Entity\Enum\UserRole;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

/**
 * Class UserTest.
 */
class UserTest extends TestCase
{
    /**
     * Test initial values.
     */
    public function testInitialValues(): void
    {
        // given
        $user = new User();

        // then
        $this->assertNull($user->getId());
        $this->assertNull($user->getEmail());
        $this->assertNull($user->getPassword());
        $this->assertSame('', $user->getUserIdentifier());
        $this->assertContains(UserRole::ROLE_USER->value, $user->getRoles());
    }

    /**
     * Test email getter and setter.
     */
    public function testEmailGetterAndSetter(): void
    {
        // given
        $user = new User();
        $email = 'user@example.com';

        // when
        $user->setEmail($email);

        // then
        $this->assertSame($email, $user->getEmail());
        $this->assertSame($email, $user->getUserIdentifier());
    }

    /**
     * Test roles getter and setter.
     */
    public function testRolesGetterAndSetter(): void
    {
        // given
        $user = new User();
        $roles = [UserRole::ROLE_ADMIN->value];

        // when
        $user->setRoles($roles);

        // then
        $this->assertContains(UserRole::ROLE_ADMIN->value, $user->getRoles());
        $this->assertContains(UserRole::ROLE_USER->value, $user->getRoles());
    }

    /**
     * Test duplicated roles are unique.
     */
    public function testDuplicatedRolesAreUnique(): void
    {
        // given
        $user = new User();
        $roles = [
            UserRole::ROLE_USER->value,
            UserRole::ROLE_USER->value,
        ];

        // when
        $user->setRoles($roles);

        // then
        $this->assertSame(
            [UserRole::ROLE_USER->value],
            array_values($user->getRoles())
        );
    }

    /**
     * Test password getter and setter.
     */
    public function testPasswordGetterAndSetter(): void
    {
        // given
        $user = new User();
        $password = 'hashed-password';

        // when
        $user->setPassword($password);

        // then
        $this->assertSame($password, $user->getPassword());
    }

    /**
     * Test erase credentials.
     */
    public function testEraseCredentials(): void
    {
        // given
        $user = new User();

        // when
        $user->eraseCredentials();

        // then
        $this->assertTrue(true);
    }
}
