<?php

declare(strict_types=1);

namespace NetAnts\WhatsRabbitLiveChatTest\Controller;

use craft\web\Request;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use NetAnts\WhatsRabbitLiveChat\Controller\LoginController;
use NetAnts\WhatsRabbitLiveChat\Factory\LiveChatServiceFactory;
use NetAnts\WhatsRabbitLiveChat\Service\SettingsService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Whatsrabbit\LiveChatPluginCore\LiveChatService;
use Whatsrabbit\LiveChatPluginCore\ValueObject\AuthenticationResponse;
use yii\base\Module;
use yii\web\Response;

class LoginControllerTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    private SettingsService|MockInterface $settingsService;
    private LiveChatServiceFactory|MockObject $liveChatServiceFactory;
    private LiveChatService|MockInterface $liveChatService;
    private LoginController $loginController;

    protected function setUp(): void
    {
        $id = 'settingsController';
        $module = Mockery::mock(Module::class);
        $config = [];
        $this->settingsService = Mockery::mock(SettingsService::class);
        $this->settingsService->pluginRepoUrl = 'bla';
        $this->liveChatServiceFactory = $this->createPartialMock(LiveChatServiceFactory::class, ['__invoke']);
        $this->liveChatService = Mockery::mock(LiveChatService::class);
        $this->liveChatServiceFactory->expects($this->once())->method('__invoke')->withAnyParameters()->willReturn($this->liveChatService);
        $this->loginController = new LoginController($id, $module, $this->settingsService, $this->liveChatServiceFactory, $config);
    }

    public function testCanCreate(): void
    {
        $this->assertInstanceOf(LoginController::class, $this->loginController);
    }

    public function testActionGetToken(): void
    {
        // Given
        $request = Mockery::mock(Request::class);
        $this->loginController->request = $request;
        $authenticationResponse = AuthenticationResponse::createFromArray([
            'externalId' => 'some-external-id',
            'token' => 'some-token',
            'refreshToken' => 'some-refresh-token',
            'refreshTokenExpiresAt' => (new \DateTimeImmutable())->format(DATE_ATOM),
        ]);
        $this->liveChatService->expects('fetchToken')->andReturn($authenticationResponse);

        // When
        $response = $this->loginController->actionGetToken();

        // Then
        $this->assertEquals(json_encode($authenticationResponse), json_encode($response->data));
    }
}
