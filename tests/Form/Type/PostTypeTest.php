<?php

/**
 * Post type tests.
 */

namespace App\Tests\Form\Type;

use App\Form\Type\PostType;
use PHPUnit\Framework\TestCase;

/**
 * Class PostTypeTest.
 */
class PostTypeTest extends TestCase
{
    /**
     * Test block prefix.
     */
    public function testGetBlockPrefix(): void
    {
        // given
        $type = new PostType();

        // then
        $this->assertSame('post', $type->getBlockPrefix());
    }
}
