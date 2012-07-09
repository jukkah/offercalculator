<?php

require_once 'OC_Auth.php';
require_once 'OC_Auth_Adapter.php';

class Default_Model_Login
{

    /**
     * Singleton instance
     *
     * @var Default_Model_Login
     */
    protected static $_instance;

    /**
     * Singleton pattern implementation makes "new" unavailable
     *
     * @return void
     */
    protected function __construct()
    {}

    /**
     * Singleton pattern implementation makes "clone" unavailable
     *
     * @return void
     */
    protected function __clone()
    {}

    /**
     * Returns an instance of Default_Model_Login
     *
     * Singleton pattern implementation
     *
     * @return Default_Model_Login
     */
    public static function getInstance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function logIn($username, $password)
    {
        // Alustetaan tarvittavat muuttujat.
        $auth = OC_Auth::getInstance();
        
        $adapter = new OC_Auth_Adapter(null, "users");
        $adapter->setIdentityColumn("username")
                ->setHashColumn("password")
                ->setSaltColumn("salt")
                ->setIdColumn("id_pk")
                ->setIdentity($username)
                ->setCredential($password);
        
        // Kirjaudutaan sisään.
        $result = $auth->authenticate($adapter);
        
        // Palautetaan tieto kirjautumisen onnistumisesta.
        return $result->isValid();
    }

    public function logOut()
    {
        // Poistetaan mahdollinen identiteetti.
        Zend_Auth::getInstance()->clearIdentity();
    }

    public function isLoggedIn()
    {
        // Tarkistetaan, onko voimassa olevaa identiteettiä.
        return Zend_Auth::getInstance()->hasIdentity();
    }

}