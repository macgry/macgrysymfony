<?php

/**
 * Comment type tests.
 */

namespace App\Tests\Form\Type;

use App\Entity\Comment;
use App\Form\Type\CommentType;
use Symfony\Component\Form\Test\TypeTestCase;

/**
 * Class CommentTypeTest.
 */
class CommentTypeTest extends TypeTestCase
{
    /**
     * Test submit valid data.
     */
    public function testSubmitValidData(): void
    {
        // given
        $formData = [
            'nickname' => 'John',
            'email' => 'john@example.com',
            'content' => 'Test comment',
        ];

        $comment = new Comment();
        $form = $this->factory->create(CommentType::class, $comment);

        // when
        $form->submit($formData);

        // then
        $this->assertTrue($form->isSynchronized());
        $this->assertSame('John', $comment->getNickname());
        $this->assertSame('john@example.com', $comment->getEmail());
        $this->assertSame('Test comment', $comment->getContent());
    }

    /**
     * Test form has fields.
     */
    public function testFormHasFields(): void
    {
        // given
        $form = $this->factory->create(CommentType::class);

        // then
        $this->assertTrue($form->has('nickname'));
        $this->assertTrue($form->has('email'));
        $this->assertTrue($form->has('content'));
    }
}
