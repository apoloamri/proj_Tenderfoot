<?php
require_once "BaseFrontController.php";
class ApiCartController extends BaseFrontController
{
    function GetCart(bool $showJson = true) : void
    {
        $this->StartSession();
        $this->Initiate("CartModel", "ApiCart");
        $this->Execute(Http::Get);
        if ($showJson)
        {
            $this->Json("Result", "Count", "Total");
        }
    }

    function PostCart() : void
    {
        $this->StartSession();
        $this->Initiate("CartModel");
		$this->Execute(Http::Post);
		$this->Json();
    }

    function PutCart() : void
    {
        $this->StartSession();
        $this->Initiate("CartModel");
		$this->Execute(Http::Put);
		$this->Json();
    }

    function DeleteCart() : void
    {
        $this->StartSession();
        $this->Initiate("CartModel");
		$this->Execute(Http::Delete);
		$this->Json();
    }
}
?>