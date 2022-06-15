<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LocationApiTest extends TestCase
{
    /**
     * Check Api with empty form data
     * validation
     */
    public function testGetLocationApiWithoutQueryString()
    {
        print sprintf("Test without query string");
        $response = $this->call('GET', '/locations');
        $this->assertEquals(302, $response->status());
    }

    public function testGetLocationApiWithQueryString()
    {
        print sprintf("Test with query string");
        $response = $this->call('GET', '/locations?searchTerm=+vienna&offset=0&_token=1I7cm2hIgQVl6XQiVVtBYgicEUwjFbQTq7iJAM5e');
        $this->assertEquals(200, $response->status());
    }
    

    public function testNumberOfRecordMatchWithEnv()
    {
        $limit = config('opentrip.parameters.limit');
        print sprintf("Test the return records match with limit");
        $response = $this->call('GET', '/locations?searchTerm=+vienna&offset=0&_token=1I7cm2hIgQVl6XQiVVtBYgicEUwjFbQTq7iJAM5e');
        $records =  json_decode($response->getContent(), true);
        $nearbyLocation =  count($records['nearByLocations']);
        $this->assertEquals($limit, $nearbyLocation);
    }

    public function testResonseJson()
    {
        $limit = config('opentrip.parameters.limit');
        print sprintf("Check the Json");
        $response = $this->call('GET', '/locations?searchTerm=+vienna&offset=0&_token=1I7cm2hIgQVl6XQiVVtBYgicEUwjFbQTq7iJAM5e');
        $records =  json_decode($response->getContent(), true);
        $this->assertArrayHasKey('nearByLocations', $records);
        $this->assertArrayHasKey('searchedLocation', $records);
        $this->assertArrayHasKey('total', $records);
    }
}
