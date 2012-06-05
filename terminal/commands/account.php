<?php

// Varmistetaan tarvittavien tiedostojen saanti.
load_file("abstract-command.php", "commands");

/**
 * Hoitaa käyttäjätilin luonnin (PUT), tietojen haun (GET), tietojen päivityksen
 * (PATCH) ja poiston (DELETE). Katso tarkemmat tiedot kunkin metodin kohdalta.
 * 
 * @author jukkah
 */
class Account extends AbstractCommand {

    /**
     * Luo ilmentymän komennosta sallituilla komennoilla.
     */
    public function __construct() {
        parent::__construct(array("PUT", "GET", "PATCH", "DELETE"));
    }

    /**
     * Suorittaa komennon.
     * 
     * @return void 
     */
    protected function execute_command() {
        $method = $_SERVER["REQUEST_METHOD"];

        if ($method == "PUT") {
            $this->create_account();
        } else if ($method == "GET") {
            $this->get_account_data();
        } else if ($method == "PATCH") {
            $this->update_account_data();
        } else if ($method == "DELETE") {
            $this->remove_account();
        } else {
            // TODO: Kirjataan virhe lokiin.

            $this->method_not_allowed();
        }
    }

    /**
     * Suorittaa käyttäjätilin lisäämisen.
     * 
     * @return void 
     */
    protected function create_account() {
        
    }

    /**
     * Suorittaa käyttäjätilin tietojen haun.
     * 
     * @return void 
     */
    protected function get_account_data() {
        
    }

    /**
     * Suorittaa käyttäjätilin tietojen päivittämisen.
     * 
     * @return void 
     */
    protected function update_account_data() {
        
    }

    /**
     * Suorittaa käyttäjätilin poiston.
     * 
     * @return void 
     */
    protected function remove_account() {
        
    }

}