# Flare

Flareapp.io integration for Craft CMS

## Requirements

This plugin requires Craft CMS 4.12.0 or later, and PHP 8.1 or later - <br>and an account at [flareapp.io](https://flareapp.io/?via=studioespresso) *(Affiliate link, you're supporting further maintenance of this plugin by signing up through that link - thanks!)*

## Installation

You can install this plugin from the Plugin Store or with Composer.

#### From the Plugin Store

Go to the Plugin Store in your project’s Control Panel and search for “Flare”. Then press “Install”.

#### With Composer

Open your terminal and run the following commands:

```shell
cd /path/to/my-project.test
(ddev/php) composer require studioespresso/craft-flare
(ddev/php) craft plugin/install flare
```

## Configuration
Create a project in your Flare account, copy the API key and add it in the plugin settings.

Alternatively, you can use the ``flare.php`` configuration file to manage this. This file is environment-aware, like any other Craft CMS configuration. You can find an example below.

````php
<?php

return [
    '*' => [
        'enabled' => false,
        'apiKey' => \craft\helpers\App::env('FLARE_API_KEY'),
        'excludedExceptions' => [
            ErrorException::class,
        ]
    ],
    'production' => [
        'enabled' => true,
    ]
];
````

## Bootstrap (optional)
In order to load the plugin as soon as possible (to make sure you’re tracking the most errors), you can add the following snippet to your `app.php

````php
return [
    // If you’re app.php contains multiple environments, make sure to add this to the ‘*’ one.
    'bootstrap' => [
        'studioespresso\flare\Bootstrap'
    ]
]
````

## Testing
Once you've added the API key for your Flare project, you can use a built-in console command to send a test exception to Flare:

````shell
(ddev/php) craft flare/test/index
# That should result on the following message:
Exception reported to flareapp.io, check your project there to see if it shows up
````
