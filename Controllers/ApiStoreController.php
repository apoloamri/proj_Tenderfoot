<?php
require_once "BaseAdminController.php";
class ApiStoreController extends BaseAdminController
{
    function GetStore() : void
    {
        $this->Initiate("StoreModel");
        $this->Execute(Http::Get);
        $this->Json("Store");
    }

    function GetStoreTrending() : void
    {
        $this->Initiate("StoreTrendingModel");
        $this->Execute(Http::Get);
        $this->Json("Result", "PageCount");
    }

    function PostStoreHeader() : void
    {
        $this->CheckAuthNotFound();
        $this->Initiate("StoreHeaderModel");
        $this->Execute(Http::Post);
        $this->Json();
    }

    function DeleteStoreHeader() : void
    {
        $this->CheckAuthNotFound();
        $this->Initiate("StoreHeaderModel");
        $this->Execute(Http::Delete);
        $this->Json();
    }
}
?>