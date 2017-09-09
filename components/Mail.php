<?php

namespace app\components;

use app\models\Articles;
use app\models\User;
use yii\base\Behavior;

class Mail extends Behavior
{

    public $user_id;
    public $article_id;

    public function sendMessage()
    {

       $user = $this->owner->register_user_id;

        \Yii::$app->mailer->compose()
            ->setTo($user->email)
            ->setFrom([\Yii::$app->params['adminEmail'] => \Yii::$app->name . ' robot'])
            ->setSubject('Signup Confirmation')
            ->setHtmlBody(" Click this link <a href='/web/index.php?r=site/confirm?id=".$user->id."&key=".$user->auth_key."'>confirm</a>"
            )
            ->send();

    }

    public function sendMessageArticle()
    {

        $article = $this->owner->register_user_id;

        $Users = User::find()->all();

        foreach ($Users as $user) {
            \Yii::$app->mailer->compose()
                ->setTo($user->email)
                ->setFrom([\Yii::$app->params['adminEmail'] => \Yii::$app->name . ' robot'])
                ->setSubject('Signup Confirmation')
                ->setHtmlBody(" New article: <a href='/web/index.php?r=article/view?id=".$article->id."'>".$article->title."</a>"
                )
                ->send();
        }



    }

    public function events()
    {
        return [
            User::USER_REGISTERED => 'sendMessage',
            Articles::NEW_ARTICLE => 'sendMessageArticle'
        ];
    }

}