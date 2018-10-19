<?php
class ApiSessionController extends Controller
{
    function GetSession() : void
    {   
        $this->Initiate("SessionModel");
		$this->Execute("GET");
		$this->Json("session", "loggedIn");
    }

    function PostSession() : void 
    {
        $this->Initiate("SessionModel");
		$this->Execute("POST");
		$this->Json("session", "loggedIn");
    }
}
?>