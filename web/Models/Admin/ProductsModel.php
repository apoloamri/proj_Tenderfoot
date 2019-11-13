<?php
Model::AddSchema("Products");
class ProductsModel extends Model
{   
    public $Id;
    public $PageTitle;
    function Validate() : iterable
    {
        if (_::HasValue($this->Id))
        {
            $products = new Products();
            if (!$products->IdExists($this->Id))
            {
                yield "Id" => GetMessage("IdDoesNotExist", $this->Id);
            }
        }
    }
}
?>