<?php
require_once "BaseAdminController.php";
class ApiOrdersController extends BaseAdminController
{
    function GetOrders() : void 
    {
        $this->CheckAuthNotFound();
        $this->Initiate("OrderModel");
        $this->Execute("GET");
        $this->Json("Result", "CartItems", "PageCount");
    }

    function PostOrders() : void 
    {
        $this->Initiate("OrderModel");
        $this->Execute("POST");
        $this->Json("OrderNumber");
    }

    function PutOrders() : void 
    {
        $this->CheckAuthNotFound();
        $this->Initiate("OrderModel");
        $this->Execute("PUT");
        $this->Json();
    }
}
?>