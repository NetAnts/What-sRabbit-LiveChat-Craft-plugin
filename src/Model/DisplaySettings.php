<?php

namespace NetAnts\WhatsRabbitLiveChat\Model;

use craft\base\Model;

class DisplaySettings extends Model
{
    public ?int $avatarAssetId = null;
    public string $description = '';
    public string $title = '';
    public string $whatsAppUrl = '';
    public bool $enabled = true;

    public function rules(): array
    {
        return [
            [['title','whatsAppUrl', 'description', 'avatarAssetId'], 'required'],
            [['enabled'], 'boolean'],
        ];
    }
}
