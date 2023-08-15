<?php

declare(strict_types=1);

namespace NetAnts\WhatsRabbitLiveChatTest\Controller;

use Craft;
use craft\test\TestSetup;
use craft\web\Request;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use NetAnts\WhatsRabbitLiveChat\Controller\DisplaySettingsController;
use NetAnts\WhatsRabbitLiveChat\Model\DisplaySettings;
use NetAnts\WhatsRabbitLiveChat\Service\SettingsService;
use NetAnts\WhatsRabbitLiveChat\ValueObject\LiveChatConfig;
use PHPUnit\Framework\TestCase;
use yii\base\Module;
use yii\web\Response;

class DisplaySettingsControllerTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    private SettingsService|MockInterface $settingsService;
    private Craft | MockInterface $craft;
    private DisplaySettingsController $controller;

    protected function setUp(): void
    {
        $id = 'displaySettingsController';
        $module = Mockery::mock(Module::class);
        $config = [];
        $this->craft = Mockery::mock(Craft::class);
        $this->settingsService = Mockery::mock(SettingsService::class);
        $this->settingsService->expects('getSettings')->andReturn(LiveChatConfig::createFromRequest([
            'avatarAssetId' => 'some-asset-id',
            'description' => 'some-description',
            'title' => 'some-title',
            'whatsAppUrl' => 'some-url',
            'enabled' => true,
        ]));
        $this->controller = new DisplaySettingsController($id, $module, $this->settingsService, $this->craft, $config);
    }


    public function testSavingAction(): void
    {
        $request = Mockery::mock(Request::class);
        $request->expects('getBodyParams')->andReturn([
            'apiKey' => 'some-api-key',
            'apiSecret' => 'some-api-secret',
            'title' => 'Some title',
            'description' => 'Some description',
            'avatarAssetId' => ['some-avatar-id'],
            'whatsAppUrl' => 'https://wa.me',
            'enabled' => true,
        ]);
        $request->expects('getValidatedBodyParam')->andReturn(null);
        $request->expects('getPathInfo')->andReturn('/api');
        $this->settingsService->expects('saveSettings')->withAnyArgs()->andReturn(true);
        $this->controller->request = $request;
        $response = $this->controller->actionSave();
        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(302, $response->getStatusCode());
        $this->assertTrue($response->getIsRedirection());
    }

    public function testActionSaveLiveChatConfigInvalid(): void
    {
        $request = Mockery::mock(Request::class);
        $request->expects('getBodyParams')->andReturn([
            'title' => 'Some title',
            'description' => 'Some description',
            'avatarAssetId' => ['some-avatar-id'],
            'whatsAppUrl' => 'https://wa.me',
        ]);
        $request->expects('getValidatedBodyParam')->andReturn(null);
        $request->expects('getPathInfo')->andReturn('/api');
        $this->controller->request = $request;
        $response = $this->controller->actionSave();
        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(302, $response->getStatusCode());
        $this->assertSame(
            'Something went wrong while creating configCould not create LiveChatConfig because the following data is missing "enabled"',
            $this->craft::$app->session->getError()
        );
    }

    public function testActionSaveFails(): void
    {
        $request = Mockery::mock(Request::class);
        $request->expects('getBodyParams')->andReturn([
            'apiKey' => 'some-api-key',
            'apiSecret' => 'some-api-secret',
            'title' => 'Some title',
            'description' => 'Some description',
            'avatarAssetId' => ['some-avatar-id'],
            'whatsAppUrl' => 'https://wa.me',
            'enabled' => true,
        ]);
        $request->expects('getValidatedBodyParam')->andReturn(null);
        $request->expects('getPathInfo')->andReturn('/api');
        $this->settingsService->expects('saveSettings')->withAnyArgs()->andReturn(false);
        $this->controller->request = $request;
        $response = $this->controller->actionSave();
        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(302, $response->getStatusCode());
        $this->assertSame(
            'Something went wrong while saving the plugin settings',
            $this->craft::$app->session->getError()
        );
    }

    public function testActionSaveButLiveChatConfigCannotBeCreated(): void
    {
        $request = Mockery::mock(Request::class);
        $request->expects('getBodyParams')->andReturn([
            'apiKey' => 'some-api-key',
            'apiSecret' => 'some-api-secret',
            'title' => 'Some title',
            'description' => 'Some description',
            'avatarAssetId' => ['some-avatar-id'],
            'enabled' => true,
        ]);
        $request->expects('getValidatedBodyParam')->andReturn(null);
        $request->expects('getAcceptsJson')->andReturnFalse();
        $this->controller->request = $request;
        $response = $this->controller->actionSave();
        $this->assertNull($response);
        $this->assertSame(
            'Something went wrong!',
            $this->craft::$app->session->getError()
        );
    }

    public function testActionEdit(): void
    {
        $response = $this->controller->actionEdit();
        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(200, $response->getStatusCode());
    }

    public function testActionEditWithSettingsFromRoute(): void
    {
        $displaySettings = new DisplaySettings([
            'title' => 'Some title',
            'description' => 'Some description',
            'avatarAssetId' => 0,
            'whatsAppUrl' => 'https://wa.me',
            'enabled' => false,
            ]);
        $response = $this->controller->actionEdit($displaySettings);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(200, $response->getStatusCode());
    }
}
