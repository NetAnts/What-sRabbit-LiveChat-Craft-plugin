<?php

namespace NetAnts\WhatsRabbitLiveChat\Service;

use Craft;
use craft\base\PluginInterface;
use NetAnts\WhatsRabbitLiveChat\db\Settings;
use NetAnts\WhatsRabbitLiveChat\Plugin;
use NetAnts\WhatsRabbitLiveChat\ValueObject\LiveChatConfig;

class SettingsService
{
    public string $pluginRepoUrl;

    public function __construct(
        private Craft $craft,
    ) {
        $this->pluginRepoUrl = getenv('PLUGIN_REPO_HOST') ?: Plugin::PLUGIN_REPO_PROD_URL;
    }

    public function saveSettings(LiveChatConfig $liveChatConfig): bool
    {
        $settings = Settings::findOne(1);
        if (empty($settings)) {
            $settings = new Settings();
            $settings->id = 1;
        }

        $settings->title = $liveChatConfig->title;
        $settings->description = $liveChatConfig->description;
        $settings->avatar_asset_id = $liveChatConfig->avatarAssetId;
        $settings->whatsapp_url = $liveChatConfig->whatsAppUrl;
        $settings->enabled = $liveChatConfig->enabled;
        return  $settings->save();
    }

    public function getSettings(): ?LiveChatConfig
    {
        $settings = Settings::findOne(1);
        if (!$settings) {
            return null;
        }
        return LiveChatConfig::createFromDatabase($settings);
    }
}
