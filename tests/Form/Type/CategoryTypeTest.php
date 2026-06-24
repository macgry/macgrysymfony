<?php

/**
 * Category type tests.
 */

namespace App\Tests\Form\Type;

use App\Entity\Category;
use App\Form\Type\CategoryType;
use Symfony\Component\Form\Test\TypeTestCase;

/**
 * Class CategoryTypeTest.
 */
class CategoryTypeTest extends TypeTestCase
{
    /**
     * Test submit valid data.
     */
    public function testSubmitValidData(): void
    {
        // given
        $formData = [
            'title' => 'Test category',
        ];

        $model = new Category();
        $form = $this->factory->create(CategoryType::class, $model);

        $expected = new Category();
        $expected->setTitle('Test category');

        // when
        $form->submit($formData);

        // then
        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($expected, $model);
    }

    /**
     * Test form has title field.
     */
    public function testFormHasTitleField(): void
    {
        // given
        $form = $this->factory->create(CategoryType::class);

        // then
        $this->assertTrue($form->has('title'));
    }

    /**
     * Test block prefix.
     */
    public function testGetBlockPrefix(): void
    {
        // given
        $type = new CategoryType();

        // then
        $this->assertSame('category', $type->getBlockPrefix());
    }
}
