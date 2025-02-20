<?php

namespace studioespresso\flare\services;

use Craft;
use craft\base\Component;
use craft\helpers\App;
use Spatie\FlareClient\Flare as FlareClient;
use studioespresso\flare\Flare;
use studioespresso\flare\middleware\FlareMiddleware;
use studioespresso\flare\models\Settings as SettingsModel;

/**
 * Class FlareService
 * @package studioespresso\flare\services\FlareService
 *
 * @property-read SettingsModel $settings
 */
class FlareService extends Component
{
    /**
     * @var FlareClient|null
     */
    public ?FlareClient $client = null;

    /**
     * @var SettingsModel|null
     */
    private ?SettingsModel $settings = null;

    public function init(): void
    {
        $this->settings = Flare::getInstance()->getSettings();

        if (
            Flare::getInstance()->getSettings()->apiKey &&
            Flare::getInstance()->getSettings()->enabled
        ) {
            $this->client = FlareClient::make(App::parseEnv($this->settings->apiKey))->registerFlareHandlers();
            $this->client->registerMiddleware(FlareMiddleware::class);
        }

        parent::init();
    }

    public function reportException(\Throwable $exception)
    {
        $settings = Flare::getInstance()->getSettings();
        if (in_array(get_class($exception), $settings->excludedExceptions)) {
            Craft::info('Exception class excluded from being reported to Flare.', Flare::getInstance()->handle);
            return;
        }

        if ($this->client) {
            if ($settings->anonymizeIp) {
                $this->client->anonymizeIp();
            }

            $this->client->context('CMS', 'Craft CMS');
            $this->client->context('System name', Craft::$app->getSystemName());
            $this->client->context('Craft version', Craft::$app->getInfo()->version);
            $this->client->context('Yii version', Craft::$app->getYiiVersion());
            $this->client->report($exception);
        }
    }
}
