<?php
Model::AddSchema("Items");
class ItemModel extends Model
{   
    public $code;
    public $result;
    public function Validate()
    {
        yield "code" => $this->Required("code");
    }
    public function Map()
    {
        $items = new Items();
        $items->AddWhere("str_code", $this->code);
        $this->result = $items->SelectSingle();
    }
}
?>