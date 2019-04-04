<?php
class Members extends MySqlSchema
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
    function HasUsername() : bool
    {
        $members = new Members();
        $members->username = $this->username;
        if ($members->Exists("username"))
        {
            return true;
        }
        return false;
    }
    function HasEmailAddress() : bool
    {
        $members = new Members();
        $members->email_address = $this->email_address;
        if ($members->Exists("username"))
        {
            return true;
        }
        return false;
    }
    function HasUsernamePassword() : bool
    {
        $members = new Members();
        $members->username = $this->username;
        $members->password = $this->password;
        if ($members->Exists("username"))
        {
            return true;
        }
        return false;
    }
    function GetWithUsernamePassword() : void
    {
        $members = new Members();
        $members->username = $this->username;
        $members->password = $this->password;
        if ($members->ExistsOne("username"))
        {
            $members->SelectSingle();
            Obj::Overwrite($this, $members);
        }
    }
}
?>