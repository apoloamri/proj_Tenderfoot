<?php
class MarketViewController extends Controller
{
    function Login() : void
    {
        $this->Initiate("Login", "Market");
        $this->Execute(Http::Get);
        $this->View("market/login");
    }

    function Dashboard() : void
    {
        $this->Initiate();
        $this->Execute(Http::Get);
        $this->View("market/dashboard");
    }
}
?>