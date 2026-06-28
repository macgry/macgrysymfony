<?php

/**
 * Change password type tests.
 */

namespace App\Tests\Form\Type;

use App\Form\Type\ChangePasswordType;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Validator\Validation;

/**
 * Class ChangePasswordTypeTest.
 */
class ChangePasswordTypeTest extends TypeTestCase
{
    /**
     * Test submit valid data.
     */
    public function testSubmitValidData(): void
    {
        // given
        $formData = [
            'oldPassword' => 'old-password',
            'newPassword' => 'new-password',
        ];

        $form = $this->factory->create(ChangePasswordType::class);

        // when
        $form->submit($formData);

        // then
        $this->assertTrue($form->isSynchronized());
        $this->assertSame($formData, $form->getData());
    }

    /**
     * Test form has password fields.
     */
    public function testFormHasPasswordFields(): void
    {
        // given
        $form = $this->factory->create(ChangePasswordType::class);

        // then
        $this->assertTrue($form->has('oldPassword'));
        $this->assertTrue($form->has('newPassword'));
    }

    /**
     * Get form extensions.
     *
     * @return array Form extensions
     */
    protected function getExtensions(): array
    {
        $validator = Validation::createValidator();

        return [
            new ValidatorExtension($validator),
        ];
    }
}
