function urlPrefix() {
    var length = window.location.href.length - window.location.pathname.length;
    return window.location.href.substr(0, length);
}

function ajax(method, url) {
    var req = new XMLHttpRequest();
    req.open(method, urlPrefix() + url, false);
    req.send(null);
    
    return req;
}

function status(ajax) {
    return ajax.status;
}

function header(header, ajax) {
    return ajax.getResponseHeader(header);
}

function ajaxStatus(method, url) {
    var req = ajax(method, url);
    return status(req);
}

function ajaxHeader(headerName, method, url) {
    var req = ajax(method, url);
    return header(headerName, req);
}