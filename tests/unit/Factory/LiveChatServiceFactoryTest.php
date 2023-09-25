<?php

declare(strict_types=1);

namespace NetAnts\WhatsRabbitLiveChatTest\Factory;

use craft\helpers\App;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use NetAnts\WhatsRabbitLiveChat\Factory\LiveChatServiceFactory;
use NetAnts\WhatsRabbitLiveChat\Service\SettingsService;
use PHPUnit\Framework\TestCase;
use Whatsrabbit\LiveChatPluginCore\LiveChatService;

class LiveChatServiceFactoryTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    private LiveChatServiceFactory $factory;

    protected function setUp(): void
    {
        $this->factory = new LiveChatServiceFactory();
    }

    public function testInvokeCreatesLiveChatService(): void
    {
        $factory = new LiveChatServiceFactory();
        $settingsService = Mockery::mock(SettingsService::class);
        $settingsService->pluginRepoUrl = 'bla';
        $service = $factory(LiveChatService::class, $settingsService);
        $this->assertInstanceOf(LiveChatService::class, $service);
    }
}
