# WebpackDevServer for SilverStripe

This package allows you to make any injectable class aware of your WebpackDevServer configuration
 and provides an API for loading requirements based on the dev server state and context.
 
 ## Requirements
 
 SilverStripe 4.0
 
 ## Usage
 
 First, create a service for your module
 
 __my-module/_config/config.yml__
 ```yaml
 ---
 Name: my-module-webapck
 ---
 SilverStripe\Core\Injector\Injector:
   SilverStripe\WebpackDevServer\ModuleDevServer.my-module:
     class: SilverStripe\WebpackDevServer\ModuleDevServer
     constructor:
       0: 'my-vendor/mymodule' # e.g. composer name
 My\Project\Controllers\MyController:
   properties:
     Webpack: %$SilverStripe\WebpackDevServer\ModuleDevServer.my-module
 ```
 
 Next, confirgure webpack in your module's `package.json`.
 
 __my-module/package.json__
 ```js
 {
   ...
  "webpack-dev-server": {
    "port": 3000
   }
 }
 ```
 
 Lastly, pull the requirements into your controller (or whatever other class needs the assets)
 
 __my-module/src/Controllers/MyController.php__
 ```
 protected function init()
 {
   parent::init();
   // Load from dev server when running, or relative to module dir when not.
   $this->Webpack->loadJavascript('dist/file.js');
 }
 ```
 
 ## Also
 
 This probably doesn't work.