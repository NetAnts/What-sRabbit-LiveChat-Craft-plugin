<?php

namespace NetAnts\WhatsRabbitLiveChatTest\Model;

use Codeception\PHPUnit\TestCase;
use NetAnts\WhatsRabbitLiveChat\Model\ApiSettings;

class ApiSettingsTest extends TestCase
{
    public function testRules()
    {
        $settings = new ApiSettings();
        $rules = $settings->rules();
        $this->assertSame([[['apiKey', 'apiSecret'], 'required']], $rules);
    }
}
