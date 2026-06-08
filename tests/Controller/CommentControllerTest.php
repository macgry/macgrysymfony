<?php

/**
 * Comment controller tests.
 */

namespace App\Tests\Controller;

use App\Entity\Category;
use App\Entity\Post;
use App\Repository\CategoryRepository;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class CommentControllerTest.
 */
class CommentControllerTest extends WebTestCase
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
     * Test add comment with invalid form.
     */
    public function testAddCommentWithInvalidForm(): void
    {
        // given
        $post = $this->createPost();

        // when
        $this->httpClient->request('POST', '/comment/post/'.$post->getId().'/add');
        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();

        // then
        $this->assertEquals(302, $resultStatusCode);
    }

    /**
     * Create category.
     *
     * @return Category Category entity
     */
    private function createCategory(): Category
    {
        $category = new Category();
        $category->setTitle('Test category');

        $categoryRepository = static::getContainer()->get(CategoryRepository::class);
        $categoryRepository->save($category);

        return $category;
    }

    /**
     * Create post.
     *
     * @return Post Post entity
     */
    private function createPost(): Post
    {
        $post = new Post();
        $post->setTitle('Test post');
        $post->setCategory($this->createCategory());

        $postRepository = static::getContainer()->get(PostRepository::class);
        $postRepository->save($post);

        return $post;
    }
}
