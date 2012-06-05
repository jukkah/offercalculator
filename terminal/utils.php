<?php

/**
 * Sekalaisia funktioita.
 */

// Pilkkoo command-parametrin taulukoksi /-merkin kohdalta.
if (isset($_REQUEST["command"])) {
    $_REQUEST["command"] = explode("/", $_REQUEST["command"]);
}

/**
 * Terminaalin juurihakemisto.
 * 
 * Huom! Mikäli tämä tiedosto siirretään pois terminaalin juurihakemistosta,
 * vakion arvoa pitää muuttaa.
 */
define("TERMINAL_ROOT_DIR", dirname(__FILE__));

/**
 * Asetetaan lataushakemistoksi terminaalin juurihakemisto. 
 */
ini_set("include_path", TERMINAL_ROOT_DIR);

/**
 * Lataa tiedoston vaaditusta hakemistosta.
 * @param string $file_name Ladattavan tiedoston polku emohakemistosta.
 * @param string $dir_name Ladattavan tiedoston emohakemiston polku terminaalin
 * juurihakemistosta. Oletuksena tyhjä.
 * @return boolean Onnistuiko tiedoston lataaminen. 
 */
function load_file($file_name, $dir_name = "") {
    $result = FALSE;

    // Ladattavan tiedoston ja sen vaaditun hakemiston oikeat polut
    // tiedostojärjestelmässä.
    $include_file = realpath("./$dir_name/$file_name");
    $include_dir = realpath("./$dir_name");

    // Ehto on epätosi mm., jos tiedostoa ei ole olemassa.
    if ($include_file !== FALSE) {
        if (allow_include($include_file, $include_dir)) {
            require_once $include_file;
            $result = TRUE;
        }
    }

    return $result;
}

/**
 * Tarkistaa, saako tiedostoa ladata.
 * 
 * @param string $file Ladattavan tiedoston oikea polku tiedostojärjestelmässä.
 * @param string $dir Tiedoston vaaditun emohakemiston oikea polku
 * tiedostojärjestelmässä. Oletuksena terminaalin juurihakemisto.
 * @return boolean 
 */
function allow_include($file, $dir = TERMINAL_ROOT_DIR) {
    // Ladattavan tiedoston on oltava terminaalin juurihakemiston alla.
    if (!is_in_terminal_root_dir($file)) {
        return FALSE;
    }

    // Tiedoston pitää olla vaaditun emohakemiston alla.
    if (strpos($file, $dir) !== 0) {
        return FALSE;
    }

    return TRUE;
}

/**
 * Kertoo, onko tiedosto terminaalin juurihakemiston alla.
 * @param string $file_name Tiedoston oikea polku tiedostojärjestelmässä.
 * @return boolean Onko tiedosto terminaalin juurihakemiston alla. 
 */
function is_in_terminal_root_dir($file_name) {
    return strpos($file_name, TERMINAL_ROOT_DIR) !== 0;
}