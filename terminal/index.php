<?php

/**
 * Terminaalin ajax-pyynnöt vastaanottava tiedosto.
 */

// Varmistetaan tarvittavien tiedostojen saanti.
require_once 'utils.php';
require_once '../config.php';
load_file("command-switcher.php");

// Ohjataan suoritus komennon valitsimelle.
$switcher = new CommandSwitcher();
$switcher->run_command();