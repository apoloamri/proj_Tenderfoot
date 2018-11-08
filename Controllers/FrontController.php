<?php
require_once "BaseFrontController.php";
class FrontController extends BaseFrontController
{
    function Index() : void
    {   
        $this->StartSession();
        $this->View("index");
    }

    function Detail() : void
    {   
        $this->StartSession();
        $this->Initiate("DetailModel");
        if (!$this->Model->IsValid)
        {
            $this->Redirect("/err/404");
        }
		$this->Execute("GET");
        $this->View("detail");
    }

    function Cart() : void
    {
        $this->StartSession();
        $this->View("cart");
    }

    function Order() : void 
    {
        $this->StartSession();
        $this->Initiate("OrderModel", "ApiOrder");
        if (!$this->Model->IsValid)
        {
            $this->Redirect("/err/404");
        }
        $this->View("order");
    }
}
?>