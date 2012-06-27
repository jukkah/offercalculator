<?php

namespace TerminalCore;

require_once(__DIR__ . "/../../../../terminal/core/login.php");

/**
 * @author jukkah
 */
class LoginTest extends \Enhance\TestFixture {
    # Huom! Käyttäjät "test" ja "test2" salasanalla "test" täytyy olla olemassa.

    public function setUp() {
        session_start();
        \core\Login::log_out();
    }
    public function tearDown() {
        session_destroy();
    }

    # == is_logged_in ==========================================================
    public function normaalisti_ei_olla_kirjautuneena() {
        \Enhance\Assert::isFalse(\core\Login::is_logged_in());
    }
    public function istuntomuuttuja_maaraa_kirjautuneisuuden_tilan() {
        # Asetetaan muuttujan arvoksi TRUE.
        $_SESSION["login_status"] = TRUE;
        # Ollaan kirjautuneena.
        \Enhance\Assert::isTrue(\core\Login::is_logged_in());

        # Asetetaan muuttujan arvoksi FALSE.
        $_SESSION["login_status"] = FALSE;
        # Ei olla kirjautuneena.
        \Enhance\Assert::isFalse(\core\Login::is_logged_in());

        # Poistetaan muuttuja.
        unset($_SESSION["login_status"]);
        # Ei olla kirjautuneena.
        \Enhance\Assert::isFalse(\core\Login::is_logged_in());
    }

    # == log_out ===============================================================
    public function uloskirjautuminen_poistaa_istuntomuuttujan() {
        # Kirjaudutaan ulos.
        \core\Login::log_out();
        # Ei olla kirjautuneena.
        \Enhance\Assert::isFalse(isset($_SESSION["login_status"]));

        # Asetetaan muuttujan arvoksi TRUE.
        $_SESSION["login_status"] = TRUE;
        # Kirjaudutaan ulos.
        \core\Login::log_out();
        # Ei olla kirjautuneena.
        \Enhance\Assert::isFalse(isset($_SESSION["login_status"]));
    }

    # == log_in ================================================================
    public function tyhjilla_tunnuksilla_ei_voi_kirjautua() {
        # Tyhjillä tunnuksilla ei voi kirjautua.
        \Enhance\Assert::isFalse(\core\Login::log_in("", ""));
    }
    public function vain_toisella_tunnuksella_ei_voi_kirjautua() {
        # Tyhjällä salasanalla ei voi kirjautua.
        \Enhance\Assert::isFalse(\core\Login::log_in("test", ""));

        # Tyhjällä käyttäjätunnuksella ei voi kirjautua.
        \Enhance\Assert::isFalse(\core\Login::log_in("", "test"));
    }
    public function vaarilla_tunnuksilla_ei_voi_kirjautua() {
        # Väärällä käyttäjätunnuksella ei voi kirjautua.
        \Enhance\Assert::isFalse(\core\Login::log_in("wrong", "test"));

        # Väärällä salasanalla ei voi kirjautua.
        \Enhance\Assert::isFalse(\core\Login::log_in("test", "wrong"));

        # Väärällä käyttäjätunnus-salasana-parilla ei voi kirjautua.
        \Enhance\Assert::isFalse(\core\Login::log_in("wrong", "wrong"));
    }
    public function oikeilla_tunnuksilla_voi_kirjautua() {
        # Oikeilla tunnuksilla voi kirjautua.
        \Enhance\Assert::isTrue(\core\Login::log_in("test", "test"));
    }
    public function oikeilla_tunnuksilla_voi_kirjautua_perakkain() {
        # Oikeilla tunnuksilla voi kirjautua peräkkäin.
        \core\Login::log_in("test", "test");
        \Enhance\Assert::isTrue(\core\Login::log_in("test2", "test"));
        # Ollaan kirjautuneena.
        \Enhance\Assert::isTrue(\core\Login::is_logged_in());
    }
    public function epaonnistunut_kirjautumisyritys_ei_kirjaa_ulos_edellista_onnistunutta_kirjautumista() {
        # Oikeilla tunnuksilla voi kirjautua peräkkäin.
        \core\Login::log_in("test", "test");
        \core\Login::log_in("wrong", "wrong");
        # Ollaan kirjautuneena.
        \Enhance\Assert::isTrue(\core\Login::is_logged_in());
    }

    # == log_in + is_logged_in =================================================
    public function epaonnistuneen_kirjautumisyrityksen_jalkeen_ei_olla_kirjautuneena() {
        # Kirjautuminen ei onnistu tyhjillä tunnuksilla.
        \core\Login::log_in("", "");
        # Ei olla kirjautuneena.
        \Enhance\Assert::isFalse(\core\Login::is_logged_in());

        # Kirjautuminen ei onnistu tyhjällä salasanalla.
        \core\Login::log_in("test", "");
        # Ei olla kirjautuneena.
        \Enhance\Assert::isFalse(\core\Login::is_logged_in());

        # Kirjautuminen ei onnistu tyhjällä käyttäjätunnuksella.
        \core\Login::log_in("", "test");
        # Ei olla kirjautuneena.
        \Enhance\Assert::isFalse(\core\Login::is_logged_in());

        # Kirjautuminen ei onnistu väärällä käyttäjätunnuksella.
        \core\Login::log_in("wrong", "test");
        # Ei olla kirjautuneena.
        \Enhance\Assert::isFalse(\core\Login::is_logged_in());

        # Kirjautuminen ei onnistu väärällä salasanalla.
        \core\Login::log_in("test", "wrong");
        # Ei olla kirjautuneena.
        \Enhance\Assert::isFalse(\core\Login::is_logged_in());

        # Kirjautuminen ei onnistu väärällä käyttäjätunnus-salasana-parilla.
        \core\Login::log_in("wrong", "wrong");
        # Ei olla kirjautuneena.
        \Enhance\Assert::isFalse(\core\Login::is_logged_in());
    }
    public function onnistuneen_kirjautumisen_jalkeen_ollaan_kirjautuneena() {
        # Kirjautuminen onnistuu oikeilla tunnuksilla.
        \core\Login::log_in("test", "test");
        # Ollaan kirjautuneena.
        \Enhance\Assert::isTrue(\core\Login::is_logged_in());
    }

    # == log_out + is_logged_in ================================================
    public function pelkan_uloskirjautumisen_jalkeen_ei_olla_kirjautuneena() {
        # Kirjaudutaan ulos.
        \core\Login::log_out();
        # Ei olla kirjautuneena.
        \Enhance\Assert::isFalse(\core\Login::is_logged_in());
    }
    
    # == log_in + log_out + is_logged_in =======================================
    public function onnistuneen_kirjautumisen_ja_uloskirjautumisen_jalkeen_ei_olla_kirjautuneena() {
        # Kirjautuminen onnistuu oikeilla tunnuksilla.
        \core\Login::log_in("test", "test");
        # Kirjaudutaan ulos.
        \core\Login::log_out();
        # Ei olla kirjautuneena.
        \Enhance\Assert::isFalse(\core\Login::is_logged_in());
    }
    public function epaonnistuneen_kirjautumisen_ja_uloskirjautumisen_jalkeen_ei_olla_kirjautuneena() {
        # Kirjautuminen ei onnistu väärillä tunnuksilla.
        \core\Login::log_in("wrong", "wrong");
        # Kirjaudutaan ulos.
        \core\Login::log_out();
        # Ei olla kirjautuneena.
        \Enhance\Assert::isFalse(\core\Login::is_logged_in());
    }

}