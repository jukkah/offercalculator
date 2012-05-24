<?php

// Varmistetaan tarvittavien tiedostojen saanti.
require_once 'command.php';

/**
 * Kirjaa käyttäjän ulos.
 * 
 * <pre>Pyyntö:
 * HEAD /logout
 * 
 * Vastaus:
 * HTTP/1.1 200 OK
 * Logged-in: false</pre>
 *
 * @author jukkah
 */
class Logout implements Command {

    /**
     * Suorittaa komennon.
     * 
     * @return void 
     */
    public function execute() {
        
    }

}