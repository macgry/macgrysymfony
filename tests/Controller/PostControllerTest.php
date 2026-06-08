<?php

/**
 * Post controller tests.
 */

namespace App\Tests\Controller;

use App\Entity\Category;
use App\Entity\Enum\UserRole;
use App\Entity\Post;
use App\Entity\User;
use App\Repository\CategoryRepository;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class PostControllerTest.
 */
class PostControllerTest extends WebTestCase
{
    /**
     * Test route.
     *
     * @var string
     */
    public const TEST_ROUTE = '/post';

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
     * Test index route for anonymous user.
     */
    public function testIndexRouteAnonymousUser(): void
    {
        // given
        $expectedStatusCode = 302;

        // when
        $this->httpClient->request('GET', self::TEST_ROUTE);
        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $resultStatusCode);
    }

    /**
     * Test index route for admin user.
     */
    public function testIndexRouteAdminUser(): void
    {
        // given
        $expectedStatusCode = 200;
        $adminUser = $this->createUser([UserRole::ROLE_ADMIN->value]);
        $this->httpClient->loginUser($adminUser);

        // when
        $this->httpClient->request('GET', self::TEST_ROUTE);
        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $resultStatusCode);
    }

    /**
     * Test view route for admin user.
     */
    public function testViewRouteAdminUser(): void
    {
        // given
        $expectedStatusCode = 200;
        $adminUser = $this->createUser([UserRole::ROLE_ADMIN->value], 'admin-view@example.com');
        $this->httpClient->loginUser($adminUser);

        $post = $this->createPost();

        // when
        $this->httpClient->request('GET', self::TEST_ROUTE.'/'.$post->getId());
        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $resultStatusCode);
    }

    /**
     * Test view route for not found post.
     */
    public function testViewRouteNotFound(): void
    {
        // given
        $expectedStatusCode = 404;
        $adminUser = $this->createUser([UserRole::ROLE_ADMIN->value], 'admin-not-found@example.com');
        $this->httpClient->loginUser($adminUser);

        // when
        $this->httpClient->request('GET', self::TEST_ROUTE.'/999999');
        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $resultStatusCode);
    }

    /**
     * Create post.
     *
     * @return Post Post entity
     */
    private function createPost(): Post
    {
        $category = new Category();
        $category->setTitle('Test category');

        $categoryRepository = static::getContainer()->get(CategoryRepository::class);
        $categoryRepository->save($category);

        $post = new Post();
        $post->setTitle('Test post');
        $post->setCategory($category);

        $postRepository = static::getContainer()->get(PostRepository::class);
        $postRepository->save($post);

        return $post;
    }

    /**
     * Create user.
     *
     * @param array<int, string> $roles User roles
     * @param string             $email User email
     *
     * @return User User entity
     */
    private function createUser(array $roles, string $email = 'admin@example.com'): User
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
