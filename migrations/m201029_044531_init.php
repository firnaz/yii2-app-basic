<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user}}`.
 */
class m201029_044531_init extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user}}', [
            'id'                   => $this->primaryKey(),
            'username'             => $this->string()->notNull(),
            'auth_key'             => $this->string(32)->notNull(),
            'password_hash'        => $this->string()->notNull(),
            'password_reset_token' => $this->string()->unique(),
            'access_token'         => $this->string()->unique(),
            'type'                 => "ENUM('admin', 'user')",
            'status'               => $this->smallInteger()->notNull()->defaultValue(10),
            'created_at'           => $this->integer(),
            'updated_at'           => $this->integer(),
        ]);

        $this->insert('user', [
            'username'             => 'admin',
            'auth_key'             => \Yii::$app->security->generateRandomString(),
            'password_hash'        => \Yii::$app->security->generatePasswordHash("admin"),
            'password_reset_token' => null,
            'access_token'         => null,
            'type'                 => "admin",
            'status'               => 10,
            'created_at'           => time(),
            'updated_at'           => time(),
        ]);

        $this->insert('user', [
            'username'             => 'demo',
            'auth_key'             => \Yii::$app->security->generateRandomString(),
            'password_hash'        => \Yii::$app->security->generatePasswordHash("demo"),
            'password_reset_token' => null,
            'access_token'         => null,
            'type'                 => "user",
            'status'               => 10,
            'created_at'           => time(),
            'updated_at'           => time(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%user}}');
    }
}
