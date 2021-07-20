<?php
class MarketApiV1Controller extends Controller
{
    function Login() : void
    {
        $this->Initiate("Login", "Market");
		$this->Execute(Http::Post);
        $this->Json("token");
    }

    function UserInfo() : void
    {
        $this->Authenticate();
        $this->Initiate("UserInfo", "Market");
		$this->Execute(Http::Get);
        $this->Json("userInfo");
    }
}
?>