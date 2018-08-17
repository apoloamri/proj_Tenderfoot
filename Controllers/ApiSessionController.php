<?php
class ApiSessionController extends Controller
{
    public function GetSession()
    {   
        $this->Initiate("SessionModel");
		$this->Execute("GET");
		$this->Json("session");
    }
}
?>