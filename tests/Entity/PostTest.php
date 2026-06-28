<?php

/**
 * Post entity tests.
 */

namespace App\Tests\Entity;

use App\Entity\Category;
use App\Entity\Post;
use PHPUnit\Framework\TestCase;

/**
 * Class PostTest.
 */
class PostTest extends TestCase
{
    /**
     * Test initial values.
     */
    public function testInitialValues(): void
    {
        // given
        $post = new Post();

        // then
        $this->assertNull($post->getId());
        $this->assertNull($post->getTitle());
        $this->assertNull($post->getCreatedAt());
        $this->assertNull($post->getUpdatedAt());
        $this->assertNull($post->getCategory());
    }

    /**
     * Test title getter and setter.
     */
    public function testTitleGetterAndSetter(): void
    {
        // given
        $post = new Post();
        $title = 'Test post';

        // when
        $post->setTitle($title);

        // then
        $this->assertSame(
            $title,
            $post->getTitle()
        );
    }

    /**
     * Test created at getter and setter.
     */
    public function testCreatedAtGetterAndSetter(): void
    {
        // given
        $post = new Post();
        $date = new \DateTimeImmutable();

        // when
        $post->setCreatedAt($date);

        // then
        $this->assertSame(
            $date,
            $post->getCreatedAt()
        );
    }

    /**
     * Test updated at getter and setter.
     */
    public function testUpdatedAtGetterAndSetter(): void
    {
        // given
        $post = new Post();
        $date = new \DateTimeImmutable();

        // when
        $post->setUpdatedAt($date);

        // then
        $this->assertSame(
            $date,
            $post->getUpdatedAt()
        );
    }

    /**
     * Test category getter and setter.
     */
    public function testCategoryGetterAndSetter(): void
    {
        // given
        $post = new Post();
        $category = new Category();

        // when
        $result = $post->setCategory($category);

        // then
        $this->assertSame(
            $post,
            $result
        );

        $this->assertSame(
            $category,
            $post->getCategory()
        );
    }

    /**
     * Test category can be set to null.
     */
    public function testCategoryCanBeNull(): void
    {
        // given
        $post = new Post();
        $category = new Category();

        // when
        $post->setCategory($category);
        $post->setCategory(null);

        // then
        $this->assertNull(
            $post->getCategory()
        );
    }
}
