<?php
class Assets extends Schema
{
    function __construct()
    {
        parent::__construct("assets");
    }
    public $id;
    public $asset_tag = "";
    public $model = "";
    public $status = "";
    public $asset_name = "";
}
?>