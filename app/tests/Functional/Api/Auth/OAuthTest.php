<?php

declare(strict_types=1);

namespace Test\Functional\Api\Auth;

use DMS\PHPUnitExtensions\ArraySubset\ArraySubsetAsserts;
use Test\Functional\FunctionalTestCase;

class OAuthTest extends FunctionalTestCase
{
    use ArraySubsetAsserts;

    private const URI = '/token';

    public function testMethod(): void
    {
        $this->client->request('GET', self::URI);
        $this->assertSame(405, $this->client->getResponse()->getStatusCode());
    }

    public function testSuccess(): void
    {
        $this->client->request('POST', self::URI, [
            'grant_type' => 'password',
            'username' => 'oauth_user',
            'password' => 'password',
            'client_id' => 'oauth',
            'client_secret' => 'secret',
            'access_type' => 'offline'
        ]);

        $this->assertSame(200, $this->client->getResponse()->getStatusCode());

        $this->assertJson($content = $this->client->getResponse()->getContent());

        $data = json_decode($content, true);

        $this->assertArraySubset([
            'token_type' => 'Bearer'
        ], $data);

        $this->assertArrayHasKey('expires_in', $data);
        $this->assertNotEmpty($data['expires_in']);

        $this->assertArrayHasKey('access_token', $data);
        $this->assertNotEmpty($data['access_token']);

        $this->assertArrayHasKey('refresh_token', $data);
        $this->assertNotEmpty($data['refresh_token']);
    }

    public function testInvalid(): void
    {
        $this->client->request('POST', self::URI, [
            'grant_type' => 'password',
            'username' => 'oauth_user',
            'password' => 'not_valid',
            'client_id' => 'oauth',
            'client_secret' => 'secret',
            'access_type' => 'offline'
        ]);

        $this->assertSame(400, $this->client->getResponse()->getStatusCode());
    }
}
