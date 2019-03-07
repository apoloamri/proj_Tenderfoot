<?php
Model::AddSchema("Admins");
// Model::AddSchema("Logs");
class LoginModel extends Model
{   
    public $username;
    public $password;
    function Validate() : iterable
    {
        if ($this->Post())
        {
            yield "username" => $this->CheckInput("username", true);
            yield "password" => $this->CheckInput("password", true);
            if ($this->IsValid("username", "password"))
            {
                $admins = Obj::Overwrite(new Admins(), $this);
                if (!$admins->HasAdmin())
                {
                    yield "username" => GetMessage("InvalidUsernamePassword");
                }
            }
        }
        if ($this->Delete())
        {
            if (GetSession("admin")->SessionKey == null)
            {
                yield "session" => GetMessage("InvalidAccess");
            }
        }
    }
    function Handle() : void 
    {
        // $logs = new Logs();
        $sessions = new Sessions();
        if ($this->Post())
        {
            $admins = Obj::Overwrite(new Admins(), $this);
            $admins->SelectSingle();
            SetSession($admins->username, "admin");
            // $logs->action = Action::LogIn;
            // $logs->LogAction();
        }
        else if ($this->Delete())
        {
            $sessions->session_key = GetSession("admin")->SessionKey;
            $sessions->Delete();
            // $logs->str_action = Action::LogOut;
            // $logs->LogAction();
            DeleteSession("admin");
        }
    }
}
?>