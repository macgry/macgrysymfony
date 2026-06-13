<?php

/**
 * Category repository tests.
 */

namespace App\Tests\Repository;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class CategoryRepositoryTest.
 */
class CategoryRepositoryTest extends KernelTestCase
{
    /**
     * Category repository.
     */
    private CategoryRepository $categoryRepository;

    /**
     * Set up test.
     */
    protected function setUp(): void
    {
        $this->categoryRepository = static::getContainer()->get(CategoryRepository::class);
    }

    /**
     * Test save.
     */
    public function testSave(): void
    {
        $category = new Category();
        $category->setTitle('Test category save');

        $this->categoryRepository->save($category);

        $this->assertNotNull($category->getId());
    }

    /**
     * Test delete.
     */
    public function testDelete(): void
    {
        $category = new Category();
        $category->setTitle('Test category delete');

        $this->categoryRepository->save($category);
        $categoryId = $category->getId();

        $this->categoryRepository->delete($category);

        $this->assertNull($this->categoryRepository->find($categoryId));
    }

    /**
     * Test query all.
     */
    public function testQueryAll(): void
    {
        $queryBuilder = $this->categoryRepository->queryAll();

        $this->assertSame('category', $queryBuilder->getRootAliases()[0]);
    }
}
