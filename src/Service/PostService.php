<?php

/**
 * Post service.
 */

namespace App\Service;

use App\Entity\Post;
use App\Repository\PostRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class PostService.
 */
class PostService implements PostServiceInterface
{
    private const PAGINATOR_ITEMS_PER_PAGE = 10;

    public function __construct(
        private readonly PostRepository $postRepository,
        private readonly PaginatorInterface $paginator
    ) {
    }

    /**
     * Get paginated list.
     */
    public function getPaginatedList(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->postRepository->queryAll(),
            $page,
            self::PAGINATOR_ITEMS_PER_PAGE,
            [
                'sortFieldAllowList' => [
                    'post.id',
                    'post.createdAt',
                    'post.updatedAt',
                    'post.title',
                ],
                'defaultSortFieldName' => 'post.updatedAt',
                'defaultSortDirection' => 'desc',
            ]
        );
    }

    /**
     * Save entity.
     */
    public function save(Post $post): void
    {
        $this->postRepository->save($post);
    }

    /**
     * Delete entity.
     */
    public function delete(Post $post): void
    {
        $this->postRepository->delete($post);
    }
}
