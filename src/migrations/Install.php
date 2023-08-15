<?php

namespace NetAnts\WhatsRabbitLiveChat\migrations;

use Craft;
use craft\db\Migration;

/**
 * m230808_090125_whatsrabbit_livechat_settings migration.
 */
class Install extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp(): bool
    {
        $this->dropTableIfExists('{{%whatsrabbit_livechat_settings}}');

        $this->createTable(
            '{{%whatsrabbit_livechat_settings}}',
            [
                'id' => $this->primaryKey(),
                'avatar_asset_id' => $this->integer()->notNull(),
                'title' => $this->string()->notNull(),
                'description' => $this->string()->notNull(),
                'whatsapp_url' => $this->string()->notNull(),
                'enabled' => $this->tinyInteger()->notNull()->defaultValue(1),
            ]
        );

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown(): bool
    {
        $this->dropTableIfExists('{{%whatsrabbit_livechat_settings}}');
        return true;
    }
}
