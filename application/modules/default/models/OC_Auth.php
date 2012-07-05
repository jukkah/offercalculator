<?php

/**
 * Toimii samalla tavalla kuin Zend_Auth, mutta sisältää yhden muutoksen:
 * Olemassa olevaa kirjautumista ei lopeteta epäonnistuneeseen
 * kirjautumisyritykseen, vaan vasta sen onnistuttua.
 */
class OC_Auth extends Zend_Auth
{
    /**
     * Returns an instance of OC_Auth
     *
     * Singleton pattern implementation
     *
     * @return OC_Auth
     */
    public static function getInstance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }
    
    /**
     * Authenticates against the supplied adapter
     * 
     * ZF-7546 - prevent multiple succesive calls from storing inconsistent
     * results. Ensure storage has clean state. (manipuloitu)
     * 
     * Epäonnistunut kirjautumisyritys ei lopeta voimassa olevaa kirjautumista.
     * Toimintoa tarvitaan käyttäjätilin vaihtamisessa.
     *
     * @param  Zend_Auth_Adapter_Interface $adapter
     * @return Zend_Auth_Result
     */
    public function authenticate(Zend_Auth_Adapter_Interface $adapter)
    {
        $result = $adapter->authenticate();

        if ($result->isValid()) {
            
            // Mikäli kirjautuminen onnistui, edellinen kirjautuminen lakkaa.
            if ($this->hasIdentity()) {
                $this->clearIdentity();
            }
            
            $this->getStorage()->write($result->getIdentity());
        }

        return $result;
    }
}

