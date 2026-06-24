<?php

/**
 * Record repository tests.
 */

namespace App\Tests\Repository;

use App\Repository\RecordRepository;
use PHPUnit\Framework\TestCase;

/**
 * Class RecordRepositoryTest.
 */
class RecordRepositoryTest extends TestCase
{
    /**
     * Test find all.
     */
    public function testFindAll(): void
    {
        // given
        $repository = new RecordRepository();

        // when
        $result = $repository->findAll();

        // then
        $this->assertCount(3, $result);
        $this->assertArrayHasKey(1, $result);
        $this->assertArrayHasKey(2, $result);
        $this->assertArrayHasKey(3, $result);
    }

    /**
     * Test find one by id.
     */
    public function testFindOneById(): void
    {
        // given
        $repository = new RecordRepository();

        // when
        $result = $repository->findOneById(1);

        // then
        $this->assertNotNull($result);
        $this->assertSame(1, $result['id']);
        $this->assertArrayHasKey('title', $result);
        $this->assertArrayHasKey('content', $result);
        $this->assertArrayHasKey('tags', $result);
    }

    /**
     * Test find one by id returns null for missing record.
     */
    public function testFindOneByIdReturnsNullForMissingRecord(): void
    {
        // given
        $repository = new RecordRepository();

        // when
        $result = $repository->findOneById(999);

        // then
        $this->assertNull($result);
    }
}
