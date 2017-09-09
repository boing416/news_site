<?php
namespace app\models;

use app\models\User;
use yii\base\Model;
use Yii;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $firstname;
    public $lastname;
    public $patronymic;
    public $date_of_birth;
    public $place_of_living;
    public $email;
    public $password;
    public $passwordConfirm;
    public $verifyCode;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => 'app\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],
            ['lastname', 'required'],
            ['firstname', 'required','message' => 'Имя'],
            ['firstname', 'filter', 'filter' => 'trim'],
            ['patronymic', 'filter', 'filter' => 'trim'],
            ['date_of_birth', 'filter', 'filter' => 'trim'],
            ['place_of_living', 'filter', 'filter' => 'trim'],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => 'app\models\User', 'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
            [['passwordConfirm'], 'compare', 'compareAttribute' => 'password', 'message' => 'Password do not match'],
            ['verifyCode', 'captcha'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => Yii::t('app', 'username'),
            'firstname' => Yii::t('app', 'firstname'),
            'lastname' => Yii::t('app', 'lastname'),
            'password' => Yii::t('app', 'password'),
            'verifyCode' => 'Verification Code',

        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {


        if ($this->validate()) {


            $user = new User();
            $user->username = $this->username;
            $user->firstname = $this->firstname;
            $user->lastname = $this->lastname;
            $user->patronymic = $this->patronymic;
            $user->date_of_birth = $this->date_of_birth; 
            $user->email = $this->email;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            if ($user->save()) {
                return $user;
            }
        }

//        return null;
    }

    public function update($id)
    {

            $user = User::findOne($id);
            $user->username = $this->username;
            $user->firstname = $this->firstname;
            $user->lastname = $this->lastname;
            $user->patronymic = $this->patronymic;
            $user->date_of_birth = $this->date_of_birth;
            $user->place_of_living = $this->place_of_living;
            $user->email = $this->email;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            if ($user->save()) {
                return 'ok';
            }


        return null;
    }
}
