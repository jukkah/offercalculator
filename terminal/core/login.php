<?php

namespace core;

/**
 * Hoitaa kirjautumisen ja muut siihen liittyvät toimenpiteet.
 *
 * @author jukkah
 */
class Login {

    /**
     * Testaa, onko käyttäjä kirjautuneena.
     * 
     * @return boolean
     */
    public static function is_logged_in() {
        return TRUE;
    }

    /**
     * Kirjaa käyttäjän sisään tunnuksella ja salasanalla.
     * @param string $user Käyttäjätunnus
     * @param string $password Salasana
     * @return boolean Kertoo, onnistuiko kirjautuminen.
     */
    public static function log_in($user, $password) {
        return TRUE;
    }

    /**
     * Kirjaa käyttäjän ulos.
     */
    public static function log_out() {
        
    }

}