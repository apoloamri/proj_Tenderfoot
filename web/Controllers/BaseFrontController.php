<?php
class BaseFrontController extends Controller
{
    function StartSession() : void
    {
        $this->Initiate("SessionModel", "Front");
        $this->Execute(Http::Get);
    }
}
?>