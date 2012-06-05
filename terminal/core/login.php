<?php

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
    public static function isLoggedIn() {
        return TRUE;
    }

    /**
     * Kirjaa käyttäjän sisään tunnuksella ja salasanalla.
     * @param string $user Käyttäjätunnus
     * @param string $password Salasana
     * @return boolean Kertoo, onnistuiko kirjautuminen.
     */
    public static function logIn($user, $password) {
        return TRUE;
    }

    /**
     * Kirjaa käyttäjän ulos.
     */
    public static function logOut() {
        
    }

}