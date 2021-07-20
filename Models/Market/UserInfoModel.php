<?php
Model::AddSchema("Users");
class UserInfoModel extends Model
{   
    public $userInfo;
    function Map() : void
    {
        $users = new Users();
        $users->username = $this->SessionName;
        $this->userInfo = $users->SelectSingle("username", "email_address", "store_name");
    }
}
?>