<?php

namespace Palmans\Tracker\Tests;

use Palmans\Tracker\Tracker;
use Prophecy\PhpUnit\ProphecyTrait;

class TrackerTest extends TestBase
{
    use ProphecyTrait;

    /**
     * Setup test
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->app['router']->get('/home', function () {
            return '';
        });
        $this->app['router']->get('/test', function () {
            return '';
        });

        $this->app['session']->put('tracker.views', []);
    }

    /**
     * Returns the instance
     *
     * @return Tracker
     */
    protected function getTracker()
    {
        return $this->app->make('Palmans\Tracker\Tracker');
    }

    /** @test */
    public function it_returns_the_model_name()
    {
        $tracker = $this->getTracker();

        $this->assertEquals(
            'Palmans\Tracker\SiteView',
            $tracker->getViewModelName()
        );
    }

    /** @test */
    public function it_makes_a_new_site_view_model()
    {
        $tracker = $this->getTracker();

        $this->assertInstanceOf(
            'Palmans\Tracker\SiteView',
            $tracker->makeNewViewModel()
        );
    }

    /** @test */
    public function it_returns_the_current_view()
    {
        $tracker = $this->getTracker();

        $this->assertInstanceOf(
            'Palmans\Tracker\SiteView',
            $tracker->getCurrent()
        );
    }

    /** @test */
    public function it_checks_if_the_current_view_is_unique()
    {
        $tracker = $this->getTracker();

        $this->get('/home');

        $this->assertTrue(
            $tracker->isViewUniqueForSession()
        );

        $tracker->saveCurrent();

        $this->assertFalse(
            $tracker->isViewUniqueForSession()
        );
    }

    /** @test */
    public function it_checks_if_current_view_is_valid()
    {
        $tracker = $this->getTracker();

        // The user agent for test is 'Symfony/3.X'
        $this->assertTrue(
            $tracker->isViewValid()
        );

        // Let's include it to the bot_filter and retry
        $this->app->config->set('tracker.bot_filter', ['symfony']);

        $this->assertFalse(
            $tracker->isViewValid()
        );
    }

    /** @test */
    public function it_saves_the_current_view()
    {
        $tracker = $this->getTracker();

        $this->assertTrue(
            $tracker->saveCurrent()
        );
    }

    /** @test */
    public function it_saves_only_if_unique()
    {
        $tracker = $this->getTracker();

        $this->assertTrue(
            $tracker->saveCurrent()
        );

        $this->assertFalse(
            $tracker->saveCurrent()
        );
    }

    /** @test */
    public function it_adds_and_saves_trackables()
    {
        $tracker = $this->getTracker();

        $view = $tracker->getCurrent();

        $trackable = $this->prophesize('Palmans\Tracker\TrackableInterface');
        $trackable->attachTrackerView($view)
            ->willReturn(null)
            ->shouldBeCalled();

        $tracker->addTrackable($trackable->reveal());

        $tracker->saveCurrent();
    }

    /** @test */
    public function it_pauses_and_resumes_recording()
    {
        $tracker = $this->getTracker();

        $this->assertTrue(
            $tracker->saveEnabled()
        );

        $tracker->pauseRecording();

        $this->assertFalse(
            $tracker->saveEnabled()
        );

        $tracker->resumeRecording();

        $this->assertTrue(
            $tracker->saveEnabled()
        );
    }
}
