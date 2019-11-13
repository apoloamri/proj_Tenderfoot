<?php
Model::AddSchema("Store");
class StoreHeaderModel extends Model
{   
    public $ImageFile;
    function Validate() : iterable
    {
        if ($this->Post())
        {
            yield "ImageFile" => $this->CheckInput("ImageFile", true, Type::Image);
        }
    }
    function Handle() : void
    {
        if ($this->Post())
        {
            $tempFile = $this->SaveTempFile($this->ImageFile);
            $fileUrl = $this->SaveFile($tempFile, "site/header");
            $store = new Store();
            $store->str_header = $fileUrl;
            $store->Update();
        }
        else if ($this->Delete())
        {
            $store = new Store();
            $store->str_header = "";
            $store->Update();
        }
    }
}
?>