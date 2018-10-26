<?php
Model::AddSchema("Admins");
class LoginModel extends Model
{   
    public $Username;
    public $Password;
    function Validate() : iterable
    {
        if ($this->Post())
        {
            yield "Username" => $this->CheckInput("Username", true);
            yield "Password" => $this->CheckInput("Password", true);
            $hasValues = 
                HasValue($this->Username) && 
                HasValue($this->Password);
            if ($hasValues)
            {
                $admins = new Admins();
                $admins->str_username = $this->Username;
                $admins->str_password = $this->Password;
                if (!$admins->HasAdmin())
                {
                    yield "Username" => GetMessage("InvalidUsernamePassword");
                }
            }
        }
        if ($this->Delete())
        {
            if (!array_key_exists("admin.session_key", $_SESSION))
            {
                yield "Session" => GetMessage("InvalidAccess");
            }
        }
    }
    function Handle() : void 
    {
        $sessions = new Sessions();
        if ($this->Post())
        {
            $admins = new Admins();
            $admins->str_username = $this->Username;
            $admins->str_password = $this->Password;
            $admins->SelectSingle();
            $sessions->str_session_key = "admin_".$admins->str_username;
            $_SESSION["admin.session_key"] = $sessions->str_session_key;
            $_SESSION["admin.session_id"] = $sessions->GetSession();
        }
        if ($this->Delete())
        {
            $sessions->str_session_key = $_SESSION["admin.session_key"];
            $sessions->Delete();
            session_unset("admin.session_key");
            session_unset("admin.session_id");
        }
    }
}
?>