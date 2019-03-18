<?php
$router = new Routing();
$router->Map("GET /backoffice", "BackOffice", "Index");
$router->Map("GET /backoffice/login", "BackOffice", "Login");
$router->Map("POST /api/v1/login/backoffice", "BackOffice", "LoginPost");
$router->Map("DELETE /api/v1/login/backoffice", "BackOffice", "LoginDelete");
if (Settings::Migrate())
{
    $router->Map("GET /command/update_database", "", "Migrate.php");
}
$router->Map("GET /err/404", "Errors", "Error404");
$router->Map("*", "Errors", "Error404");
?>