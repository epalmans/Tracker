<?php

namespace Arrtrust\Tracker\Tests;

class TrackerServiceProviderTest extends TestBase
{
    /** @test */
    public function it_registers_the_tracker_service()
    {
        $this->assertInstanceOf(
            'Arrtrust\Tracker\Tracker',
            $this->app['tracker']
        );
    }

    /** @test */
    public function it_allows_access_via_helper()
    {
        $this->assertInstanceOf(
            'Arrtrust\Tracker\Tracker',
            app('tracker')
        );
    }
}
