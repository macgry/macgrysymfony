<?php

/**
 * Category service tests.
 */

namespace App\Tests\Service;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\PostRepository;
use App\Service\CategoryService;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class CategoryServiceTest.
 */
class CategoryServiceTest extends TestCase
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

        $categoryRepository = $this->createMock(CategoryRepository::class);
        $categoryRepository->expects($this->once())
            ->method('queryAll')
            ->willReturn($queryBuilder);

        $postRepository = $this->createMock(PostRepository::class);

        $paginator = $this->createMock(PaginatorInterface::class);
        $paginator->expects($this->once())
            ->method('paginate')
            ->with(
                $queryBuilder,
                $page,
                10,
                [
                    'sortFieldAllowList' => ['category.id', 'category.title'],
                    'defaultSortFieldName' => 'category.title',
                    'defaultSortDirection' => 'asc',
                ]
            )
            ->willReturn($pagination);

        $service = new CategoryService($categoryRepository, $postRepository, $paginator);

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
        $category = new Category();

        $categoryRepository = $this->createMock(CategoryRepository::class);
        $categoryRepository->expects($this->once())
            ->method('save')
            ->with($category);

        $postRepository = $this->createMock(PostRepository::class);
        $paginator = $this->createMock(PaginatorInterface::class);

        $service = new CategoryService($categoryRepository, $postRepository, $paginator);

        // when
        $service->save($category);
    }

    /**
     * Test delete.
     */
    public function testDelete(): void
    {
        // given
        $category = new Category();

        $categoryRepository = $this->createMock(CategoryRepository::class);
        $categoryRepository->expects($this->once())
            ->method('delete')
            ->with($category);

        $postRepository = $this->createMock(PostRepository::class);
        $paginator = $this->createMock(PaginatorInterface::class);

        $service = new CategoryService($categoryRepository, $postRepository, $paginator);

        // when
        $service->delete($category);
    }

    /**
     * Test can be deleted when category has no posts.
     */
    public function testCanBeDeletedWhenCategoryHasNoPosts(): void
    {
        // given
        $category = new Category();

        $categoryRepository = $this->createMock(CategoryRepository::class);

        $postRepository = $this->createMock(PostRepository::class);
        $postRepository->expects($this->once())
            ->method('countByCategory')
            ->with($category)
            ->willReturn(0);

        $paginator = $this->createMock(PaginatorInterface::class);

        $service = new CategoryService($categoryRepository, $postRepository, $paginator);

        // when
        $result = $service->canBeDeleted($category);

        // then
        $this->assertTrue($result);
    }

    /**
     * Test can be deleted when category has posts.
     */
    public function testCanBeDeletedWhenCategoryHasPosts(): void
    {
        // given
        $category = new Category();

        $categoryRepository = $this->createMock(CategoryRepository::class);

        $postRepository = $this->createMock(PostRepository::class);
        $postRepository->expects($this->once())
            ->method('countByCategory')
            ->with($category)
            ->willReturn(3);

        $paginator = $this->createMock(PaginatorInterface::class);

        $service = new CategoryService($categoryRepository, $postRepository, $paginator);

        // when
        $result = $service->canBeDeleted($category);

        // then
        $this->assertFalse($result);
    }

    /**
     * Test can be deleted returns false on exception.
     */
    public function testCanBeDeletedReturnsFalseOnException(): void
    {
        // given
        $category = new Category();

        $categoryRepository = $this->createMock(CategoryRepository::class);

        $postRepository = $this->createMock(PostRepository::class);
        $postRepository->expects($this->once())
            ->method('countByCategory')
            ->with($category)
            ->willThrowException(new NoResultException());

        $paginator = $this->createMock(PaginatorInterface::class);

        $service = new CategoryService($categoryRepository, $postRepository, $paginator);

        // when
        $result = $service->canBeDeleted($category);

        // then
        $this->assertFalse($result);
    }
}
