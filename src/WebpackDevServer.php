<?php

namespace SilverStripe\WebpackDevServer;

use SilverStripe\Control\Director;
use SilverStripe\View\Requirements;

/**
 * Class WebpackDevServer
 * @package SilverStripe\Admin\Webpack
 */
class WebpackDevServer
{
    /**
     * @var
     */
    protected $port = 3000;

    protected $hostname = 'localhost';

    /**
     * @return bool
     */
    public function isActive()
    {
        return Director::isDev() && !!@fsockopen($this->hostname, $this->port, $errno, $errstr, 1);
    }

    /**
     * @param $path
     */
    public function loadCSS($path)
    {
        if ($this->isActive()) {
            Requirements::javascript($this->toDevServerPath($path));
        }

        Requirements::css($this->toPublicPath($path));
    }

    /**
     * @param $path
     */
    public function loadJavascript($path)
    {
        $path = $this->isActive() ?
            $this->toDevServerPath($path) :
            $this->toPublicPath($path);

        Requirements::javascript($path);
    }

    public function setPort($port)
    {
        $this->port = $port;

        return $this;
    }

    public function setHostname($hostname)
    {
        $this->hostname = $hostname;

        return $this;
    }

    protected function toDevServerPath($path)
    {
        return sprintf(
            'http://%s:%s/%s',
            $this->hostname,
            $this->port,
            $path
        );
    }

    protected function toPublicPath($path)
    {
        return $path;
    }
}