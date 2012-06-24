<?php

// Varmistetaan tarvittavien tiedostojen saanti.
load_file("command.php", "commands");

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
            header("Status: 404 Not Found"); //Muutin headerin muodoksi status. Tämä on parempi tulevaisuutta ajatellen. @petjatouru
            return;
        }

        try {
            $command->execute();
        } catch (Exception $e) {
            if (!($e instanceof BreakException)) {
			
                //TODO: Laitetaan virhe lokiin.

                header("Status: 500 Internal Server Error"); //Muutin headerin muodoksi status. Tämä on parempi tulevaisuutta ajatellen. @petjatouru
            }
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

        // Varmistetaan, että komentoa on todellakin pyydetty.
        if (isset($_REQUEST["command"])) {
            $file_name = $_REQUEST["command"][0];
            $class_name = $this->get_command_class_name($file_name);

            if ($this->load_command_class($file_name)) {
                $command = $this->create_command_instance($class_name);
            }
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
        return load_file("$file_name.php", "commands");
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