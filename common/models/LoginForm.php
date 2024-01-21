<?php
namespace common\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    private $_user;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {

            // if($this->getUser()->can('user')) {
                // // create the cookie
                // $cookies = Yii::$app->response->cookies;

                // // add a new cookie to the response to be sent
                // $cookies->add(new \yii\web\Cookie([
                //     'name' => 'e2l-auth-key',
                //     'value' => $this->getUser()->auth_key,
                //     'expire' => time() + (3600 * 24 * 30)
                // ]));

                $serverNameParts = explode('.', $_SERVER['SERVER_NAME']);
                $cookieDomain = $serverNameParts[count($serverNameParts)-2] . '.' . $serverNameParts[count($serverNameParts)-1];

                setcookie('e2l-auth-key', $this->getUser()->auth_key, time() + (3600 * 24 * 30), '/', $cookieDomain, isset($_SERVER["HTTPS"]), true);

                return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);

            /*} else {
                $this->addError('password', 'No user role');
                return false;
            }*/
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }
}
