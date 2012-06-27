<?php

namespace core;

/**
 * Hoitaa kirjautumisen ja muut siihen liittyvät toimenpiteet.
 *
 * @author jukkah & petjatouru
 */
class Login {

    /**
     * Testaa, onko käyttäjä kirjautuneena.
     * 
     * @return boolean
     */
    public static function is_logged_in() {
        return $_SESSION["login_status"] || false;
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
        session_destroy(); // "Tappaa" eli lopettaa istunnon
    }

}