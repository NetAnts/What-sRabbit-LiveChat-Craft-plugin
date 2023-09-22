<?php

namespace NetAnts\WhatsRabbitLiveChat\Model;

use craft\base\Model;

class ApiSettings extends Model
{
    public string $apiKey = '';
    public string $apiSecret = '';

    public function rules(): array
    {
        return [
            [['apiKey', 'apiSecret'], 'required'],
        ];
    }
}
