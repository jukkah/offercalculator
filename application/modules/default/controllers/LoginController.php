<?php

class LoginController extends Zend_Controller_Action {

    public function init() {}

    public function indexAction()
    {
        
    }

    public function testAction()
    {
        $auth = Zend_Auth::getInstance();

        // Tarkistetaan, ollaanko kirjautuneena jollakin tunnuksella.
        $this->view->logged_in = $auth->hasIdentity();
    }

    public function outAction()
    {
        // Lopetetaan mahdollinen kirjautuminen.
        Zend_Auth::getInstance()->clearIdentity();
    }

}

