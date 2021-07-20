<?php
Model::AddSchema("Users");
class LoginModel extends Model
{   
    public $username;
    public $password;
    public $token;
    function Validate() : iterable
    {
        if ($this->Post())
        {
            yield "username" => $this->CheckInput("username", true);
            yield "password" => $this->CheckInput("password", true);
            if ($this->IsValid("username", "password"))
            {
                $users = new Users();
                if (!$users->HasUsernamePassword($this->username, $this->password))
                {
                    yield "username" => GetMessage("InvalidUsernamePassword");
                    yield "password" => GetMessage("InvalidUsernamePassword");
                }
            }
        }
    }
    function Handle() : void
    {
        $sessions = new Sessions();
        $this->token = $sessions->New($this->username);
    }
}
?>