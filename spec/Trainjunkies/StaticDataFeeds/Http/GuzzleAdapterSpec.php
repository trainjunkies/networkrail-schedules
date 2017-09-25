<?php

namespace spec\Trainjunkies\StaticDataFeeds\Http;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Cookie\CookieJar;
use Trainjunkies\StaticDataFeeds\Http\Adapter;
use Trainjunkies\StaticDataFeeds\Http\GuzzleAdapter;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Trainjunkies\StaticDataFeeds\NetworkRail\Authentication;

class GuzzleAdapterSpec extends ObjectBehavior
{
    const USERNAME = 'username';
    const PASSWORD = 'password';

    function it_can_create_guzzle_http_adapter(
        Authentication $authentication,
        HttpClient $httpClient,
        CookieJar $cookieJar
    )
    {
        $authentication->username()->willReturn(self::USERNAME);
        $authentication->password()->willReturn(self::PASSWORD);

        $this->beConstructedWith($authentication, $httpClient, $cookieJar);
        $this->shouldImplement(Adapter::class);
        $this->shouldHaveType(GuzzleAdapter::class);

        $this->get('', [], []);

        $options = [
            'base_uri' => 'https://datafeeds.networkrail.co.uk/ntrod/',
            'query' => [],
            'headers' => [],
            'auth' => [
                self::USERNAME,
                self::PASSWORD,
                'basic'
            ],
            'stream' => true,
            'cookie' => $cookieJar
        ];

        $httpClient->get('', $options)->shouldHaveBeenCalled();
    }
}
