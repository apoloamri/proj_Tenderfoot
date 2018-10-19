<?php
class HomeController extends Controller
{
    function Index() : void
    {   
        $this->View("index");
    }

    function Item() : void
    {   
        $this->Initiate("ItemModel");
		$this->Execute("GET");
        $this->View("item");
    }

    function Cart() : void
    {
        $this->View("cart");
    }
}
?>