<?php

// Varmistetaan tarvittavien tiedostojen saanti.
load_file("abstract-command.php", "commands");
load_file("login.php", "core");

/**
 * Testaa, onko käyttäjä kirjautuneena.
 * 
 * Pyyntö:
 * <pre>HEAD /login-test HTTP/1.1</pre>
 * Vastaus:
 * <pre>HTTP/1.1 204 No Content
 * Logged-in: [boolean]</pre>
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
        $logged_in = \core\Login::is_logged_in() ? "true" : "false";
        
        header("HTTP/1.1 204 No Content");
        header("Logged-in: $logged_in");
    }

}