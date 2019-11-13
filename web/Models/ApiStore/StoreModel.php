<?php
Model::AddSchema("Store");
class StoreModel extends Model
{   
    public $Store;
    function Map() : void
    {
        $store = new Store();
        $this->Store = $store->SelectSingle();
    }
}
?>