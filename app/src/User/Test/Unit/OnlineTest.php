<?php

declare(strict_types=1);

namespace User\Test\Unit;

use DateInterval;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use User\Test\Builder\UserBuilder;

/**
 * Class OnlineTest
 * @package Domain\Model\User\Test\Unit
 * @covers \User\Model\User
 */
class OnlineTest extends TestCase
{
    public function testSuccess(): void
    {
        $user = (new UserBuilder())->build();

        $user->addAction($latestActivity = new DateTimeImmutable("03.11.2021 12:00"));

        $this->assertEquals($latestActivity, $user->getLatestActivity());

        $this->assertTrue(
            $user->isOnline(
                $latestActivity->add(new DateInterval("PT1H")),
                new DateInterval("PT2H")
            )
        );

        $this->assertFalse(
            $user->isOnline(
                $latestActivity->add(new DateInterval("PT3H")),
                new DateInterval("PT1H")
            )
        );
    }
}
