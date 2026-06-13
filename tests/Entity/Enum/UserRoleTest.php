<?php

/**
 * User role tests.
 */

namespace App\Tests\Entity\Enum;

use App\Entity\Enum\UserRole;
use PHPUnit\Framework\TestCase;

/**
 * Class UserRoleTest.
 */
class UserRoleTest extends TestCase
{
    /**
     * Test role user label.
     */
    public function testRoleUserLabel(): void
    {
        // given
        $role = UserRole::ROLE_USER;

        // when
        $result = $role->label();

        // then
        $this->assertSame('label.role_user', $result);
    }

    /**
     * Test role admin label.
     */
    public function testRoleAdminLabel(): void
    {
        // given
        $role = UserRole::ROLE_ADMIN;

        // when
        $result = $role->label();

        // then
        $this->assertSame('label.role_admin', $result);
    }

    /**
     * Test role values.
     */
    public function testRoleValues(): void
    {
        // then
        $this->assertSame('ROLE_USER', UserRole::ROLE_USER->value);
        $this->assertSame('ROLE_ADMIN', UserRole::ROLE_ADMIN->value);
    }
}

