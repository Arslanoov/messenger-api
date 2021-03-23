<?php

declare(strict_types=1);

namespace Test\Functional\Api;

use Test\Functional\FunctionalTestCase;

class HomeTest extends FunctionalTestCase
{
    private const URI = '/';

    public function testSuccess(): void
    {
        $this->client->request('GET', self::URI);

        $response = $this->client->getResponse();

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame([
            'v' => '1.0',
            'name' => 'messenger'
        ], json_decode($response->getContent(), true));
    }
}
