<?php
require_once "BaseAdminController.php";
class ProductsController extends BaseAdminController
{
    function GetProducts() : void
    {
        $this->Initiate("ProductsModel");
		$this->Execute("GET");
        $this->Json("Result", "ImagePaths", "PageCount");
    }

    function PostProducts() : void
    {
        $this->CheckAuthNotFound();
        $this->Initiate("ProductsModel");
        $this->Execute("POST");
        $this->Json();
    }

    function PostProductsImage() : void
    {
        $this->CheckAuthNotFound();
        $this->Initiate("ProductsImageModel");
        $this->Execute("POST");
        $this->Json("ImagePath");
    }

    function PutProducts() : void
    {
        $this->CheckAuthNotFound();
        $this->Initiate("ProductsModel");
        $this->Execute("PUT");
        $this->Json();
    }

    function DeleteProducts() : void
    {
        $this->CheckAuthNotFound();
        $this->Initiate("ProductsModel");
        $this->Execute("DELETE");
        $this->Json();
    }

    function PutInventory() : void
    {
        $this->CheckAuthNotFound();
        $this->Initiate("InventoryModel");
        $this->Execute("DELETE");
        $this->Json();
    }
}
?>