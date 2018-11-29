<?php
require_once "BaseAdminController.php";
class ApiOrdersController extends BaseAdminController
{
    function GetOrders() : void 
    {
        $this->CheckAuthNotFound();
        $this->Initiate("OrderModel");
        $this->Execute(Http::Get);
        $this->Json("Result", "CartItems", "PageCount");
    }

    function PostOrders() : void 
    {
        $this->Initiate("OrderModel");
        $this->Execute(Http::Post);
        $this->Json("OrderNumber");
    }

    function PutOrders() : void 
    {
        $this->CheckAuthNotFound();
        $this->Initiate("OrderModel");
        $this->Execute(Http::Put);
        $this->Json();
    }

    function DeleteOrders() : void 
    {
        $this->CheckAuthNotFound();
        $this->Initiate("OrderModel");
        $this->Execute(Http::Delete);
        $this->Json();
    }
}
?>