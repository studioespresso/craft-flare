<?php

namespace studioespresso\flare\models;

use craft\base\Model;
use craft\behaviors\EnvAttributeParserBehavior;

/**
 * Flare settings
 */
class Settings extends Model
{
    /**
     * @var bool Should we enable logs to be sent to flareapp.io
     */
    public bool $enabled = true;

    /**
     * @var string the API key of flareapp
     */
    public ?string $apiKey = null;

    /**
     * @var bool should ip-addresses by anonymized
     */
    public bool $anonymizeIp = true;

    /**
     * @var array Array with exceptions that should not be logged into flareapp
     */
    public array $excludedExceptions = [];

    /**
     * @inheritdoc
     */
    protected function defineBehaviors(): array
    {
        return [
            'parser' => [
                'class' => EnvAttributeParserBehavior::class,
                'attributes' => ['apiKey'],
            ],
        ];
    }
}
