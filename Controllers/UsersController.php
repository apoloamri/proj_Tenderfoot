<?php
class UsersController extends Controller
{
    public function GetUsers()
    {
        $this->Initiate("UsersModel");
		$this->Execute("GET");
		$this->Json("Result");
	}
	
	public function PostUsers()
	{
		$this->Initiate("UsersModel");
		$this->Execute("POST");
		$this->Json("Result");
	}
}
?>