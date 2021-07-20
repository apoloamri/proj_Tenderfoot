<?php
$router = new Routing();
$router->Map("GET /market/login", "MarketView", "Login");
$router->Map("GET /market/dashboard", "MarketView", "Dashboard");
$router->Map("GET /api/v1/market/userinfo", "MarketApiV1", "UserInfo");
$router->Map("POST /api/v1/market/login", "MarketApiV1", "Login");

if (Settings::Migrate())
{
    $router->Map("GET /command/update_database", "", "Migrate.php");
}

$router->Map("*", "Errors", "Error404");
?>