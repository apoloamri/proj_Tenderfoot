<?php
class Settings
{
	public static function GetSettings($tag)
	{
		$settings = parse_ini_file(".settings.ini");
		return $settings[$tag];
	}
	//Database
	public static function Host() { return self::GetSettings("Host"); }
    public static function Port() { return self::GetSettings("Port"); }
    public static function Database() { return self::GetSettings("Database"); }
    public static function User() { return self::GetSettings("User"); }
	public static function Password() { return self::GetSettings("Password"); }
	public static function ConnectionString()
	{
		return 
			"host=".self::Host()." ".
			"port=".self::Port()." ".
			"dbname=".self::Database()." ".
			"user=".self::User()." ".
			"password=".self::Password()." ";
	}
	//Site
	public static function SiteUrl() { return self::GetSettings("SiteUrl"); }
	public static function SiteUrlSSL() { return self::GetSettings("SiteUrlSSL"); }
}
function GetMessage($tag, $value)
{
	$message = parse_ini_file(".messages.ini");
	return sprintf($message[$tag], $value);
}
?>