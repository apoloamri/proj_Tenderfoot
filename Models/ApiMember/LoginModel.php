<?php
Model::AddSchema("Members");
class LoginModel extends Model
{   
    public $Username;
    public $Password;
    public $Token;
    function Validate() : iterable
    {
        if ($this->Post())
        {
            yield "username" => $this->CheckInput("Username", true);
            yield "password" => $this->CheckInput("Password", true);
            if ($this->IsValid("Username", "Password"))
            {
                $members = new Members();
                $members->username = $this->Username;
                $members->password = $this->Password;
                if (!$members->HasUsernamePassword())
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
        $sessions->name = $this->Username;
        $this->Token = $sessions->New();
    }
}
?>