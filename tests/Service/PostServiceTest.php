<?php

/**
 * Post service tests.
 */

namespace App\Tests\Service;

use App\Entity\Post;
use App\Repository\PostRepository;
use App\Service\PostService;
use Doctrine\ORM\QueryBuilder;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class PostServiceTest.
 */
class PostServiceTest extends TestCase
{
    /**
     * Test get paginated list.
     */
    public function testGetPaginatedList(): void
    {
        // given
        $page = 1;
        $queryBuilder = $this->createMock(QueryBuilder::class);
        $pagination = $this->createMock(PaginationInterface::class);

        $postRepository = $this->createMock(PostRepository::class);
        $postRepository->expects($this->once())
            ->method('queryAll')
            ->willReturn($queryBuilder);

        $paginator = $this->createMock(PaginatorInterface::class);
        $paginator->expects($this->once())
            ->method('paginate')
            ->with(
                $queryBuilder,
                $page,
                10,
                [
                    'sortFieldAllowList' => [
                        'post.id',
                        'post.createdAt',
                        'post.updatedAt',
                        'post.title',
                        'category.title',
                    ],
                    'defaultSortFieldName' => 'post.updatedAt',
                    'defaultSortDirection' => 'desc',
                ]
            )
            ->willReturn($pagination);

        $service = new PostService($postRepository, $paginator);

        // when
        $result = $service->getPaginatedList($page);

        // then
        $this->assertSame($pagination, $result);
    }

    /**
     * Test save.
     */
    public function testSave(): void
    {
        // given
        $post = new Post();

        $postRepository = $this->createMock(PostRepository::class);
        $postRepository->expects($this->once())
            ->method('save')
            ->with($post);

        $paginator = $this->createMock(PaginatorInterface::class);

        $service = new PostService($postRepository, $paginator);

        // when
        $service->save($post);
    }

    /**
     * Test delete.
     */
    public function testDelete(): void
    {
        // given
        $post = new Post();

        $postRepository = $this->createMock(PostRepository::class);
        $postRepository->expects($this->once())
            ->method('delete')
            ->with($post);

        $paginator = $this->createMock(PaginatorInterface::class);

        $service = new PostService($postRepository, $paginator);

        // when
        $service->delete($post);
    }
}
