<?php

namespace NetAnts\WhatsRabbitLiveChatTest\ValueObject;

use NetAnts\WhatsRabbitLiveChat\db\Settings;
use NetAnts\WhatsRabbitLiveChat\Exception\InvalidDataException;
use NetAnts\WhatsRabbitLiveChat\Service\SettingsService;
use NetAnts\WhatsRabbitLiveChat\ValueObject\LiveChatConfig;

class LiveChatConfigTest extends \Codeception\PHPUnit\TestCase
{
    public function testCreateFromRequestWithMissingData()
    {
        $this->expectException(InvalidDataException::class);
        $this->expectExceptionMessage('Could not create LiveChatConfig because the following data is missing "title"');
        LiveChatConfig::createFromRequest([
            'description' => 'Some description',
            'avatarAssetId' => ['some-avatar-id'],
            'enabled' => true,
            'whatsAppUrl' => 'https://wa.me',
        ]);
    }

    public function testCreateFromRequest()
    {
        $config = LiveChatConfig::createFromRequest([
            'title' => 'Some title',
            'description' => 'Some description',
            'avatarAssetId' => [42],
            'enabled' => true,
            'whatsAppUrl' => 'https://wa.me',
        ]);

        $this->assertInstanceOf(LiveChatConfig::class, $config);
    }

    public function testCreateFromDatabase()
    {
        $settings = new Settings([
            'title' => 'Some title',
            'description' => 'Some description',
            'avatar_asset_id' => 42,
            'enabled' => true,
            'whatsapp_url' => 'https://wa.me',
        ]);
        $config = LiveChatConfig::createFromDatabase($settings);

        $this->assertInstanceOf(LiveChatConfig::class, $config);
    }
}
