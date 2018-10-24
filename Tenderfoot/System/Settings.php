<?php
class Settings
{
	private static function InitializeSettings(string $settings) : array
	{
		if (!array_key_exists($settings, $GLOBALS))
		{
			$cachedSettings = parse_ini_file($settings);
			$GLOBALS[$settings] = $cachedSettings;
			return $cachedSettings;
		}
		else
		{
			return $GLOBALS[$settings];
		}
	}
	static function GetSettings(string $tag)
	{
		$settings;
		if ($tag == "Deploy")
		{
			$settings = self::InitializeSettings(".settings.ini");
			return $settings[$tag];
		}
		else
		{
			$deployment = self::Deploy();
			$settings = self::InitializeSettings(".settings.$deployment.ini");
			if (array_key_exists($tag, $settings))
			{
				return $settings[$tag];
			}
			else
			{
				$settings = self::InitializeSettings(".settings.ini");
				return $settings[$tag];
			}
		}
	}
	//Deployment
	static function Deploy() : string { return self::GetSettings("Deploy"); }
	//Database
	static function Host() : string { return self::GetSettings("Host"); }
    static function Port() : string { return self::GetSettings("Port"); }
    static function Database() : string { return self::GetSettings("Database"); }
    static function User() : string { return self::GetSettings("User"); }
	static function Password() : string { return self::GetSettings("Password"); }
	static function Migrate() : bool { return self::GetSettings("Migrate"); }
	static function ConnectionString() : string
	{
		return 
			"host=".self::Host()." ".
			"port=".self::Port()." ".
			"dbname=".self::Database()." ".
			"user=".self::User()." ".
			"password=".self::Password()." ";
	}
	//Site
	static function FilePath() : string { return self::GetSettings("FilePath"); }
	static function FilePathTemp() : string { return self::GetSettings("FilePathTemp"); }
	static function Session() : string { return self::GetSettings("Session"); }
	static function SiteUrl() : string { return self::GetSettings("SiteUrl"); }
	static function SiteUrlSSL() : string { return self::GetSettings("SiteUrlSSL"); }
}
function GetMessage(string $tag, string $value = "") : string
{
	$message = parse_ini_file(".messages.ini");
	if (array_key_exists($tag, $message))
	{
		return sprintf($message[$tag], $value);	
	}
	return $tag;
}
?>