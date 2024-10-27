<?php

namespace studioespressp\flare\services;

use Craft;
use craft\base\Component;
use Spatie\FlareClient\Flare as FlareClient;
use studioespresso\flare\Flare;

class FlareService extends Component
{
    public ?FlareClient $client = null;

    public function init(): void
    {
        if (
            Flare::getInstance()->getSettings()->apiKey &&
            Flare::getInstance()->getSettings()->enabled
        ) {
            $this->client = FlareClient::make(Flare::getInstance()->getSettings()->apiKey)->registerFlareHandlers();
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
            $this->client->context('CMS', 'Craft CMS');
            $this->client->context('System name', Craft::$app->getSystemName());
            $this->client->context('Craft version', Craft::$app->getInfo()->version);
            $this->client->context('Yii version', Craft::$app->getYiiVersion());
            $this->client->report($exception);
        }
    }
}
