<?php

/**
 * Terminaalin käsky, jota voidaan kutsua selaimesta.
 * 
 * Perivien luokkien nimen ja tiedoston nimen on täytettävä seuraavat säännöt:
 * 
 * Komento <-> komento.php<br>
 * CommandName <-> command-name.php
 * 
 * Lisäksi niiden on oltava samassa kansiossa.
 * 
 * @author jukkah
 */
interface Command {

    /**
     * Suorittaa komennon.
     * 
     * Mikäli metodi heittää virheen, selaimelle lähetetään HTTP-virhe 500.
     * 
     * @return void
     */
    public function execute();
}