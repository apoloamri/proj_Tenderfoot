<?php
class Logs extends MySqlSchema
{
    function __construct()
    {
        parent::__construct("logs");
    }
    public $str_admin_user;
    public $str_action;
    public $str_code;
    function LogAction() : void
    {
        $session = GetSession("admin");
        if (HasValue($session->SessionKey))
        {
            $this->str_admin_user = $session->SessionKey;
            $this->Insert();   
        }
    }
}
class Action
{
    const LogIn = "Logged In";
    const LogOut = "Logged Out";
    const Created = "Created";
    const Updated = "Updated";
    const Deleted = "Deleted";
    const Increased = "Increased Inventory";
    const Decreased = "Decreased Inventory";
}
?>