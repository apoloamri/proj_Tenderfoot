<?php
Model::AddSchema("Users");
class SessionModel extends Model
{   
    public $session;
    public $loggedIn = false;
    public $username;
    public $password;
    function Validate() : iterable
    {
        if ($this->Post())
        {
            yield "username" => $this->CheckInput("username", true);
            yield "password" => $this->CheckInput("password", true);
            yield "session" => $this->CheckInput("session", true);
            if (HasValue($this->username) && 
                HasValue($this->password) &&
                HasValue($this->session))
            {
                $users = new Users();
                $users->str_username = $this->username;
                $users->str_password = $this->password;
                if ($users->Count() != 1)
                {
                    yield "username" => GetMessage("InvalidUsernamePassword");
                }
                $sessions = new Sessions();
                $sessions->str_session_id = $this->session;
                yield "session" => $sessions->ValidateSession();
            }
        }
    }
    function Map() : void
    {
        $sessions = new Sessions();
        $sessions->str_session_id = $this->session;
        $this->session = $sessions->GetSession();
        $this->loggedIn = HasValue($sessions->str_session_key);
    }
    function Handle() : void 
    {
        $users = new Users();
        $users->str_username = $this->username;
        $users->str_password = $this->password;
        $users->SelectSingle();
        $sessions = new Sessions();
        $sessions->str_session_key = $users->str_username;
        $sessions->Where("str_session_id", DB::Equal, $this->session);
        $sessions->Update();
    }
}
?>