<?php
require_once "BaseAdminController.php";
class ApiProductsController extends BaseAdminController
{
    function GetProducts() : void
    {
        $this->Initiate("ProductsModel");
		$this->Execute(Http::Get);
        $this->Json("Result", "PageCount");
    }

    function GetTags() : void
    {
        $this->Initiate("TagsModel");
		$this->Execute(Http::Get);
        $this->Json("Result");
    }

    function GetTrends() : void
    {
        $this->Initiate("TrendsModel");
		$this->Execute(Http::Get);
        $this->Json("MostViews", "MostCarts", "MostPurchases");
    }

    function PostProducts() : void
    {
        $this->CheckAuthNotFound();
        $this->Initiate("ProductsModel");
        $this->Execute(Http::Post);
        $this->Json("Id");
    }

    function PostProductsImage() : void
    {
        $this->CheckAuthNotFound();
        $this->Initiate("ProductsImageModel");
        $this->Execute(Http::Post);
        $this->Json("ImagePath");
    }

    function PostTagsImage() : void
    {
        $this->CheckAuthNotFound();
        $this->Initiate("TagsModel");
        $this->Execute(Http::Post);
        $this->Json();
    }

    function PutProducts() : void
    {
        $this->CheckAuthNotFound();
        $this->Initiate("ProductsModel");
        $this->Execute(Http::Put);
        $this->Json();
    }

    function PutTagsImage() : void
    {
        $this->CheckAuthNotFound();
        $this->Initiate("TagsModel");
        $this->Execute(Http::Put);
        $this->Json();
    }

    function DeleteProducts() : void
    {
        $this->CheckAuthNotFound();
        $this->Initiate("ProductsModel");
        $this->Execute(Http::Delete);
        $this->Json();
    }

    function DeleteTags() : void
    {
        $this->CheckAuthNotFound();
        $this->Initiate("TagsModel");
        $this->Execute(Http::Delete);
        $this->Json();
    }

    function PutInventory() : void
    {
        $this->CheckAuthNotFound();
        $this->Initiate("InventoryModel");
        $this->Execute(Http::Delete);
        $this->Json();
    }
}
?>