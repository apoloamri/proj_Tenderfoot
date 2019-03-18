<?php
require_once "BaseBackOfficeController.php";
class BackOfficeController extends BaseBackOfficeController
{
    function Index() : void
    {   
        $this->CheckAuthRedirect();
        $this->Initiate();
		$this->Execute(Http::Get);
        $this->View("index");
    }

    function Login() : void
    {
        $this->Initiate();
		$this->Execute(Http::Get);
        $this->View("login");
    }

    function LoginPost() : void
    {
        $this->Initiate("LoginModel");
		$this->Execute(Http::Post);
        $this->Json("messages");
    }

    function LoginDelete() : void
    {
        $this->Initiate("LoginModel");
		$this->Execute(Http::Delete);
        $this->Json("messages");
    }
}
?>