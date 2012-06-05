<?php

/**
 * Terminaalin käsky, jota voidaan kutsua selaimesta.
 * 
 * Perivien luokkien nimen ja tiedoston nimen on täytettävä seuraavat säännöt:
 * 
 * Komento <-> komento.php<br>
 * CommandName <-> command-name.php
 * 
 * Lisäksi niiden on oltava terminal/commadns-hakemiston alla.
 * 
 * @author jukkah
 */
interface Command {

    /**
     * Suorittaa komennon.
     * 
     * Mikäli metodi heittää poikkeuksen, selaimelle lähetetään tilakoodi 500.
     * 
     * @return void
     */
    public function execute();
}