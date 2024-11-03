<?php

namespace studioespresso\flare\models;

use craft\base\Model;

/**
 * Flare settings
 */
class Settings extends Model
{
    public bool $enabled = true;

    public ?string $apiKey = null;

    public bool $anonymizeIp = true;

    public array $excludedExceptions = [];
}
