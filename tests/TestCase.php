<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    public function from(string $url)
    {
        $this->app['session']->setPreviousUrl($url);
        return $this;
    }
}
