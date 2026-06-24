<?php

/**
 * Comment entity tests.
 */

namespace App\Tests\Entity;

use App\Entity\Comment;
use App\Entity\Post;
use PHPUnit\Framework\TestCase;

/**
 * Class CommentTest.
 */
class CommentTest extends TestCase
{
    /**
     * Test initial values.
     */
    public function testInitialValues(): void
    {
        // given
        $comment = new Comment();

        // then
        $this->assertNull($comment->getId());
        $this->assertNull($comment->getNickname());
        $this->assertNull($comment->getEmail());
        $this->assertNull($comment->getContent());
        $this->assertNull($comment->getPost());
        $this->assertNull($comment->getCreatedAt());
    }

    /**
     * Test nickname getter and setter.
     */
    public function testNicknameGetterAndSetter(): void
    {
        // given
        $comment = new Comment();
        $nickname = 'John';

        // when
        $comment->setNickname($nickname);

        // then
        $this->assertSame($nickname, $comment->getNickname());
    }

    /**
     * Test email getter and setter.
     */
    public function testEmailGetterAndSetter(): void
    {
        // given
        $comment = new Comment();
        $email = 'john@example.com';

        // when
        $comment->setEmail($email);

        // then
        $this->assertSame($email, $comment->getEmail());
    }

    /**
     * Test content getter and setter.
     */
    public function testContentGetterAndSetter(): void
    {
        // given
        $comment = new Comment();
        $content = 'Test comment content';

        // when
        $comment->setContent($content);

        // then
        $this->assertSame($content, $comment->getContent());
    }

    /**
     * Test post getter and setter.
     */
    public function testPostGetterAndSetter(): void
    {
        // given
        $comment = new Comment();
        $post = new Post();

        // when
        $comment->setPost($post);

        // then
        $this->assertSame($post, $comment->getPost());
    }

    /**
     * Test post can be null.
     */
    public function testPostCanBeNull(): void
    {
        // given
        $comment = new Comment();
        $post = new Post();

        // when
        $comment->setPost($post);
        $comment->setPost(null);

        // then
        $this->assertNull($comment->getPost());
    }
}
