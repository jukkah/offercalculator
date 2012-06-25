// Testit tiedostolle /terminal/commands/account.php.

module("Account commands");

test("Account", function() {
    /* = Perustestit =========================================================*/
    
    // /account on olemassa.
    var req = ajax("OPTIONS", "/account");
    equal(status(req), 200, "/account on olemassa.");
    
    // /account hyväksyy vain PUT-, GET-, PATCH- ja DELETE-metodit.
    var methods = header("Allow", req).split(", ").sort();
    deepEqual(methods, ["DELETE","GET", "PATCH", "PUT"], "/account hyväksyy vain PUT-, GET-, PATCH- ja DELETE-metodit.");
});

test("PUT Account", function() {
    
});

test("GET Account", function() {
    
});

test("PATCH Account", function() {
    
});

test("DELETE Account", function() {
    
});