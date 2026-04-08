<?php

namespace App\Service;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\TaskRepository;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\NonUniqueResultException;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

class CategoryService implements CategoryServiceInterface
{
    private const PAGINATOR_ITEMS_PER_PAGE = 10;

    public function __construct(
        private readonly CategoryRepository $categoryRepository,
        private readonly TaskRepository $taskRepository, // <-- dodane
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
        // Gedmo Timestampable ustawia createdAt i updatedAt automatycznie
        $this->categoryRepository->save($category);
    }

    /**
     * Can Category be deleted?
     *
     * @param Category $category Category entity
     *
     * @return bool
     */
    public function canBeDeleted(Category $category): bool
    {
        try {
            $result = $this->taskRepository->countByCategory($category);

            return $result === 0;
        } catch (NoResultException|NonUniqueResultException) {
            return false;
        }
    }
}
