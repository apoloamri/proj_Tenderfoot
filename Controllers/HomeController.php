<?php
class HomeController extends Controller
{
    public function Index()
    {   
        $this->View("index");
    }

    public function Item()
    {   
        $this->Initiate("ItemModel");
		$this->Execute("GET");
        $this->View("item");
    }
}
?>