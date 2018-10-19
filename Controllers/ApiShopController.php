<?php
class ApiShopController extends Controller
{
    function Items() : void
    {   
        $this->Initiate("ItemsModel");
		$this->Execute("GET");
		$this->Json("result");
    }

    function GetCart() : void
    {
        $this->Initiate("CartModel");
		$this->Execute("GET");
		$this->Json("result", "count", "total");
    }

    function AddCart() : void
    {
        $this->Initiate("CartModel");
		$this->Execute("POST");
		$this->Json();
    }

    function DeleteCart() : void
    {
        $this->Initiate("CartModel");
		$this->Execute("DELETE");
		$this->Json();
    }
}
?>