<?php

namespace born05\sentry\services;

use born05\sentry\Plugin as SentryPlugin;

use Craft;
use craft\base\Component;

use Sentry;

class SentryService extends Component
{
    public function handleException($exception)
    {
        $plugin = SentryPlugin::$plugin;
        $settings = $plugin->getSettings();

        $statusCode = $exception->statusCode ?? null;

        if (in_array($statusCode, $settings->excludedCodes)) {
            Craft::info('Exception status code excluded from being reported to Sentry.', $plugin->handle);
            return;
        }

        Sentry\configureScope(function (Sentry\State\Scope $scope) use ($statusCode) {
            $scope->setExtra('Status Code', $statusCode);
        });

        Sentry\captureException($exception);
    }
}