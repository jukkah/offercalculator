<?php

// Varmistetaan tarvittavien tiedostojen saanti.
require_once 'commands/command.php';

/**
 * Päättelee, mikä komento pitäisi suorittaa ja suorittaa sen hallitusti.
 *
 * @author jukkah
 */
class CommandSwitcher {

    /**
     * Valitsee ja suorittaa HTTP-pyynnössä pyydetyn komennon.
     * 
     * Mikäli komentoa ei löytynyt, keskeytetään suoritus HTTP-tilakoodiin 404.
     * Mikäli komennon aikana tapahtui yllättävä virhe, lopetetaan suoritus
     * HTTP-tilakoodiin 500.
     * 
     * @return void
     */
    public function run_command() {
        $command = $this->select_command();

        if (is_null($command)) {
            header("HTTP/1.1 404 Not Found");
            return;
        }

        try {
            $command->execute();
        } catch (Exception $e) {
            //TODO: Laitetaan virhe lokiin.

            header("HTTP/1.1 500 Internal Server Error");
        }
    }

    /**
     * Valitsee suoritettavan komennon ja luo siitä ilmentymän.
     * 
     * @return Command Normaalitilanteessa paluuarvo on
     * $_REQUEST["command"][0]:ssa pyydetyn komennon ilmentymä.
     * @return null Mikäli komennon ilmentymää ei voitu syystä tai toisesta
     * luoda; sitä ei löytynyt.
     */
    private function select_command() {
        $command = NULL;

        $file_name = $_REQUEST["command"][0];

        if ($file_name !== FALSE) {
            $class_name = $this->get_command_class_name($file_name);

            if ($file_name !== FALSE) {
                $command = $this->create_command($file_name, $class_name);
            }
        }

        return $command;
    }

    /**
     * Lataa annetun nimisen komentotiedoston ja luo siitä ilmentymän.
     * 
     * @param string $file_name Komennon tiedoston nimi ilman tiedostopäätettä.
     * @param string $class_name Komennon luokan nimi.
     * @return Command Normaalitilanteessa paluuarvo on pyydetyn komennon
     * ilmentymä.
     * @return null Mikäli komennosta ei voitu syystä tai toisesta luoda
     * ilmentymää; sitä ei löytynyt.
     */
    private function create_command($file_name, $class_name) {
        $instance = NULL;

        if ($this->load_command_class($file_name)) {
            $instance = $this->create_command_instance($class_name);
        }

        return $instance;
    }

    /**
     * Lataa annetun nimisen php-tiedoston commands-hakemistosta.
     * 
     * @param string $file_name Komennon tiedoston nimi ilman tiedostopäätettä.
     * @return boolean Palautetaan tieto tiedoston lataamisen onnistumisesta. 
     */
    private function load_command_class($file_name) {
        $file_name = "commands/$file_name.php";
        $result = FALSE;

        if (file_exists($file_name)) {
            // .htaccess:issa varmistetaan, että $file_name ei nyt voi olla
            // esimerkiksi commands/../index.php tai commands/hack-me/index.php.

            require_once $file_name;

            $result = TRUE;
        }

        return $result;
    }

    /**
     * Luo ilmentymän komennosta.
     * 
     * @param string $class_name Komennon luokan nimi.
     * @return null Mikäli annetun nimistä luokkaa ei ole olemassa, tai se ei
     * toteuta Command-rajapintaa.
     * @return Command Normaalitilanteessa paluuarvo on ilmentymä annetun
     * nimisestä komennosta.
     */
    private function create_command_instance($class_name) {
        $instance = NULL;

        if (class_exists($class_name, FALSE)) {
            $instance = new $class_name();

            if (!$instance instanceof Command) {
                $instance = NULL;
            }
        }

        return $instance;
    }

    /**
     * Muuntaa komennon tiedoston nimen (ilman tiedostopäätettä) sitä vastaavan
     * luokan nimeksi.
     * 
     * @example "komento" => "Komento"
     * @example "toinen-komento" => "ToinenKomento"
     * @param string $file_name Komennon tiedoston nimi ilman tiedostopäätettä.
     * @return string 
     */
    private function get_command_class_name($file_name) {
        $osat = explode("-", $file_name);

        foreach ($osat as $index => $osa) {
            $osat[$index] = ucfirst($osa);
        }

        return implode("", $osat);
    }

}