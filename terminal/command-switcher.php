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
        $command = $this->get_command();

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
    private function get_command() {
        $command = NULL;

        $file_name = $_REQUEST["command"][0];
        $class_name = $this->get_command_class_name($file_name);

        if ($this->load_command_class($file_name)) {
            $command = $this->create_command_instance($class_name);
        }

        return $command;
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
            // Koska $file_name ei voi sisältää /-merkkiä, tiedosto ladataan
            // aina commands-hakemistosta.
            
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