<?php

// $_REQUEST["command"] sisältää komennon nimen.
// $_REQUEST["params"] sisältää komennon parametrit, jota välitettiin URL:in
// osana.
// Varmistetaan tarvittavien tiedostojen saanti.
require_once 'command-switcher.php';

// Ohjataan suoritus komennon valitsimelle.
$switcher = new CommandSwitcher();
$switcher->run_command();