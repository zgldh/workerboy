<?php namespace zgldh\workerboy;

use Illuminate\Session\Store;

/**
 * Class CredentialProcessor
 * 用于“生成凭证”和“检验凭证”
 * @package zgldh\workerboy
 */
class CredentialProcessor
{
    protected $tokenKey = '_workerboy_credential_token';

    /**
     * Called by web server<br>
     * 生成并返回凭证。生成的凭证默认存储在Session里。<br>
     * 你可以改写该函数，存储在数据库、文件里、Memcache里都行。凭证数据结构也可以改。
     * @return array
     */
    public function outputCredential()
    {
        $token = str_random(40);

        $sessionId = \Session::getId();
        $credential = array(
            \Crypt::encrypt($sessionId),
            \Crypt::encrypt($token)
        );
        \Session::put($this->tokenKey, $token);
        return $credential;
    }

    /**
     * Called by workerman application<br>
     * 检验凭证。 参数是凭证数据。如果检验通过，请返回用户ID；否则返回false
     * @param $credential
     * @return bool|integer
     */
    public function validateCredential($credential)
    {
        list($sessionId, $token) = $credential;
        $sessionId = \Crypt::decrypt($sessionId);
        $token = \Crypt::decrypt($token);

        $sessStore = new Store($sessionId, \App::make('session')->driver()->getHandler(), $sessionId);
        $sessStore->start();
        if ($sessStore->has($this->tokenKey)) {
            if ($sessStore->get($this->tokenKey) == $token) {
                $sessStore->remove($this->tokenKey);

                $userIdKey = 'login_' . md5('Illuminate\Auth\Guard');
                $userId = $sessStore->get($userIdKey);

                return $userId;
            }
        }
        return false;
    }
}