<?php

namespace App\Service;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\PostRepository;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\NonUniqueResultException;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

class CategoryService implements CategoryServiceInterface
{
    private const PAGINATOR_ITEMS_PER_PAGE = 10;

    public function __construct(
        private readonly CategoryRepository $categoryRepository,
        private readonly PostRepository $postRepository,
        private readonly PaginatorInterface $paginator
    ) {
    }

    public function getPaginatedList(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->categoryRepository->queryAll(),
            $page,
            self::PAGINATOR_ITEMS_PER_PAGE,
            [
                'sortFieldAllowList' => ['category.id', 'category.title'],
                'defaultSortFieldName' => 'category.title',
                'defaultSortDirection' => 'asc',
            ]
        );
    }

    public function save(Category $category): void
    {
        $this->categoryRepository->save($category);
    }

    public function delete(Category $category): void
    {
        $this->categoryRepository->delete($category);
    }

    /**
     * Can Category be deleted?
     */
    public function canBeDeleted(Category $category): bool
    {
        try {
            $result = $this->postRepository->countByCategory($category);

            return $result === 0;
        } catch (NoResultException|NonUniqueResultException) {
            return false;
        }
    }
}
