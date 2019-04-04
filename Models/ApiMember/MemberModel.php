<?php
Model::AddSchema("Members");
class MemberModel extends Model
{   
    public $Username;
    public $Password;
    public $EmailAddress;
    public $StoreName;
    public $Member;
    function Validate() : iterable
    {
        if ($this->Post())
        {
            yield "username" => $this->CheckInput("Username", true);
            yield "password" => $this->CheckInput("Password", true);
            yield "emailAddress" => $this->CheckInput("EmailAddress", true);
            yield "storeName" => $this->CheckInput("StoreName", true);
            if ($this->IsValid("Username", "Password", "EmailAddress", "StoreName"))
            {
                $members = new Members();
                $members->username = $this->Username;
                $members->email_address = $this->EmailAddress;
                if ($members->HasUsername())
                {
                    yield "username" => GetMessage("AlreadyExists", $this->Username);
                }
                if ($members->HasEmailAddress())
                {
                    yield "emailAddress" => GetMessage("AlreadyExists", $this->EmailAddress);
                }
            }
        }
    }
    function Map() : void
    {
        $members = new Members();
        $members->username = $this->SessionName;
        $members->SelectSingle();
        $this->Member = Obj::Select($members, "username", "email_address", "store_name");
    }
    function Handle() : void
    {
        $members = new Members();
        $members->username = $this->Username;
        $members->password = $this->Password;
        $members->email_address = $this->EmailAddress;
        $members->store_name = $this->StoreName;
        $members->Insert();
    }
}
?>