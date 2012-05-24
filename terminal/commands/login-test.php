<?php

// Varmistetaan tarvittavien tiedostojen saanti.
require_once 'command.php';

/**
 * Testaa, onko käyttäjä kirjautuneena.
 * 
 * <pre>Pyyntö:
 * HEAD /login-test
 * 
 * Vastaus:
 * HTTP/1.1 200 OK
 * Logged-in: ${true|false}</pre>
 * 
 * @author jukkah
 */
class LoginTest implements Command {

    /**
     * Suorittaa komennon.
     * 
     * @return void 
     */
    public function execute() {
        
    }

}