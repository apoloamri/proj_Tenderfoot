<?php
class HomeController extends Controller
{
    function Index() : void
    {   
        $this->View("index");
    }

    function Detail() : void
    {   
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
        $this->View("cart");
    }
}
?>