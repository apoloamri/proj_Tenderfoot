<?php
$router = new Routing();

$router->Map("GET /", "Client", "Index");
$router->Map("GET /api/v1/member", "ApiMember", "MemberInfo");

$router->Map("POST /api/v1/member/login", "ApiMember", "Login");
$router->Map("POST /api/v1/member/register", "ApiMember", "Register");

if (Settings::Migrate())
{
    $router->Map("GET /command/update_database", "", "Migrate.php");
}

$router->Map("*", "Errors", "Error404");
?>