<?php
use Steampixel\Route;
require '../vendor/autoload.php';

const ROUTE_URL_INDEX = 'https://xxxxxxxx.ngrok.io/service2';
const ROUTE_URL_LOGIN = ROUTE_URL_INDEX . '/login';
const ROUTE_URL_CALLBACK = ROUTE_URL_INDEX . '/callback';
const ROUTE_URL_LOGOUT = ROUTE_URL_INDEX . '/logout';

$auth0 = new \Auth0\SDK\Auth0([
    'domain' => 'AUTH0_DOMAIN',
    'clientId' => 'AUTH0_SERVICE2_CLIENT_ID',
    'clientSecret' => 'AUTH0_SERVICE2_CLIENT_SECRET',
    'cookieSecret' => 'test',
]);

Route::add('/service2', function() use ($auth0) {
    $session = $auth0->getCredentials();
    if ($session === null) {
        echo '<p>Please <a href="' . ROUTE_URL_LOGIN . '">log in</a>.</p>';
        return;
    }

    echo '<pre>';
    print_r($session->user);
    echo '</pre>';

    echo '<p>You can now <a href="' . ROUTE_URL_LOGOUT . '">log out</a>.</p>';
});

Route::add('/service2/login', function() use ($auth0) {
    $auth0->clear();
    header("Location: " . $auth0->login(ROUTE_URL_CALLBACK));
});

Route::add('/service2/callback', function() use ($auth0) {
    $auth0->exchange(ROUTE_URL_CALLBACK);
    header("Location: " . ROUTE_URL_INDEX);
});

Route::add('/service2/logout', function() use ($auth0) {
    header("Location: " . $auth0->logout(ROUTE_URL_INDEX));
});

Route::run('/');