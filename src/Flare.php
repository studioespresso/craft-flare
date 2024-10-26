<?php

namespace studioespresso\flare;

use Craft;
use craft\base\Model;
use craft\base\Plugin;
use craft\console\Application as ConsoleApplication;
use craft\events\ExceptionEvent;
use craft\web\ErrorHandler;
use studioespresso\flare\models\Settings;
use studioespressp\flare\services\FlareService;
use yii\base\Event;

/**
 * Flare plugin
 *
 * @method static Flare getInstance()
 * @method Settings getSettings()
 * @method FlareService flare
 * @author Studio Espresso <support@studioespresso.co>
 * @copyright Studio Espresso
 * @license MIT
 */
class Flare extends Plugin
{
    public string $schemaVersion = '1.0.0';
    public bool $hasCpSettings = true;

    public static function config(): array
    {
        return [
            'components' => [
                'flare' => FlareService::class,
            ],
        ];
    }

    public function init(): void
    {
        parent::init();

        if (Craft::$app instanceof ConsoleApplication) {
            $this->controllerNamespace = 'studioespresso\flare\console\controllers';
        }

        $this->attachEventHandlers();

        // Any code that creates an element query or loads Twig should be deferred until
        // after Craft is fully initialized, to avoid conflicts with other plugins/modules
        Craft::$app->onInit(function() {
            if ($this->getSettings()->enabled) {
                Event::on(
                    ErrorHandler::className(),
                    ErrorHandler::EVENT_BEFORE_HANDLE_EXCEPTION,
                    function(ExceptionEvent $event) {
                        $this->flare->reportException($event->exception);
                    }
                );
            }
        });
    }

    protected function createSettingsModel(): ?Model
    {
        return Craft::createObject(Settings::class);
    }

    protected function settingsHtml(): ?string
    {
        return Craft::$app->view->renderTemplate('flare/_settings.twig', [
            'plugin' => $this,
            'settings' => $this->getSettings(),
        ]);
    }

    private function attachEventHandlers(): void
    {
        // Register event handlers here ...
        // (see https://craftcms.com/docs/4.x/extend/events.html to get started)
    }
}
