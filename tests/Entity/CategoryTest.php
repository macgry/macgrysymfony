<?php

/**
 * Category entity tests.
 */

namespace App\Tests\Entity;

use App\Entity\Category;
use PHPUnit\Framework\TestCase;

/**
 * Class CategoryTest.
 */
class CategoryTest extends TestCase
{
    /**
     * Test initial values.
     */
    public function testInitialValues(): void
    {
        // given
        $category = new Category();

        // then
        $this->assertNull($category->getId());
        $this->assertNull($category->getTitle());
        $this->assertNull($category->getCreatedAt());
        $this->assertNull($category->getUpdatedAt());
        $this->assertNull($category->getSlug());
    }

    /**
     * Test title getter and setter.
     */
    public function testTitleGetterAndSetter(): void
    {
        // given
        $category = new Category();
        $title = 'Test category';

        // when
        $category->setTitle($title);

        // then
        $this->assertSame($title, $category->getTitle());
    }

    /**
     * Test created at getter and setter.
     */
    public function testCreatedAtGetterAndSetter(): void
    {
        // given
        $category = new Category();
        $date = new \DateTimeImmutable('2026-06-08 12:00:00');

        // when
        $result = $category->setCreatedAt($date);

        // then
        $this->assertSame($category, $result);
        $this->assertSame($date, $category->getCreatedAt());
    }

    /**
     * Test updated at getter and setter.
     */
    public function testUpdatedAtGetterAndSetter(): void
    {
        // given
        $category = new Category();
        $date = new \DateTimeImmutable('2026-06-08 13:00:00');

        // when
        $result = $category->setUpdatedAt($date);

        // then
        $this->assertSame($category, $result);
        $this->assertSame($date, $category->getUpdatedAt());
    }

    /**
     * Test slug getter and setter.
     */
    public function testSlugGetterAndSetter(): void
    {
        // given
        $category = new Category();
        $slug = 'test-category';

        // when
        $result = $category->setSlug($slug);

        // then
        $this->assertSame($category, $result);
        $this->assertSame($slug, $category->getSlug());
    }
}
