<?php

use yii\db\Migration;

class m170904_233417_articles extends Migration
{

    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->createTable('articles', [
           'id' => $this->primaryKey(),
            'title' => $this->string(200),
            'text' => $this->string(2000),
            'desc' => $this->string(200),
            'img' => $this->string(200),
            'author_id' => $this->integer(),
            'date' => $this->date("Y-m-d H:i:s"),
            'status' => "ENUM('active', 'inactive')",
        ]);

    }

    public function down()
    {
        echo "m170904_233417_articles cannot be reverted.\n";

        return false;
    }

    //    public function safeUp()
//    {
//
//    }
//
//    public function safeDown()
//    {
//        echo "m170904_233417_articles cannot be reverted.\n";
//
//        return false;
//    }

}
