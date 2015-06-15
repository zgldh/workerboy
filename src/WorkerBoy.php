<?php namespace zgldh\workerboy;

    /**
     * Created by PhpStorm.
     * User: zgldh
     * Date: 2015/4/11
     * Time: 20:51
     */
/**
 * Class WorkerBoy
 * @package zgldh\workerboy
 */
class WorkerBoy
{
    private static $instance = null;

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct()
    {
    }

    public function __desctruct()
    {
    }

    /**
     * @return \zgldh\workerboy\CredentialProcessor
     */
    public function getCredentialProcessor()
    {
        return \App::make('workerboy.credentialprocessor');
    }

    public function outputCredential()
    {
        return $this->getCredentialProcessor()->outputCredential();
    }

    /**
     * @param $credential
     * @return bool|int
     */
    public function validateCredential($credential)
    {
        $userId = $this->getCredentialProcessor()->validateCredential($credential);
        if (!$userId) {
            return false;
        }
        return $userId;
    }
}