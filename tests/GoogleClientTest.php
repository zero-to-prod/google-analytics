<?php

namespace ZeroToProd\GoogleClient\Tests;

use Google_Client;
use Google_Service_Analytics;
use RuntimeException;
use ZeroToProd\GoogleClient\GoogleClient;

class GoogleClientTest extends TestCase
{
    /**
     * @test
     * @see GoogleClient::__construct()
     * @see GoogleClient::client
     */
    public function invalid_key(): void
    {
        $this->expectException(RuntimeException::class);

        $this->assertNull((new GoogleClient())->client);
    }

    /**
     * @test
     * @throws \Exception
     * @see GoogleClient::__construct()
     * @see GoogleClient::client
     */
    public function gets_client_after_caching_key(): void
    {
        $this->assertNotNull((new GoogleClient($this->key))->client);

        $this->assertNotNull((new GoogleClient())->client);
    }

    /**
     * @test
     * @throws \Exception
     * @see GoogleClient::__construct()
     * @see GoogleClient::client
     */
    public function gets_analytics_class(): void
    {
        $this->assertNotNull((new GoogleClient($this->key))->client);
    }

    /**
     * @test
     * @throws \Exception
     * @see GoogleClient::__construct()
     * @see GoogleClient::key
     */
    public function gets_key(): void
    {
        $this->assertEquals($this->key, (new GoogleClient($this->key))->key);
    }

    /**
     * @test
     * @throws \Exception
     * @see GoogleClient::__construct()
     * @see GoogleClient::client
     */
    public function use_client()
    {
        $this->storeKeyForTest();

        $client = new Google_Service_Analytics(new Google_Client());

        $this->assertEquals($client, (new GoogleClient(client: $client))->client);
    }

    /**
     * @test
     * @see GoogleClient::keyIsValid()
     */
    public function key_is_valid(): void
    {
        $this->assertTrue(GoogleClient::keyIsValid($this->key));
    }

    /**
     * @test
     * @see GoogleClient::keyIsInValid()
     */
    public function key_is_invalid(): void
    {
        $this->assertTrue(GoogleClient::keyIsInValid(''));
    }

    /**
     * @test
     * @throws \Exception
     * @see GoogleClient::accountSummariesList()
     */
    public function account_summaries_list(): void
    {
        dd((new GoogleClient($this->key))->accountSummariesList());
    }
}
