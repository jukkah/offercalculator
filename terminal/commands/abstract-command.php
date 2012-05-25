<?php

// Varmistetaan tarvittavien tiedostojen saanti.
require_once 'command.php';

/**
 * Sisältää kaikille komennoille yhteiset toiminnot.
 *
 * @author jukkah
 */
abstract class AbstractCommand implements Command {

    /**
     * Lista niistä HTTP:n metodeista, joilla komentoa saa kutsua. Mahdollisia
     * metodeja ovat esimerkiksi HEAD, GET, POST, PUT, DELETE ja PATCH.
     * 
     * @var array Lista sallituista HTTP:n metodeista.
     */
    private $ACCEPTED_METHODS;

    /**
     * Luo komennon, hyväksyttävillä HTTP:n metodeilla.
     * @param type $accepted_methods 
     */
    public function __construct($accepted_methods = array("GET")) {
        $this->ACCEPTED_METHODS = $accepted_methods;
    }

    /**
     * Tekee suorituksen valmistelevia tarkistuksia.
     */
    final public function execute() {
        $method = $_SERVER["REQUEST_METHOD"];

        if (in_array($method, $this->ACCEPTED_METHODS)) {
            // Jos metodi on sallittu, suoritetaan komento.
            $this->execute_command();
        } else if ($method == "OPTIONS") {
            // Jos metodi on OPTIONS, kerrotaan, mitkä metodit ovat sallittuja.
            $this->execute_options();
        } else {
            $this->method_not_allowed();
        }
    }

    private function execute_options() {
        $methods = implode(", ", $this->ACCEPTED_METHODS);

        header("Allow: $methods");
    }

    /**
     * Tällä metodilla katkaistaan komennon suoritus HTTP:n 405 virheeseen.
     */
    protected function method_not_allowed() {
        header("HTTP/1.1 405 Method Not Allowed");
    }

    /**
     * Suorittaa varsinaisen komennon.
     * 
     * @return void
     */
    protected abstract function execute_command();
}
