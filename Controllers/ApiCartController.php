<?php
require_once "BaseFrontController.php";
class ApiCartController extends BaseFrontController
{
    function GetCart(bool $showJson = true) : void
    {
        $this->StartSession();
        $this->Initiate("CartModel", "ApiCart");
        $this->Execute("GET");
        if ($showJson)
        {
            $this->Json("Result", "Count", "Total");
        }
    }

    function PostCart() : void
    {
        $this->StartSession();
        $this->Initiate("CartModel");
		$this->Execute("POST");
		$this->Json();
    }

    function DeleteCart() : void
    {
        $this->StartSession();
        $this->Initiate("CartModel");
		$this->Execute("DELETE");
		$this->Json();
    }
}
?>