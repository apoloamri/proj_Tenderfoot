<?php
Model::AddSchema("Users");
class UsersModel extends Model
{   
    public $username;
    public $password;
    public $last_name;
    public $first_name;
    public $Result;
    public function Validate()
    {
        if ($this->Post())
        {
            yield "username" => $this->Required("username");
            yield "password" => $this->Required("password");
            yield "last_name" => $this->Required("last_name");
            yield "first_name" => $this->Required("first_name");
        }
    }
    public function Map()
    {
        $users = new Users();
        if (!IsNullOrEmpty($this->username))
        {
            $users->AddWhere("str_username", $this->username);
        }
        $this->Result = $users->Select("str_username", "str_last_name", "str_first_name");
    }
    public function Handle()
    {
        $users = new Users();
        $users->OverwriteWithModel($this);
        $users->dat_insert_time = Now();
        $users->Insert();
        $this->Result = $users;
    }
}
?>