<?php
class Admins extends MySqlSchema
{
    function __construct()
    {
        parent::__construct(
            "admins",
            new Column("username", ColumnProp::VaryingChars, true, 50),
            new Column("password", ColumnProp::VaryingChars, true, 100),
            new Column("last_name", ColumnProp::VaryingChars, true, 100),
            new Column("first_name", ColumnProp::VaryingChars, true, 100)
        );
    }
    public $username;
    public $password;
    public $last_name;
    public $first_name;
    function HasAdmin() : bool
    {
        if ($this->Count("username") != 0)
        {
            return true;
        }
        return false;
    }
}
?>