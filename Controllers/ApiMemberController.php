<?php
class ApiMemberController extends Controller
{
    function MemberInfo() : void
    {
        $this->Authenticate();
        $this->Initiate("Member");
		$this->Execute(Http::Get);
        $this->Json("member");
    }

    function Register() : void
    {
        $this->Initiate("Member");
		$this->Execute(Http::Post);
        $this->Json();
    }

    function Login() : void
    {
        $this->Initiate("Login");
		$this->Execute(Http::Post);
        $this->Json("token");
    }
}
?>