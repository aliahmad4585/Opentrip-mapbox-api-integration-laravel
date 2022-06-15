<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LocationApiValidationTest extends TestCase
{
    /**
     * A test to check the data is validation working
     *
     * @return void
     */
    public function testLocationApiValidateField()
    {
        $rules = [
            'searchTerm' => 'required|min:4|string',
            'offset' => 'required|integer'
        ];

        $data = [
            "searchTerm" => "vienna",
            'offset' => 1
        ];

        $v = $this->app['validator']->make($data, $rules);
        $this->assertTrue($v->passes());
    }

    /**
     * A test to check the Validation with invalid data
     *
     * @return void
     */

    public function testLocationApiInValidateDataField()
    {
        $rules = [
            'searchTerm' => 'required|min:4|string',
            'offset' => 'required|integer'
        ];

        $data = [
            "searchTerm" => "",
            'offset' => "1"
        ];

        $v = $this->app['validator']->make($data, $rules);
        $this->assertFalse($v->passes());
    }
}
