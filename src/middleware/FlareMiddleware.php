<?php

namespace studioespresso\flare\middleware;

use Craft;
use Spatie\FlareClient\Report;

class FlareMiddleware
{
    public function handle(Report $report, $next)
    {
        $context = $report->allContext();
        $context['request']['url'] = Craft::$app->getRequest()->getIsConsoleRequest() ? null : Craft::$app->getRequest()->getAbsoluteUrl();
        $report->userProvidedContext($context);
        return $next($report);
    }
}
