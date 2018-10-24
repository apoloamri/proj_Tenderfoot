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
    }
    function Handle() : void 
    {
        $admins = new Admins();
        $admins->str_username = $this->Username;
        $admins->str_password = $this->Password;
        $admins->SelectSingle();
        $sessions = new Sessions();
        $sessions->str_session_key = "admin_".$admins->str_username;
        $_SESSION["admin.session_key"] = $sessions->str_session_key;
        $_SESSION["admin.session_id"] = $sessions->GetSession();
    }
}
?>