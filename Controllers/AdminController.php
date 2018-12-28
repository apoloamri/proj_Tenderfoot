<?php
require_once "BaseAdminController.php";
class AdminController extends BaseAdminController
{
    function __construct()
    {
        $this->Environment = "admin";
    }

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

    function Orders() : void
    {
        $this->CheckAuthRedirect();
        $this->Initiate();
		$this->Execute(Http::Get);
        $this->View("orders");
    }

    function OrdersDetail() : void
    {
        $this->CheckAuthRedirect();
        $this->Initiate("OrdersModel");
        if (!$this->Model->IsValid)
        {
            $this->Redirect("/error/404");
        }
		$this->Execute(Http::Get);
        $this->View("orders_detail");
    }

    function Products() : void
    {
        $this->CheckAuthRedirect();
        $this->Initiate();
		$this->Execute(Http::Get);
        $this->View("products");
    }

    function ProductsAdd() : void
    {
        $this->CheckAuthRedirect();
        $this->Initiate("ProductsModel");
        $this->Execute(Http::Get);
        $this->Model->PageTitle = "Add Product";
        $this->View("products_add");
    }

    function ProductsEdit() : void
    {
        $this->CheckAuthRedirect();
        $this->Initiate("ProductsModel");
        if (!$this->Model->IsValid)
        {
            $this->Redirect("/error/404");
        }
        $this->Execute(Http::Get);
        $this->Model->PageTitle = "Edit Product (ID: ".$this->Model->Id.")";
        $this->View("products_add");
    }

    function ProductsTags() : void
    {
        $this->CheckAuthRedirect();
        $this->Initiate();
        $this->Execute(Http::Get);
        $this->View("products_tags");
    }

    function Store() : void
    {
        $this->CheckAuthRedirect();
        $this->Initiate();
        $this->Execute(Http::Get);
        $this->View("store");
    }
    
    function ApiPostLogin() : void 
    {
        $this->Initiate("LoginModel");
		$this->Execute(Http::Post);
        $this->Json();
    }

    function ApiDeleteLogin() : void
    {
        $this->CheckAuthNotFound();
        $this->Initiate("LoginModel");
		$this->Execute(Http::Delete);
        $this->Json();
    }
}
?>