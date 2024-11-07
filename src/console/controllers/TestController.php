<?php

namespace studioespresso\flare\console\controllers;

use craft\console\Controller;
use craft\helpers\App;
use craft\helpers\Console;
use studioespresso\flare\Flare;
use yii\base\Exception;

/**
 * Testing your Flare integration
 */
class TestController extends Controller
{
    /**
     * Once you have the Flare plugin installed & configured, use this function to throw an simulated exception to test if it shows on in Flare.
     */
    public function actionIndex(): void
    {
        if (!Flare::getInstance()->getSettings()->enabled) {
            Console::stdout("Flare is not enabled on this environment." . PHP_EOL . "Check your settings or run this command where Flare is enabled." . PHP_EOL, Console::FG_YELLOW);
            return;
        }
        if (!App::parseEnv(Flare::getInstance()->getSettings()->apiKey)) {
            Console::stdout("Flare API key missing." . PHP_EOL . "Check your settings or run this command where Flare is correctly configured" . PHP_EOL, Console::FG_RED);
            return;
        }
        try {
            throw new Exception("This is an exception throws while testing the Flare plugin for Craft CMS", 418);
        } catch (\Throwable $e) {
            Flare::getInstance()->flare->reportException($e);
            Console::stdout("Exception reported to flareapp.io, check your project there to see if it shows up" . PHP_EOL);
        }
    }
}
