<?php

declare(strict_types=1);

namespace Messenger\Test\Unit\Dialog;

use Messenger\Model\Author\Author;
use Messenger\Model\Dialog\Dialog;
use PHPUnit\Framework\TestCase;

/**
 * Class CreateTest
 * @package Messenger\Test\Unit\Author
 * @covers \Messenger\Model\Dialog\Dialog
 */
class CreateTest extends TestCase
{
    public function testNew(): void
    {
        $dialog = Dialog::new(
            $firstAuthor = Author::empty(),
            $secondAuthor = Author::empty()
        );

        $this->assertNotEmpty($dialog->getUuid());
        $this->assertEquals($firstAuthor, $dialog->getFirstAuthor());
        $this->assertEquals($secondAuthor, $dialog->getSecondAuthor());
        $this->assertEquals(0, $dialog->getMessagesCount());
        $this->assertEquals(0, $dialog->getNotReadMessagesCount());
    }
}
