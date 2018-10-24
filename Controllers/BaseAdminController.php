<?php
class BaseAdminController extends Controller
{
    function ApiGetCheckAuth(bool $show = true) : void 
    {
        $this->Initiate("CheckAuthModel", "Admin");
        $this->Execute("GET");
        if ($show)
        {
            $this->Json();
        }
    }

    function CheckAuth() : bool 
    {
        $this->ApiGetCheckAuth(false);
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
            http_response_code(400);
            die();
        }
    }
}
?>