<?php
class BaseBackOfficeController extends Controller
{
    function __construct()
    {
        $this->Environment = "BackOffice";
    }

    function CheckAuth() : bool 
    {
        $this->Initiate("AuthorizeModel", "BackOffice");
        $this->Execute(Http::Get);
        return $this->Model->IsValid;
    }

    function CheckAuthRedirect() : void
    {
        if (!$this->CheckAuth())
        {
            $this->Redirect("/backoffice/login");
        }
    }

    function CheckAuthNotFound() : void
    {
        if (!$this->CheckAuth())
        {
            $this->Redirect("/err/404");
            die();
        }
    }
}
?>