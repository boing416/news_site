<?php

namespace app\models;

use app\components\Mail;
use Yii;

/**
 * This is the model class for table "articles".
 *
 * @property integer $id
 * @property string $title
 * @property string $text
 * @property string $desc
 * @property string $img
 * @property integer $author_id
 * @property string $data
 * @property string $status
 */
class Articles extends \yii\db\ActiveRecord
{
    public $file;

    public $new_article; 

    const NEW_ARTICLE = 'new_article';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'articles';
    }

    public function behaviors()
    {
        return [
            [
                'class' => Mail::className(),
                'article_id' =>$this->new_article
            ]
        ];
    }

    public function articleSend()
    {

        $this->trigger(self::NEW_ARTICLE);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['author_id'], 'integer'],
            [['date'], 'safe'],
            [['status'], 'string'],
            [['title', 'desc', 'img'], 'string', 'max' => 200],
            [['text'], 'string', 'max' => 2000],
            [['file'], 'safe'],
            [['file'], 'file','extensions' => 'png,jpg'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Название',
            'text' => 'Текст',
            'desc' => 'Краткое описание',
            'img' => 'Картинка',
            'author_id' => 'Author ID',
            'date' => 'Date',
            'status' => 'Status',
            'image' => 'Image',
            'file' => 'Файл' 
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'author_id']);
    }
}
