<?php

// $_REQUEST["command"] sisältää komennon nimen.

// Varmistetaan tarvittavien tiedostojen saanti.
require_once 'command-switcher.php';

// Pilkotaan command taulukoksi /-merkin kohdalta.
if (isset($_REQUEST["command"])) {
    $_REQUEST["command"] = explode("/", $_REQUEST["command"]);
}

// Ohjataan suoritus komennon valitsimelle.
$switcher = new CommandSwitcher();
$switcher->run_command();