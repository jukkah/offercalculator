<?php

# == Tietokantayhteyden muuttujat ==============================================

# Tietokantapalvelimen tyyppi.
define("DATABASE_SERVER_TYPE", "MySQL");

# Tietokantayhteyden parametrit.
define("DATABASE_HOST",     "127.0.0.1");
define("DATABASE_PORT",     "3306");
define("DATABASE_USERNAME", "root");
define("DATABASE_PASSWORD", "");

# Sovelluksen käyttäjän tietokannan nimi ja mahdollinen taulujen etuliite.
define("DATABASE_NAME",         "offercalculator");
define("DATABASE_TABLE_PREFIX", "");

# Sovelluksen automaattisiin testeihin käyttämän tietokannan nimi ja mahdollinen
# taulujen etuliite. Vähintään toisen tulisi olla eri kuin normaalissa
# käytössä, jotta testidata ei sekoitu normaaliin dataan.
define("TEST_DATABASE_NAME",         "test-offercalculator");
define("TEST_DATABASE_TABLE_PREFIX", "");
