<?php
class TestModel extends Model
{   
    public $Prop1;
    public $Prop2;
    public $Prop3 = false;
    public $Prop4;
    function Map() : void
    {
        $this->Prop1 = "Test";
        $this->Prop2 = array();
        $object = new stdClass();
        $object->Name = "Paolo";
        $object->Number = "99";
        $this->Prop2[] = $object;
        $object = new stdClass();
        $object->Name = "Mari";
        $object->Number = "88";
        $this->Prop2[] = $object;
        $this->Prop3 = true;
        $this->Prop4 = $object;
    }
}
?>