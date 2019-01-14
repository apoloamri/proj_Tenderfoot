<?php
$router = new Routing();

//AdminController
$router->Map("GET /admin", "Admin", "Index");
$router->Map("GET /admin/login", "Admin", "Login");
$router->Map("GET /admin/orders", "Admin", "Orders");
$router->Map("GET /admin/orders/detail/[Id]", "Admin", "OrdersDetail");
$router->Map("GET /admin/products", "Admin", "Products");
$router->Map("GET /admin/products/add", "Admin", "ProductsAdd");
$router->Map("GET /admin/products/edit/[Id]", "Admin", "ProductsEdit");
$router->Map("GET /admin/products/tags", "Admin", "ProductsTags");
$router->Map("GET /admin/store", "Admin", "Store");
$router->Map("GET /admin/api/checkauth", "Admin", "ApiGetCheckAuth");
$router->Map("POST /admin/api/login", "Admin", "ApiPostLogin");
$router->Map("DELETE /admin/api/logout", "Admin", "ApiDeleteLogin");

//FrontController
$router->Map("GET /", "Front", "Index");
$router->Map("GET /cart", "Front", "Cart");
$router->Map("GET /detail/[Code]", "Front", "Detail");
$router->Map("GET /order", "Front", "Order");
$router->Map("GET /tracking/[OrderNumber]", "Front", "Tracking");

//ApiCartController
$router->Map("GET /api/cart", "ApiCart", "GetCart");
$router->Map("POST /api/cart", "ApiCart", "PostCart");
$router->Map("PUT /api/cart", "ApiCart", "PutCart");
$router->Map("DELETE /api/cart", "ApiCart", "DeleteCart");

//ApiCartController
$router->Map("GET /api/logs", "ApiLogs", "GetLogs");

//ApiOrderController
$router->Map("GET /api/orders", "ApiOrders", "GetOrders");
$router->Map("POST /api/orders", "ApiOrders", "PostOrders");
$router->Map("PUT /api/orders", "ApiOrders", "PutOrders");
$router->Map("DELETE /api/orders", "ApiOrders", "DeleteOrders");

//ApiProductsController
$router->Map("GET /api/products", "ApiProducts", "GetProducts");
$router->Map("GET /api/products/tags", "ApiProducts", "GetTags");
$router->Map("GET /api/products/trends", "ApiProducts", "GetTrends");
$router->Map("POST /api/products", "ApiProducts", "PostProducts");
$router->Map("POST /api/products/image", "ApiProducts", "PostProductsImage");
$router->Map("POST /api/products/tags/image", "ApiProducts", "PostTagsImage");
$router->Map("PUT /api/products/", "ApiProducts", "PutProducts");
$router->Map("PUT /api/products/inventory", "ApiProducts", "PutInventory");
$router->Map("PUT /api/products/tags/image", "ApiProducts", "PutTagsImage");
$router->Map("DELETE /api/products/", "ApiProducts", "DeleteProducts");
$router->Map("DELETE /api/products/tags", "ApiProducts", "DeleteTags");

//ApiStoreController
$router->Map("GET /api/store", "ApiStore", "GetStore");
$router->Map("GET /api/store/trending", "ApiStore", "GetStoreTrending");
$router->Map("POST /api/store/header", "ApiStore", "PostStoreHeader");
$router->Map("DELETE /api/store/header", "ApiStore", "DeleteStoreHeader");

$router->Map("GET /api/test", "Test", "Test");

//Errors
$router->Map("GET /err/404", "Errors", "Error404");
$router->Map("*", "Errors", "Error404");
?>