// Testit hakemiston /terminal/commands tiedostoille login.php, logout.php ja
// login-test.php.

module("Login commands");

test("Login", function() {
    /* = Perustestit =========================================================*/
    
    // /login on olemassa.
    var req = ajax("OPTIONS", "/login");
    equal(status(req), 200, "/login on olemassa.");
    
    // /login hyväksyy vain HEAD- ja GET-metodit.
    var methods = header("Allow", req).split(", ").sort();
    deepEqual(methods, ["GET", "HEAD"], "/login hyväksyy vain HEAD- ja GET-metodit.");
    
    /* = Virheelliset syötteet ===============================================*/
    
    // Kirjautuminen ei onnistu ilman tunnuksia.
    var statusCode = ajaxStatus("HEAD", "/login");
    equal(statusCode, 400, "Kirjautuminen ei onnistu ilman tunnuksia. (1/2)");
    statusCode = ajaxStatus("HEAD", "/login/:/:");
    equal(statusCode, 400, "Kirjautuminen ei onnistu ilman tunnuksia. (2/2)");
    
    // Kirjautuminen ei onnistu pelkästään käyttäjätunnuksella.
    statusCode = ajaxStatus("HEAD", "/login/:user");
    equal(statusCode, 400, "Kirjautuminen ei onnistu pelkästään käyttäjätunnuksella.");
    
    // Kirjautuminen ei onnistu pelkästään salasanalla.
    statusCode = ajaxStatus("HEAD", "/login/:/:password");
    equal(statusCode, 400, "Kirjautuminen ei onnistu pelkästään salasanalla.");
    
    // Kirjautuminen ei onnistu virheellisillä tunnuksilla.
    statusCode = ajaxStatus("HEAD", "/login/:asdf/:asdf");
    equal(statusCode, 400, "Kirjautuminen ei onnistu virheellisillä tunnuksilla.");
    
    // Kirjautuminen ei onnistu oikeilla tunnuksilla virheellisessä muodossa.
    statusCode = ajaxStatus("HEAD", "/login/user/password");
    equal(statusCode, 400, "Kirjautuminen ei onnistu oikeilla tunnuksilla virheellisessä muodossa. (1/3)");
    statusCode = ajaxStatus("HEAD", "/login/:user/password");
    equal(statusCode, 400, "Kirjautuminen ei onnistu oikeilla tunnuksilla virheellisessä muodossa. (2/3)");
    statusCode = ajaxStatus("HEAD", "/login/user/:password");
    equal(statusCode, 400, "Kirjautuminen ei onnistu oikeilla tunnuksilla virheellisessä muodossa. (3/3)");
    
    // Kirjautuminen ei onnistu, kun tunnuksena on salasana ja salasanana tunnus.
    statusCode = ajaxStatus("HEAD", "/login/:password/:user");
    equal(statusCode, 400, "Kirjautuminen ei onnistu, kun tunnuksena on salasana ja salasanana tunnus.");
});

test("Login-test", function() {
    /* = Perustestit =========================================================*/
    
    // /login-test on olemassa.
    var req = ajax("OPTIONS", "/login-test");
    equal(status(req), 200, "/login-test on olemassa.");
    
    // /login-test hyväksyy vain HEAD-metodin.
    var methods = header("Allow", req).split(", ").sort();
    deepEqual(methods, ["HEAD"], "/login-test hyväksyy vain HEAD-metodin.");
});

test("Logout", function() {
    /* = Perustestit =========================================================*/
    
    // /logout on olemassa.
    var req = ajax("OPTIONS", "/logout");
    equal(status(req), 200, "/logout on olemassa.");
    
    // /logout hyväksyy vain HEAD-metodin.
    var methods = header("Allow", req).split(", ").sort();
    deepEqual(methods, ["HEAD"], "/logout hyväksyy vain HEAD-metodin.");
});

test("Login-[Login-Test]-Logout", function() {
    // /logout ei kaadu, vaikka ei oltukaan kirjautuneena.
    var statusCode = ajaxStatus("HEAD", "/logout");
    equal(statusCode, 204, "/logout ei kaadu, vaikka ei oltukaan kirjautuneena.");
    
    // /login-test ilmoittaa ennen kirjautumista, että ei olla kirjautuneena.
    var loggedIn = ajaxHeader("Logged-in", "HEAD", "/login-test");
    strictEqual(loggedIn, false, "/login-test ilmoittaa ennen kirjautumista, että ei olla kirjautuneena.");
    
    // /login-test ei kaadu (1/2).
    statusCode = ajaxStatus("HEAD", "/login-test");
    equal(statusCode, 204, "/login-test ei kaadu. (1/2)");
    
    // Kirjautuminen onnistuu kelvollisilla tunnuksilla.
    statusCode = ajaxStatus("HEAD", "/login/:user/:password");
    equal(statusCode, 204, "Kirjautuminen onnistuu kelvollisilla tunnuksilla.");
    
    // /login-test ilmoittaa sisäänkirjautumisen jälkeen, että ollaan kirjautuneena.
    loggedIn = ajaxHeader("Logged-in", "HEAD", "/login-test");
    strictEqual(loggedIn, true, "/login-test ilmoittaa sisäänkirjautumisen jälkeen, että ollaan kirjautuneena.");
    
    // /login-test ei kaadu (2/2).
    statusCode = ajaxStatus("HEAD", "/login-test");
    equal(statusCode, 204, "/login-test ei kaadu. (2/2)");
    
    // /logout kirjaa käyttäjän ulos.
    loggedIn = ajaxHeader("Logged-in", "HEAD", "/logout");
    strictEqual(loggedIn, false, "/logout kirjaa käyttäjän ulos.");
    
    // /login-test ilmoittaa uloskirjautumisen jälkeen, että ei olla kirjautuneena.
    loggedIn = ajaxHeader("Logged-in", "HEAD", "/login-test");
    strictEqual(loggedIn, false, "/login-test ilmoittaa uloskirjautumisen jälkeen, että ei olla kirjautuneena.");
});