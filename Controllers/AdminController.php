<?php
require_once "BaseAdminController.php";
class AdminController extends BaseAdminController
{
    function Index() : void
    {   
        $this->CheckAuthRedirect();
        $this->Initiate();
		$this->Execute("GET");
        $this->View("index");
    }

    function Login() : void
    {
        $this->Initiate();
		$this->Execute("GET");
        $this->View("login");
    }

    function Products() : void
    {
        $this->CheckAuthRedirect();
        $this->Initiate();
		$this->Execute("GET");
        $this->View("products");
    }

    function ProductsAdd() : void
    {
        $this->CheckAuthRedirect();
        $this->Initiate();
        $this->Execute("GET");
        $this->Model->PageTitle = "Add Product";
        $this->View("products_add");
    }

    function ProductsEdit() : void
    {
        $this->CheckAuthRedirect();
        $this->Initiate();
        $this->Execute("GET");
        $this->Model->PageTitle = "Edit Product";
        $this->View("products_add");
    }
    
    function ApiPostLogin() : void 
    {
        $this->Initiate("LoginModel");
		$this->Execute("POST");
        $this->Json();
    }

    function ApiPostLogout() : void
    {
        $this->Initiate("LogoutModel");
		$this->Execute("POST");
        $this->Json();
    }
}
?>