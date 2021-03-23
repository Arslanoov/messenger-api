<?php

declare(strict_types=1);

namespace Test\Functional\Api\Auth;

use DMS\PHPUnitExtensions\ArraySubset\ArraySubsetAsserts;
use Test\Functional\FunctionalTestCase;

class SignUpTest extends FunctionalTestCase
{
    use ArraySubsetAsserts;

    private const URI = '/auth/sign-up';
    private const TWO_TIMES_USERNAME = 'username';

    public function testSuccess(): void
    {
        $this->client->request('POST', self::URI, [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'username' => $username = self::TWO_TIMES_USERNAME,
            'password' => 'secret'
        ]));

        $response = $this->client->getResponse();

        $this->assertSame(201, $response->getStatusCode());
        $this->assertSame([
            'username' => $username
        ], json_decode($response->getContent(), true));
    }

    public function testValidationErrors(): void
    {
        $this->client->request('POST', self::URI, [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'username' => '',
            'password' => ''
        ]));

        $response = $this->client->getResponse();

        $this->assertSame(422, $response->getStatusCode());
        $this->assertArraySubset([
            'violations' => [
                ['propertyPath' => 'username', 'title' => 'This value should not be blank.'],
                ['propertyPath' => 'username', 'title' => 'This value is too short. It should have 4 characters or more.'],
                ['propertyPath' => 'password', 'title' => 'This value should not be blank.'],
                ['propertyPath' => 'password', 'title' => 'This value is too short. It should have 4 characters or more.']
            ],
        ], json_decode($response->getContent(), true));
    }

    public function testUsernameAlreadyExists(): void
    {
        $this->testSuccess();

        $this->client->request('POST', self::URI, [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'username' => self::TWO_TIMES_USERNAME,
            'password' => 'secret'
        ]));

        $response = $this->client->getResponse();

        $this->assertSame([
            'message' => 'User with this username already exists',
        ], json_decode($response->getContent(), true));
    }
}
