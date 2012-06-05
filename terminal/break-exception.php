<?php

/**
 * Tämä poikkeus esittää komennon suorittamisen hallittua kaatumista. Sitä
 * käytetään pysäyttämään komennon suoritus esimerkiksi pakollisen parametrin
 * puuttuessa.
 *
 * @author jukkah
 */
class BreakException extends Exception {
}