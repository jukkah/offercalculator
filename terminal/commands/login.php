<?php

// Varmistetaan tarvittavien tiedostojen saanti.
require_once 'command.php';

/**
 * Kirjaa käyttäjän sisään.
 * 
 * <pre>Pyyntö:
 * HEAD /login/:user/:password
 * 
 * Vastaus:
 * HTTP/1.1 ${200 OK|400 Bad Request}
 * Logged-in: ${true|false}</pre>
 * 
 * Jos kirjautuminen onnistui, tilakoodi on 200, muuten 400. Jos tilakoodi on
 * 400, mutta Logged-in on true, edellinen kirjautuminen on voimassa.
 * Kirjautumisia voi olla voimassa vain yksi kerrallaan.
 * 
 * @author jukkah
 */
class Login implements Command {

    /**
     * Suorittaa komennon.
     * 
     * @return void 
     */
    public function execute() {
        
    }

}