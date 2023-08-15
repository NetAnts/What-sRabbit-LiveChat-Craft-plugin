<?php

namespace NetAnts\WhatsRabbitLiveChat\db;

use yii\db\ActiveRecord;

class Settings extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%whatsrabbit_livechat_settings}}';
    }
}
