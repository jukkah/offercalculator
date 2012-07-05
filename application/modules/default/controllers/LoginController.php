<?php

// Tätä ei pitäisi tarvita, mutta ilman tulee virheilmoitus.
require_once APPLICATION_PATH . '/modules/default/models/Login.php';

class LoginController extends Zend_Controller_Action
{

    public function init()
    {}

    public function indexAction()
    {
        // Alustetaan taulukko virhekoodeja varten.
        $error_codes = array();

        // Noudetaan ja validoidaan parametrit.
        $username = $this->_getParam("username", $error_codes);
        $password = $this->_getParam("password", $error_codes);

        // Mikäli ei ole virheitä, eli sekä tunnus että salasana on välitetty,
        // yritetään kirjautua sisään.
        if (count($error_codes) == 0) {
            $this->_log_in($username, $password, $error_codes);
        }

        // Mikäli on virheitä, palautetaan nooden vikakoodit.
        if (count($error_codes) > 0) {
            $this->view->error_code = $error_codes;
        }

        // Tarkistetaan kirjautuneisuus.
        $this->testAction();
    }

    public function testAction()
    {
        // Otetaan käyttöön tarvittavat muuttujat.
        $login = Default_Model_Login::getInstance();

        // Tarkistetaan kirjautuneisuus.
        $this->view->logged_in = $login->is_logged_in();
    }

    public function outAction()
    {
        // Kirjaudutaan ulos.
        Default_Model_Login::getInstance()->log_out();
    }
    
    /**
     * _getParam - hakee pakollisen parametrin arvon. Mikäli parametri puuttuu
     * tai on tyhjä, lisätään vikakoodi "1-parametin_nimi".
     * 
     * @param string $name Parametrin nimi
     * @param array $error_codes Taulukko, johon mahdollinen vikakoodi
     * sijoitetaan.
     * @return mixed Parametrin arvo.
     */
    protected function _getParam($name, &$error_codes)
    {
        $param = $this->_request->getParam($name);
        
        // Tarkistetaan, puuttuuko parametri.
        if ($param == null) {
            $this->_response->setHttpResponseCode(400);
            $error_codes[] = "1-$name";
        }
        
        return $param;
    }
    
    /**
     * _log_in - yritää kirjata käyttäjän sisään. Mikäli kirjautuminen ei
     * onnistu, lisätään vikakoodi "2".
     * 
     * @param string $username Käyttäjätunnus
     * @param string $password Salasana
     * @param array $error_codes Taulukko, johon mahdollinen vikakoodi
     * sijoitetaan.
     */
    protected function _log_in($username, $password, &$error_codes)
    {
        // Otetaan käyttöön tarvittavat muuttujat.
        $login = Default_Model_Login::getInstance();
        
        // Kirjaudutaan sisään.
        $login_successed = $login->log_in($username, $password);

        // Tarkistetaan, epäonnistuiko kirjautuminen.
        if (!$login_successed) {
            $this->_response->setHttpResponseCode(400);
            $error_codes[] = "2";
        }
    }

}
