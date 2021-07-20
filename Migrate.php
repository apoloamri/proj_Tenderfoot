<?php
$migration = new Migration();
$migration->Migrate("Admins");
$migration->Migrate("Users");

$admin1 = new stdClass();
$admin1->id = 1;
$admin1->username = "admin";
$admin1->password = "admin";
$admin1->last_name = "Administrator";
$admin1->first_name = "Tenderfoot";
$migration->Seed("Admins", $admin1);

$admin1 = new stdClass();
$admin1->id = 2;
$admin1->username = "admin2";
$admin1->password = "admin2";
$admin1->last_name = "Administrator2";
$admin1->first_name = "Tenderfoot2";
$migration->Seed("Admins", $admin1);

$user1 = new stdClass();
$user1->id = 1;
$user1->username = "user1";
$user1->password = "user1";
$user1->email_address = "market_app@mailinator.com";
$user1->store_name = "MarketApp";
$migration->Seed("Users", $user1);
?>