<?php

// Varmistetaan tarvittavien tiedostojen saanti.
load_file("abstract-command.php", "commands");

/**
 * Kirjaa käyttäjän sisään.
 * 
 * <pre>Pyyntö:
 * HEAD /login/:user/:password
 * 
 * Vastaus:
 * HTTP/1.1 ${204 No Content|400 Bad Request}
 * Logged-in: ${true|false}</pre>
 * 
 * Jos kirjautuminen onnistui, tilakoodi on 204, muuten 400. Jos tilakoodi on
 * 400, mutta Logged-in on true, edellinen kirjautuminen on voimassa.
 * Kirjautumisia voi olla voimassa vain yksi kerrallaan.
 * 
 * @author jukkah
 */
class Login extends AbstractCommand {

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