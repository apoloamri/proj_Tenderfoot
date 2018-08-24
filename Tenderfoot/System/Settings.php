<?php
class Settings
{
	public static function GetSettings($tag)
	{
		$settings = parse_ini_file(".settings.ini");
		return $settings[$tag];
	}
	//Database
	public static function Host() : string { return self::GetSettings("Host"); }
    public static function Port() : string { return self::GetSettings("Port"); }
    public static function Database() : string { return self::GetSettings("Database"); }
    public static function User() : string { return self::GetSettings("User"); }
	public static function Password() : string { return self::GetSettings("Password"); }
	public static function Migrate() : bool { return self::GetSettings("Migrate"); }
	public static function ConnectionString() : string
	{
		return 
			"host=".self::Host()." ".
			"port=".self::Port()." ".
			"dbname=".self::Database()." ".
			"user=".self::User()." ".
			"password=".self::Password()." ";
	}
	//Site
	public static function Session() : string { return self::GetSettings("Session"); }
	public static function SiteUrl() : string { return self::GetSettings("SiteUrl"); }
	public static function SiteUrlSSL() : string { return self::GetSettings("SiteUrlSSL"); }
}
function GetMessage(string $tag, string $value = "") : string
{
	$message = parse_ini_file(".messages.ini");
	return sprintf($message[$tag], $value);
}
?>