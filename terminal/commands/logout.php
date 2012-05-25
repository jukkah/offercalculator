<?php

// Varmistetaan tarvittavien tiedostojen saanti.
require_once 'abstract-command.php';

/**
 * Kirjaa käyttäjän ulos.
 * 
 * <pre>Pyyntö:
 * HEAD /logout
 * 
 * Vastaus:
 * HTTP/1.1 204 No Content
 * Logged-in: false</pre>
 *
 * @author jukkah
 */
class Logout extends AbstractCommand {

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