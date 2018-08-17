<?php
class ApiShopController extends Controller
{
    public function Items()
    {   
        $this->Initiate("ItemsModel");
		$this->Execute("GET");
		$this->Json("result");
    }
}
?>