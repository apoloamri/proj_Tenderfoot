<?php
class TestController extends Controller
{
    function Test() : void
    {
        $this->Initiate("TestModel");
        $this->Execute(Http::Get);
        $this->View("TestCompile");
    }
}
?>