<?php
$router = new Routing();
$router->Map("GET /api/users", "Users", "GetUsers");
$router->Map("POST /api/users", "Users", "PostUsers");
$router->Map("*", "Errors", "Error404");
?>