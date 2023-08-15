<?php

declare(strict_types=1);

namespace NetAnts\WhatsRabbitLiveChat\Controller;

use Craft;
use craft\web\Controller;
use craft\web\View;
use NetAnts\WhatsRabbitLiveChat\Exception\InvalidDataException;
use NetAnts\WhatsRabbitLiveChat\Model\DisplaySettings;
use NetAnts\WhatsRabbitLiveChat\Plugin;
use NetAnts\WhatsRabbitLiveChat\Service\SettingsService;
use NetAnts\WhatsRabbitLiveChat\ValueObject\LiveChatConfig;
use yii\base\InvalidConfigException;
use yii\web\BadRequestHttpException;
use yii\base\Module;
use yii\web\Response;

class DisplaySettingsController extends Controller
{
    private DisplaySettings $displaySettings;

    public function __construct(
        string $id,
        Module $module,
        private SettingsService $settingsService,
        private Craft $craft,
        array $config = [],
    ) {
        parent::__construct($id, $module, $config);
        $this->getDisplaySettings();
    }

    public function actionEdit(?DisplaySettings $displaySettings = null)
    {
        if (!$displaySettings) {
            $displaySettings =  $this->displaySettings;
        }
        return $this->renderTemplate(
            'whatsrabbit-live-chat/index',
            ['displaySettings' => $displaySettings],
            View::TEMPLATE_MODE_CP
        );
    }

    /**
     * @throws InvalidConfigException
     * @throws BadRequestHttpException
     */
    public function actionSave(): ?Response
    {
        $data = $this->request->getBodyParams();
        $avatarAssetId = $data['avatarAssetId'] ?? null;
        if (is_array($avatarAssetId)) {
            $avatarAssetId = (int) $avatarAssetId[0];
        }
        $this->displaySettings->setAttributes([
            'avatarAssetId' => $avatarAssetId,
            'title' => $data['title'] ?? null,
            'description' => $data['description'] ?? null,
            'whatsAppUrl' => $data['whatsAppUrl'] ?? null,
            'enabled' => (bool)($data['enabled'] ?? null),
        ]);


        if (!$this->displaySettings->validate()) {
            return $this->asModelFailure(
                $this->displaySettings,
                Craft::t('whatsrabbit-live-chat', 'Something went wrong!'), // Flash message
                'displaySettings'// Route param key
            );
        }

        try {
            $liveChatConfig = LiveChatConfig::createFromRequest($data);
        } catch (InvalidDataException $e) {
            $this->craft::$app->session->setError('Something went wrong while creating config' . $e->getMessage());
            return $this->redirectToPostedUrl();
        }

        $saved = $this->settingsService->saveSettings(
            $liveChatConfig,
        );

        if (!$saved) {
            $this->craft::$app->session->setError('Something went wrong while saving the plugin settings');
            return $this->redirectToPostedUrl();
        }

        $this->craft::$app->session->setSuccess('Plugin settings updated');
        return $this->redirectToPostedUrl();
    }

    public function getDisplaySettings()
    {
        $settings = $this->settingsService->getSettings();
        $this->displaySettings = new DisplaySettings([
            'avatarAssetId' => $settings?->avatarAssetId,
            'description' => $settings?->description,
            'title' => $settings?->title,
            'whatsAppUrl' => $settings?->whatsAppUrl,
            'enabled' => $settings?->enabled
        ]);
    }
}
