<?php

/**
 * Post repository tests.
 */

namespace App\Tests\Repository;

use App\Entity\Category;
use App\Entity\Post;
use App\Repository\CategoryRepository;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class PostRepositoryTest.
 */
class PostRepositoryTest extends KernelTestCase
{
    /**
     * Post repository.
     */
    private PostRepository $postRepository;

    /**
     * Category repository.
     */
    private CategoryRepository $categoryRepository;

    /**
     * Set up test.
     */
    protected function setUp(): void
    {
        $this->postRepository = static::getContainer()->get(PostRepository::class);
        $this->categoryRepository = static::getContainer()->get(CategoryRepository::class);
    }

    /**
     * Test save.
     */
    public function testSave(): void
    {
        // given
        $category = $this->createCategory('Test category save');

        $post = new Post();
        $post->setTitle('Test post save');
        $post->setCategory($category);

        // when
        $this->postRepository->save($post);

        // then
        $this->assertNotNull($post->getId());
    }

    /**
     * Test delete.
     */
    public function testDelete(): void
    {
        // given
        $category = $this->createCategory('Test category delete');

        $post = new Post();
        $post->setTitle('Test post delete');
        $post->setCategory($category);

        $this->postRepository->save($post);
        $postId = $post->getId();

        // when
        $this->postRepository->delete($post);

        // then
        $this->assertNull($this->postRepository->find($postId));
    }

    /**
     * Test count by category.
     */
    public function testCountByCategory(): void
    {
        // given
        $category = $this->createCategory('Test category count');

        $firstPost = new Post();
        $firstPost->setTitle('First test post');
        $firstPost->setCategory($category);
        $this->postRepository->save($firstPost);

        $secondPost = new Post();
        $secondPost->setTitle('Second test post');
        $secondPost->setCategory($category);
        $this->postRepository->save($secondPost);

        // when
        $result = $this->postRepository->countByCategory($category);

        // then
        $this->assertSame(2, $result);
    }

    /**
     * Test query all.
     */
    public function testQueryAll(): void
    {
        // when
        $queryBuilder = $this->postRepository->queryAll();

        // then
        $this->assertSame('post', $queryBuilder->getRootAliases()[0]);
    }

    /**
     * Create category.
     *
     * @param string $title Category title
     *
     * @return Category Category entity
     */
    private function createCategory(string $title): Category
    {
        $category = new Category();
        $category->setTitle($title);

        $this->categoryRepository->save($category);

        return $category;
    }
}
