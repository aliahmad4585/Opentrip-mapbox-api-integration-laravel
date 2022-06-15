<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LocationTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testSearch()
    {

        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->type("input", "veinna")
                ->pressAndWaitFor('button', 20)
                ->assertPathIs('/');
        });
    }

    // check that when user the press button map is visible or not
    public function testMapVisible()
    {

        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->type("input", "veinna")
                ->pressAndWaitFor('button', 20)
                ->assertVisible('#map')
                ->assertPathIs('/');
        });
    }
}
