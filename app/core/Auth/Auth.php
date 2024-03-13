<?php

namespace App\core\Auth;

use App\core\Session\Session;
use App\core\Db\Db;
use App\models\AccountModel;
use App\core\Validator\Validator;
use Exception;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use App\core\Request\Request;
use App\core\Redirect\Redirect;

class Auth
{
    public static function check_user()
    {
        $request =  Request::createFromGlobals();
        if (Session::has('username')) {
            return [
                'username' => Session::get('username'),
                'auth' => Session::get('auth'),
                'role' => Session::get('role')
            ];
        } else if (Session::hasCookie('id') and Session::hasCookie('hash')) {

            $userip = ip2long($request->server['REMOTE_ADDR']);
            $accountModel = new AccountModel;
            $id = Session::getCookie('id');
            $params = ['id' => $id];
            $userdata = $accountModel->get_user_data($params);

            if (($userdata[0]['hash'] !== $request->cookie['hash']) or ($userdata[0]['id'] !== intval($request->cookie['id']))
                or (($userdata[0]['ip'] !== $userip)  and ($userdata[0]['ip'] !== "0"))
            ) {
                Session::removeCookie("username");
                Session::removeCookie("id");
                Session::removeCookie("hash");
            } else {
                Session::setArray([
                    'username' => $userdata[0]['login'],
                    'id' => $userdata[0]['id'],
                    'auth' => true,
                    'role' => $userdata[0]['role']
                ]);
                return [
                    'username' => $userdata[0]['login'],
                    'auth' => true,
                    'id' => $userdata[0]['id'],
                    'role' => $userdata[0]['role']
                ];
            }
        }
    }

    public static function signup()
    {
        $request =  Request::createFromGlobals();
        if (isset($request->post['signup'])) {
            $username = $request->post['username'];
            $password = $request->post['password'];
            $confirm = $request->post['confirm'];
            $fail = Validator::validate_username($username);
            $fail .= Validator::check_username_exist($username);
            $fail .= Validator::validate_password($password, $confirm);
            if (empty($fail)) {
                $password = password_hash($password, PASSWORD_DEFAULT);
                $params = ['username' => $username, 'password' => $password, 'role' => 'user'];
                $accountModel = new AccountModel;
                $id = $accountModel->insert_user_data($params);
                Session::has('auth') ? Session::destroyAll() : false;
                Session::setArray([
                    'auth' => true,
                    'username' => $username,
                    'id' => $id,
                    'role' => 'user'
                ]);
                Redirect::redirect('/home');
            } else {
                Session::set('errors', $fail);
                Redirect::redirect('/account/signup');
            }
        }
    }

    public static function signin()
    {
        $request =  Request::createFromGlobals();
        $sessionToken = Session::get('CSRF');
        $postToken = $request->post['token'] ?? null;

        if (isset($request->post['signin'])) {

            if ($sessionToken == $postToken) {
                $username = Validator::test_input($request->post['username']);
                $password = Validator::test_input($request->post['password']);
                $accountModel = new AccountModel;
                $params = ['username' => $username];
                $data = $accountModel->get_signin_data($params);
                if ($data and password_verify($password, $data[0]['password'])) {
                    $id = $data[0]['id'];
                    $role = $data[0]['role'];
                    Session::setArray([
                        'auth' => true,
                        'username' => $username,
                        'id' => $id,
                        'role' => $role
                    ]);

                    if (isset($request->post['save_user'])) {
                        $hash = md5(static::generateCode(10));
                        $ip = ip2long($request->server['REMOTE_ADDR']);
                        $accountModel = new AccountModel;
                        $params = ['hash' => $hash, 'ip' => $ip, 'id' => $id];
                        $accountModel->update_user_hash($params);
                        Session::setCookie('id', $id);
                        Session::setCookie('hash', $hash);
                        Session::setCookie('username', $username);
                    }
                    Redirect::redirect('/home');
                } else {
                    $fail = 'Неверный пароль или имя пользователя';
                    Session::set('errors', $fail);
                    $log = new Logger('auth_logger');
                    $log->pushHandler(new StreamHandler(LOGS . 'auth_logs.log', Logger::WARNING));
                    $log->warning('Неудачная попытка входа:' . 'name=' . $request->post['username'] . '; ' . 'ip=' . $request->server['REMOTE_ADDR']);
                    Redirect::redirect('/account/signin');
                }
            }
        }
    }

    public static function generateCode($length = 6)
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHI JKLMNOPRQSTUVWXYZ0123456789";
        $code = "";
        $clen = strlen($chars) - 1;
        while (strlen($code) < $length) {
            $code .= $chars[mt_rand(0, $clen)];
        }
        return $code;
    }

    public static function signout(): void
    {
        $request =  Request::createFromGlobals();
        if (isset($request->post['signout'])) {
            Session::removeCookie("username");
            Session::removeCookie("id");
            Session::removeCookie("hash");
            Session::destroyAll();
            Redirect::redirect('/home');
        }
    }

    public static function getParamOauth()
    {
        $clientId     = '51874698';
        $clientSecret = 'qc95B8qYovryFdS75g24';
        $redirectUri  = 'https://bytly.in/00cd12';

        $params = array(
            'client_id'     => $clientId,
            'redirect_uri'  => $redirectUri,
            'response_type' => 'code',
            'v'             => '5.126',
            'scope'         => 'mail,offline',
        );


        return $params;
    }

    public static function getAccessToken()
    {
        $request =  Request::createFromGlobals();
        $params = array(
            'client_id'     => '51874698',
            'client_secret' => 'qc95B8qYovryFdS75g24',
            'code'          => $request->get['code'],
            'redirect_uri'  => 'https://bytly.in/00cd12'
        );

        if (!$content = @file_get_contents('https://oauth.vk.com/access_token?' . http_build_query($params))) {
            $error = error_get_last();
            throw new Exception('HTTP request failed. Error: ' . $error['message']);
        }

        $response = json_decode($content);

        if (isset($response->error)) {
            throw new Exception('При получении токена произошла ошибка. Error: ' . $response->error . '. Error description: ' . $response->error_description);
        }

        $token = $response->access_token;
        $expiresIn = $response->expires_in;
        $userId = $response->user_id;
        $mail = $response->mail;
        Session::setArray(['username' => $mail, 'id' => $userId, 'token' => $token, 'role' => 'vk_user']);
        Redirect::redirect('/home');
    }

    public static function check_auth()
    {
        if (Session::has('auth')) {
            return true;
        } else {
            Redirect::redirect('/home');
        }
    }
}
