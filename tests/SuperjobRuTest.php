<?php
declare(strict_types=1);

namespace Holdmann\OAuth2\Client\Provider\Tests;

use Holdmann\OAuth2\Client\Provider\{
    SuperjobRu
};
use PHPUnit\Framework\TestCase;

class SuperjobRuTest extends TestCase
{
    use CanAccessTokenStub;

    public function testBaseAuthorizationUrl()
    {
        // Execute
        $url = $this->provider()
            ->getBaseAuthorizationUrl();

        // Verify
        self::assertSame('/authorize', path($url));
    }

    public function testBaseAccessTokenUrl()
    {
        static $params = [];

        // Execute
        $url = $this->provider()
            ->getBaseAccessTokenUrl($params);

        // Verify
        self::assertSame('/2.0/oauth2/access_token', path($url));
    }

    public function testResourceOwnerDetailsUrl()
    {
        $tokenParams = [
            'access_token'  => 'mock_access_token',
        ];

        $url = $this->provider()
            ->getResourceOwnerDetailsUrl($this->accessToken($tokenParams));

        // Verify
        self::assertSame('/2.0/user/current/', path($url));
    }

    public function testDefaultScopes()
    {
        $getDefaultScopes = function () {
            return $this->getDefaultScopes();
        };

        // Execute
        $defaultScopes = $getDefaultScopes->call($this->provider());

        // Verify
        self::assertSame([], $defaultScopes);
    }

    private function provider(...$args): SuperjobRu
    {
        static $default = [
            'clientId'     => 'mock_client_id',
            'clientSecret' => 'mock_secret',
            'redirectUri'  => 'mock_redirect_uri',
        ];

        $values = array_replace($default, ...$args);

        return new SuperjobRu($values);
    }
}

function path(string $url): string
{
    return parse_url($url, PHP_URL_PATH);
}
