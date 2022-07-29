<?php

if (! function_exists('tracker')) {

    /**
     * Shorthand for the tracker instance
     *
     * @return Palmans\Tracker\Tracker
     */
    function tracker()
    {
        return app()->make('tracker');
    }
}
