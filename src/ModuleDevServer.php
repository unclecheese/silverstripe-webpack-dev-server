<?php

namespace SilverStripe\WebpackDevServer;

use SilverStripe\Core\Manifest\ModuleLoader;
use Exception;

class ModuleDevServer extends WebpackDevServer
{

    /**
     * @var ModulePackageJSONLoader
     */
    protected $config;

    /**
     * @var \SilverStripe\Core\Manifest\Module
     */
    protected $module;

    protected $configFile = 'package.json';

    public function __construct($moduleName)
    {
        $this->module = ModuleLoader::getModule($moduleName);

        if (!$this->module) {
            throw new Exception(sprintf(
                "Invalid module passed to %s factory: %s",
                __CLASS__,
                $moduleName
            ));
        }
        $config = $this->loadModuleConfig();
        if (isset($config['hostname'])) {
            $this->hostname = $config['hostname'];
        }
        if (isset($config['port'])) {
            $this->port = $config['port'];
        }
    }

    public function setConfigFile($file)
    {
        $this->configFile = $file;
    }

    protected function toPublicPath($path)
    {
        return $this->module->getRelativeResourcePath($path);
    }

    protected function loadModuleConfig()
    {
        if (!$this->module->hasResource($this->configFile)) {
            throw new Exception("
                Cannot run Webpack on module {$this->module->getName()} with 
                no package.json in the module root.
            ");
        }

        $packageJSON = @file_get_contents(
            $this->module->getResourcePath($this->configFile)
        );

        $parsed = json_decode($packageJSON, true);

        if (!$parsed) {
            throw new Exception(sprintf(
                'Invalid file %s in %s',
                $this->configFile,
                $this->module->getName()
            ));
        }

        if (!isset($parsed['webpack-dev-server'])) {
            throw new Exception(sprintf(
                'No webpack-dev-server node found in %s file in %s',
                $this->configFile,
                $this->module->getName()
            ));
        }

        return $parsed['webpack-dev-server'];
    }

}