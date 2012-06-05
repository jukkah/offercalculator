<?php

// Varmistetaan tarvittavien tiedostojen saanti.
load_file("abstract-command.php", "commands");
load_file("login.php", "core");

/**
 * Kirjaa käyttäjän ulos.
 * 
 * Pyyntö:
 * <pre>HEAD /logout HTTP/1.1</pre>
 * Vastaus:
 * <pre>HTTP/1.1 204 No Content
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
        \core\Login::log_out();

        header("HTTP/1.1 204 No Content");
        header("Logged-in: false");
    }

}