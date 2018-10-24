<?php
Model::AddSchema("Products");
class ProductsModel extends Model
{   
    public $Id;
    public $PageTitle;
    function Validate() : iterable
    {
        if ($this->Put())
        {
            yield "Id" => $this->CheckInput("Id", true, Type::Numeric);
            if (HasValue($this->Id))
            {
                $products = new Products();
                $products->id = $this->Id;
                if (!$products->Exists())
                {
                    yield "Id" => GetMessage("IdDoesNotExist", $this->Id);
                }
            }
        }
    }
}
?>