<?php
class BaseAdminController extends Controller
{
    function CheckAuth() : bool 
    {
        $this->Initiate("CheckAuthModel", "Admin");
        $this->Execute("GET");
        return $this->Model->IsValid;
    }

    function CheckAuthRedirect() : void
    {
        if (!$this->CheckAuth())
        {
            $this->Redirect("/admin/login");
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