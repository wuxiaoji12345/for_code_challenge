<?php
namespace backend\models;

use Yii;
use yii\base\Model;
use backend\models\BackendUser;

/**
 * Signup form
 */
class BackendSignupForm extends Model
{
    public $isNew;
    public $username;
    public $email;
    public $password;
    public $modifyPassword;
    public $swim_address_id;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\backend\models\BackendUser', 'message' => '用户名已经被占用.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\backend\models\BackendUser', 'message' => '邮箱已经被占用.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6, 'message' => '密码至少包含六个字符'],

            ['modifyPassword', 'trim'],

            ['swim_address_id', 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => '用户名',
            'password' => '密码',
        ];
    }

    /**
     * Signs user up.
     *
     * @return bool whether the creating new account was successful and email was sent
     */
    public function signup()
    {
        /*if(stristr($this->username, '@')){
            $this->email = $this->username;
        } else {
            $this->email = $this->username . '@example.com';
        }*/
        $this->email = md5($this->username) . '@example.com';

        if (!$this->validate()) {
            return null;
        }

        $user = new BackendUser();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->generatePasswordResetToken();
        $user->swim_address_id = intval($this->swim_address_id);
        $user->status = BackendUser::STATUS_ACTIVE;
        return $user->save();

    }

    public function modifyInfo($id)
    {
        $modelUser = BackendUser::findOne($id);
        if (!empty($this->modifyPassword)) {
            $modelUser->setPassword($this->modifyPassword);
            $modelUser->generateAuthKey();
            $modelUser->generateEmailVerificationToken();
            $modelUser->generatePasswordResetToken();
        }
        $modelUser->swim_address_id = $this->swim_address_id;
        return $modelUser->save();
    }

    /**
     * Sends confirmation email to user
     * @param BackendUser $user user model to with email should be send
     * @return bool whether the email was sent
     */
    protected function sendEmail($user)
    {
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'emailVerify-html', 'text' => 'emailVerify-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->email)
            ->setSubject('Account registration at ' . Yii::$app->name)
            ->send();
    }
}
