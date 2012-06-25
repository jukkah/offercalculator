<?php

// Varmistetaan tarvittavien tiedostojen saanti.
load_file("command.php", "commands");
load_file("break-exception.php");

/**
 * Sisältää kaikille komennoille yhteiset toiminnot.
 *
 * @author jukkah
 */
abstract class AbstractCommand implements Command {

    /**
     * Lista niistä HTTP:n metodeista, joilla komentoa saa kutsua. Mahdollisia
     * metodeja ovat toistaiseksi HEAD, GET, POST, PUT, DELETE ja PATCH.
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
     * Hoitaa metodin sallittavuuden tarkistuksen.
     */
    final public function execute() {
        $method = $_SERVER["REQUEST_METHOD"];

        if ($this->accept_method($method)) {
            $this->execute_command();
        } else if ($method == "OPTIONS") {
            // Jos metodi on OPTIONS, kerrotaan, mitkä metodit ovat sallittuja.
            $this->execute_options();
        } else {
            $this->method_not_allowed();
        }
    }

    private function accept_method($method) {
        return in_array($method, $this->ACCEPTED_METHODS);
    }

    /**
     * Tarkistaa, että pyynnön mukana välitettiin tietty parametri, ja palauttaa
     * sen arvon. Mikäli parametri puuttuu tai sen on tyhjä, keskeytetään
     * komennon suoritus.
     * 
     * @param string $name Pakollisen parametrin nimi, jota pyydetään.
     * @return string Parametrin arvo.
     * @throws BreakException Mikäli parametri puuttui tai se oli tyhjä.
     */
    protected function get_required_param($name) {
        $param = $_REQUEST[$name];

        if (!isset($param) OR strlen($param) == 0) {
            $this->fail("1-$name");

            // Keskeytetään komennon suorittaminen.
            throw new BreakException();
        }

        return $param;
    }

    /**
     * Lisää tilakoodin 400 ja parametrina saadun vikakoodin otsakkeisiin.
     * 
     * @param string $error_code Vikakoodi, joka laitetaan otsakkeeseen.
     */
    protected function fail($error_code) {
        header("Status: 400 Bad Request");
        header("Error-code: $error_code");
    }

    /**
     * Lisää 405 tilakoodin.
     */
    protected function method_not_allowed() {
        header("Status: 405 Method Not Allowed");
    }

    /**
     * Vastaa OPTIONS-metodin kutsuun.
     */
    private function execute_options() {
        $methods = implode(", ", $this->ACCEPTED_METHODS);

        header("Allow: $methods");
    }

}
