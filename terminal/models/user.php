<?php

/**
 * Käyttäjän malli.
 * 
 * Luokka hoitaa kirjautumisen matalan tason käsittelyn.
 *
 * @author jukkah
 */
class User {

    /**
     * Testaa, onko käyttäjä kirjautuneena.
     * 
     * @return boolean
     */
    public static function isLoggedIn() {
        
    }

    /**
     * Kirjaa käyttäjän sisään tunnuksella ja salasanalla.
     * @param string $user Käyttäjätunnus
     * @param string $password Salasana
     * @return boolean Kertoo, onnistuiko kirjautuminen.
     */
    public static function logIn($user, $password) {
        
    }

    /**
     * Kirjaa käyttäjän ulos.
     */
    public static function logOut() {
        
    }

}