<?php
Model::AddSchema("Admins");
Model::AddSchema("Logs");
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
            if ($this->IsValid("Username", "Password"))
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
            if (GetSession("admin")->SessionKey == null)
            {
                yield "Session" => GetMessage("InvalidAccess");
            }
        }
    }
    function Handle() : void 
    {
        $logs = new Logs();
        $sessions = new Sessions();
        if ($this->Post())
        {
            $admins = new Admins();
            $admins->str_username = $this->Username;
            $admins->str_password = $this->Password;
            $admins->SelectSingle();
            SetSession($admins->str_username, "admin");
            $logs->str_action = Action::LogIn;
            $logs->LogAction();
        }
        else if ($this->Delete())
        {
            $sessions->str_session_key = GetSession("admin")->SessionKey;
            $sessions->Delete();
            $logs->str_action = Action::LogOut;
            $logs->LogAction();
            DeleteSession("admin");
        }
    }
}
?>