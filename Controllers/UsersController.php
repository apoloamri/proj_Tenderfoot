<?php
class UsersController extends Controller
{
    function GetUsers() : void
    {
        $this->Initiate("UsersModel");
		$this->Execute("GET");
		$this->Json("Result");
	}
	
	function PostUsers() : void
	{
		$this->Initiate("UsersModel");
		$this->Execute("POST");
		$this->Json("Result");
	}
}
?>