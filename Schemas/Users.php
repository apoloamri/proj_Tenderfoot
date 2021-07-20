<?php
class Users extends MySqlSchema
{
    function __construct()
    {
        parent::__construct(
            new Column("username", ColumnProp::VaryingChars, true, 50),
            new Column("password", ColumnProp::VaryingChars, true, 255),
            new Column("email_address", ColumnProp::VaryingChars, true, 255),
            new Column("store_name", ColumnProp::VaryingChars, true, 255)
        );
    }
    public $username;
    public $password;
    public $email_address;
    public $store_name;
    function HasUsernamePassword(string $username, string $password) : bool
    {
        $this->username = $username;
        $this->password = $password;
        return $this->Exists("username");
    }
}
?>