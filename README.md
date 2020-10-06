# Shared packages for laravel / lumen based applications


#Usage instruction 


To use this package which provides central point of communication scheme between Laravel / Lumen based applications, read following instructions and step by step guide : 


--- 
Manualy update primary repo's composer.json file by adding following private repository:

Example composer.json : https://gist.github.com/LShoniaTbcConnect/2c1a2a7125ab8d6e4df8040df230e388

``````
...
"repositories": [
        {
            "type": "git",
            "url":  "git@github.com:tbc360/lumen-shared-packages.git"
        }
    ],
...    

``````

and run this command in your terminal


``````
composer require tbcconnect/lumen-shared-packages 

``````
 
 Example usage:
 
 ``````
 <?php
 
 use Tbcconnect\MsConnector;
 
 
 class ExampleController extends Controller
 {
     /**
      * Create a new controller instance.
      *
      * @return void
      */
     public function __construct()
     {
         //
     }
 
 
     public function ExampleRequest()
     {
 
         # Http GET exampple
        
         MsConnector::httpRequest([
             'route'   => 'service/some-route',
             'app'     => 'ANALYTICS_API',  # DNS of analytics api;  should be presented in .env file
             'method'  => 'GET',
             'headers' => [
                 'params' => json_encode([
                     'some_key' => 'some_value',
                 ])
             ]
         ]);
         
         # Http POST / PUT / DELETE exampple
         
         MsConnector::httpRequest([
             'route'   => 'service/some-route',
             'app'     => 'ANALYTICS_API',  # DNS of analytics api;  should be presented in .env file
             'method'  => 'POST',
             'data'    => [
                'key' => 'value', #REQUIRED
             ],
             'headers' => [ # OPTIONAL 
                 'params' => json_encode([
                 'some_key' => 'some_value',
             ])]
         ]);               
     }
 }
``````
