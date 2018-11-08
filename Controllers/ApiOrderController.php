<?php
require_once "ApiCartController.php";
class ApiOrderController extends ApiCartController
{
    function PostOrder() : void 
    {
        $this->Initiate("OrderModel", "ApiOrder");
        $this->Execute("POST");
        $this->Json();
    }
}
?>