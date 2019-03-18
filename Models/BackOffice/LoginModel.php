<?php
Model::AddSchema("Admins");
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
            if (Session::Get("backoffice")->SessionKey == null)
            {
                yield "session" => GetMessage("InvalidAccess");
            }
        }
    }
    function Handle() : void 
    {
        $sessions = new Sessions();
        if ($this->Post())
        {
            $admins = Obj::Overwrite(new Admins(), $this);
            $admins->SelectSingle();
            Session::Set($admins->username, "backoffice");
        }
        else if ($this->Delete())
        {
            $sessions->session_key = Session::Get("backoffice")->SessionKey;
            $sessions->Delete();
            Session::Delete("admin");
        }
    }
}
?>