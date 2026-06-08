<?php

/**
 * Profile type tests.
 */

namespace App\Tests\Form\Type;

use App\Entity\User;
use App\Form\Type\ProfileType;
use Symfony\Component\Form\Test\TypeTestCase;

/**
 * Class ProfileTypeTest.
 */
class ProfileTypeTest extends TypeTestCase
{
    /**
     * Test submit valid data.
     */
    public function testSubmitValidData(): void
    {
        // given
        $formData = [
            'email' => 'test@example.com',
        ];

        $model = new User();
        $form = $this->factory->create(ProfileType::class, $model);

        $expected = new User();
        $expected->setEmail('test@example.com');

        // when
        $form->submit($formData);

        // then
        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($expected, $model);
    }

    /**
     * Test form has email field.
     */
    public function testFormHasEmailField(): void
    {
        // given
        $form = $this->factory->create(ProfileType::class);

        // then
        $this->assertTrue($form->has('email'));
    }
}
