<?php

declare(strict_types=1);

namespace NetAnts\WhatsRabbitLiveChat\Factory;

use GuzzleHttp\Client;
use NetAnts\WhatsRabbitLiveChat\Plugin;
use NetAnts\WhatsRabbitLiveChat\Service\SettingsService;
use Whatsrabbit\LiveChatPluginCore\LiveChatService;

class LiveChatServiceFactory
{
    public function __invoke(string $requestedName, SettingsService $settingsService): LiveChatService
    {
        $settings = Plugin::getInstance()->getSettings();

        $client = new Client(['http_errors' => false]);

        return new LiveChatService(
            $settings['apiKey'],
            $settings['apiSecret'],
            $client,
            $settingsService->pluginRepoUrl
        );
    }
}
