<?php

// Varmistetaan tarvittavien tiedostojen saanti.
require_once 'abstract-command.php';

/**
 * Testaa, onko käyttäjä kirjautuneena.
 * 
 * <pre>Pyyntö:
 * HEAD /login-test
 * 
 * Vastaus:
 * HTTP/1.1 204 No Content
 * Logged-in: ${true|false}</pre>
 * 
 * @author jukkah
 */
class LoginTest extends AbstractCommand {

    /**
     * Luo ilmentymän komennosta sallituilla komennoilla.
     */
    public function __construct() {
        parent::__construct(array("HEAD"));
    }

    /**
     * Suorittaa komennon.
     * 
     * @return void 
     */
    protected function execute_command() {
        
    }

}