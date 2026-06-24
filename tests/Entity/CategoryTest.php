<?php

/**
 * Category entity tests.
 */

namespace App\Tests\Entity;

use App\Entity\Category;
use App\Entity\Post;
use Doctrine\Common\Collections\Collection;
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
        $this->assertInstanceOf(Collection::class, $category->getPosts());
        $this->assertCount(0, $category->getPosts());
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

    /**
     * Test add post.
     */
    public function testAddPost(): void
    {
        // given
        $category = new Category();
        $post = new Post();

        // when
        $result = $category->addPost($post);

        // then
        $this->assertSame($category, $result);
        $this->assertCount(1, $category->getPosts());
        $this->assertTrue($category->getPosts()->contains($post));
        $this->assertSame($category, $post->getCategory());
    }

    /**
     * Test add the same post twice.
     */
    public function testAddSamePostTwice(): void
    {
        // given
        $category = new Category();
        $post = new Post();

        // when
        $category->addPost($post);
        $category->addPost($post);

        // then
        $this->assertCount(1, $category->getPosts());
        $this->assertSame($category, $post->getCategory());
    }

    /**
     * Test remove post.
     */
    public function testRemovePost(): void
    {
        // given
        $category = new Category();
        $post = new Post();
        $category->addPost($post);

        // when
        $result = $category->removePost($post);

        // then
        $this->assertSame($category, $result);
        $this->assertCount(0, $category->getPosts());
        $this->assertFalse($category->getPosts()->contains($post));
        $this->assertNull($post->getCategory());
    }

    /**
     * Test remove post that does not belong to category.
     */
    public function testRemovePostThatDoesNotBelongToCategory(): void
    {
        // given
        $category = new Category();
        $post = new Post();

        // when
        $result = $category->removePost($post);

        // then
        $this->assertSame($category, $result);
        $this->assertCount(0, $category->getPosts());
    }
}
