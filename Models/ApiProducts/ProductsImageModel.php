<?php
class ProductsImageModel extends Model
{   
    public $ImageFile;
    public $ImagePath;
    function Validate() : iterable
    {
        yield "ImageFile" => $this->CheckInput("ImageFile", true, Type::Image);
    }
    function Handle() : void
    {
        $this->ImagePath = $this->SaveTempFile($this->ImageFile);
    }
}
?>