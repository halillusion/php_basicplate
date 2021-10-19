<?php

/**
 * Path
 * @return string $path main path
 */

function path($dir  = null) {
	return ROOT_PATH . $dir;
}


/**
 * Base URL
 * @return string $base main url
 */

function base($body = null) {

	$url = (config('settings.ssl') ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . '/';
	if ($body) $url .= trim(strip_tags($body), '/');

	return $url;
}

/**
 * Configuration
 * @param  string $setting setting value name
 * @return string $return  setting value
 */

function config($setting) {

	// TEMP
	if ($setting == 'settings.default_user_role') {
		return 1;
	}

	$return = false;
	if (strpos($setting, '.') !== false) {
		
		$setting = explode('.', $setting, 2);

		if ($setting[0] == 'settings') { // JSON


			$file = path() . 'app/core/config/' . $setting[0] . '.json';
			if (file_exists($file)) {

				$settings = json_decode(file_get_contents($file));
				if ($settings AND isset($settings->{$setting[1]}) !== false) {

					$return = $settings->{$setting[1]};
				}
			}

		} else { // PHP

			$file = path('app/core/config/' . $setting[0] . '.php');
			if (file_exists($file)) {

				$settings = require $file;
				$setting = strpos($setting[1], '.') !== false ? explode('.', $setting[1]) : [$setting[1]];
				
				$data = null;
				foreach ($setting as $key) {
					
					if (isset($settings[$key]) !== false) {
						$data = $settings[$key];
						$settings = $settings[$key];
					} else {
						$data = null;
					}
				}
				$return = $data;
			}

		}


	}
	
	return $return;
}

function dump($value, $exit = false) {

	echo '<pre>';
	var_dump($value);
	echo '</pre>';

	if ($exit) exit;

}

function view($page) {

	echo $page;
}

function errorHandler(int $errNo, string $errMsg, string $file, int $line) {

	$handlerInterface = '
        <!doctype html>
        <html>
            <head>
                <meta charset="utf-8">
                <title>Error Handler | PHP Basicplate</title>
                <style>
                body {
                  font-family: monospace, system-ui;
                  background: #151515;
                  color: #b2b2b2;
                  padding: 1rem;
                }
                h1 {
                    margin: 0;
                    color: #fff;
                }
                h2 {
                    margin: 0;
                    color: #777;
                }
                </style>
            </head>
            <body>
                <h1>PHP Basicplate</h1>
                <h2>Error Handler</h2>
                <pre>[OUTPUT]</pre>
            </body>
        </html>
    ';

    $errorOutput = '    '.$file.':'.$line.' - '.$errMsg.' <strong>('.$errNo.')</strong>';
    if (! config('app.dev_mode')) http(500);
    echo str_replace('[OUTPUT]', $errorOutput, $handlerInterface);
}

function fatalHandler() {

	$error = error_get_last();
    if ( ! is_null($error) AND is_array($error) AND $error["type"] == E_ERROR ) {
        errorHandler( $error["type"], $error["message"], $error["file"], $error["line"] );
    }
}

function exceptionHandler(Exception $e ) {

	echo '<pre>';
	var_dump($e->getMessage());
	echo '</pre>';

	exit();
}

function lang($key) {

	global $languages;

	if (is_array($languages)) {

		$get = strpos($key, '.') !== false ? explode('.', $key, 2) : [$key];
		
		if (isset($languages[$get[0]]) !== false) {

			if (isset($get[1]) !== false AND isset($languages[$get[0]][$get[1]]) !== false) {

				$key = $languages[$get[0]][$get[1]];

			} elseif (is_string($languages[$get[0]])) {

				$key = $languages[$get[0]];

			}

		}

	}

	return $key;

}

function title($echo = true) {

	global $title;

	$return = ($title ? $title . ' | ' : '') . config('app.name');

	if ($echo) echo $return;
	else return $return;

}


function meta() {

	echo '<!-- META -->';

}

function urlGenerator($key = null, $slugs = [], $getParams = []) {

	return base($key);

}

/**
 * Assets File Controller
 * @param string $filename
 * @param bool $version
 * @param bool $tag
 * @param bool $echo
 * @param array $externalParameters
 * @return string|null
 */

function assets(string $filename, $version = true, $tag = false, $echo = false, $externalParameters = [])
{

	$fileDir = rtrim( path().'assets/'.$filename, '/' );
	$return = trim( base().'assets/'.$filename, '/' );
	if (file_exists( $fileDir )) {

		$return = $version==true ? $return.'?v='.filemtime($fileDir) : $return;
		if ( $tag==true ) // Only support for javascript and stylesheet files
		{
			$_externalParameters = '';
			foreach ($externalParameters as $param => $val) {
				$_externalParameters = ' ' . $param . '="' . $val . '"';
			}

			$file_data = pathinfo( $fileDir );
			if ( $file_data['extension'] == 'css' )
			{
				$return = '<link'.$_externalParameters.' rel="stylesheet" href="'.$return.'" type="text/css"/>'.PHP_EOL.'		';

			} elseif ( $file_data['extension'] == 'js' )
			{
				$return = '<script'.$_externalParameters.' src="'.$return.'"></script>'.PHP_EOL.'		';
			}
		}

	} else {
		$return = null;
		// new app\core\Log('sys_asset', $filename);
	}

	if ( $echo == true ) {

		echo $return;
		return null;

	} else {
		return $return;
	}
}

function createCSRF($echo = true) {

    $token = md5($_COOKIE[config('app.session')].'_'.getIP().'_'.getHeader());
    $_SESSION['token'] = $token;

    $return = '<input type="hidden" name="token" value="' . encryptKey($token) . '" />';

    if ($echo) echo $return;
    else return $return;

}

function verifyCSRF($val) {

    $token = md5($_COOKIE[config('app.session')].'_'.getIP().'_'.getHeader());

    if (isset($_SESSION['token']) !== false AND $_SESSION['token'] === $token AND $token === decryptKey($val)) {

        return true;

    } else {

        return false;

    }

}

/**
 * Create Header Definition
 * @param string|int $code
 * @param null $data
 * @param null $extra
 */

function http($code, $data = null, $extra = null) {

	switch ($code)
	{
		case 'powered_by':
			header('X-Powered-By: Kalipso Build Engine');
			break;

		case 301:
			header($_SERVER["SERVER_PROTOCOL"] . ' 301 Moved Permanently');
			if (! is_null($data)) {
				header('Location: '.$data);
				exit;
			}
			break;

		case 200:
			header($_SERVER["SERVER_PROTOCOL"] . ' 200 OK');
			if (! is_null($data)) {
				header('Location: '.$data);
				exit;
			}
			break;

		case 401:
			header($_SERVER["SERVER_PROTOCOL"] . ' 401 Unauthorized');
			if (! is_null($data))
			{
				echo $data;
				exit;
			}
			break;

		case 403:
			header($_SERVER["SERVER_PROTOCOL"] . ' 403 Forbidden');
			if (!is_null($data)) {
				echo $data;
				exit;
			}
			break;

		case 404:
			header($_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
			if (!is_null($data)) {
				echo $data;
				exit;
			}
			break;

		case 410:
			header($_SERVER["SERVER_PROTOCOL"] . ' 410 Gone');
			if (!is_null($data)) {
				echo $data;
				exit;
			}
			break;

		case 500:
			header($_SERVER["SERVER_PROTOCOL"] . ' 500 Internal Server Error');
			if (!is_null($data)) {
				echo $data;
				exit;
			}
			break;

		case 'refresh':
			if (is_null($data['url'])) {

				if (isset($_SERVER['HTTP_REFERER']) !== false) {
					$data['url'] = $_SERVER['HTTP_REFERER'];
				} else {
					$data['url'] = base();
				}
			}
			header('refresh:'.$data['second'].'; url='.$data['url'] );
			break;

		case 'location':
			header('Location: '.$data );
			exit;

		case 'content_type':
			$charset = config('app.charset');
			if (! is_null($extra)) {

				header('Content-Type: '.$extra.'; Charset='.$charset);
				echo $data;

			} else {

				switch ($data) {
					case 'application/javascript':
					case 'js': $ctype = 'application/javascript'; break;

					case 'application/zip':
					case 'zip': $ctype = 'application/zip'; break;

					case 'text/plain':
					case 'txt': $ctype = 'text/plain'; break;

					case 'text/xml':
					case 'xml': $ctype = 'application/xml'; break;

					case 'vcf': $ctype = 'text/x-vcard'; break;

					default: $ctype = 'text/html'; break;
				}
				header('Content-Type: '.$ctype.'; Charset='.$charset);
			}
			break;

		default: break;
	}
}

/**
 * Generate a Token
 * @param int $length
 * @return string
 */

function tokenGenerator($length = 120): string
{

	$key = '';
	list($usec, $sec) = explode(' ', microtime());
	mt_srand((float) $sec + ((float) $usec * 100000));

	$inputs = array_merge(range('z','a'),range(0,9),range('A','Z'));

	for($i=0; $i<$length; $i++)
	{
		$key .= $inputs[mt_rand(0,61)];
	}
	return $key;
}

/**
 * Get IP Address
 * @return string
 */

function getIP(): string
{

	if (getenv("HTTP_CLIENT_IP")) $ip = getenv("HTTP_CLIENT_IP");
	elseif (getenv("HTTP_X_FORWARDED_FOR"))
	{
		$ip = getenv("HTTP_X_FORWARDED_FOR");
		if (strpos($ip, ','))
		{
			$tmp = explode(',', $ip);
			$ip = trim($tmp[0]);
		}
	}
	else $ip = getenv("REMOTE_ADDR");

	if ($ip=='::1') $ip = '127.0.0.1';

	return $ip;
}


/**
 * Get User Agent Header
 * @return string
 */

function getHeader(): string
{
	return isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'none';
}

/**
 * Format File Size
 * @param $bytes
 * @return string
 */

function formatSize($bytes): string
{

	if ($bytes >= 1073741824) $bytes = number_format($bytes / 1073741824, 2) . ' GB';
	elseif ($bytes >= 1048576) $bytes = number_format($bytes / 1048576, 2) . ' MB';
	elseif ($bytes >= 1024) $bytes = number_format($bytes / 1024, 2) . ' KB';
	elseif ($bytes > 1) $bytes = $bytes.' '.lang('byte').lang('plural_suffix');
	elseif ($bytes == 1) $bytes = $bytes.' '.lang('byte');
	else $bytes = '0 '.lang('byte');

	return $bytes;
}

/**
 * Get User Device Details
 * @param ?string $ua
 * @return array
 */

function userAgentDetails($ua = null): array
{

	$ua = $ua==null ? getHeader() : $ua;
	$browser = '';
	$platform = '';
	$b_icon = 'mdi mdi-close-circle';
	$p_icon = 'mdi mdi-close-circle';

	$browser_list = [
		'Trident\/7.0'			=> ['Internet Explorer 11','mdi mdi-internet-explorer'],
		'MSIE'					=> ['Internet Explorer','mdi mdi-internet-explorer'],
		'Edge'					=> ['Microsoft Edge','mdi mdi-microsoft-edge-legacy'],
		'Edg'					=> ['Microsoft Edge','mdi mdi-microsoft-edge'],
		'Internet Explorer'		=> ['Internet Explorer','mdi mdi-internet-explorer'],
		'Beamrise'				=> ['Beamrise','mdi mdi-earth'],
		'Opera'					=> ['Opera','mdi mdi-opera'],
		'OPR'					=> ['Opera','mdi mdi-opera'],
		'Vivaldi'				=> ['Vivaldi','mdi mdi-earth'],
		'Shiira'				=> ['Shiira','mdi mdi-earth'],
		'Chimera'				=> ['Chimera','mdi mdi-earth'],
		'Phoenix'				=> ['Phoenix','mdi mdi-earth'],
		'Firebird'				=> ['Firebird','mdi mdi-earth'],
		'Camino'				=> ['Camino','mdi mdi-earth'],
		'Netscape'				=> ['Netscape','mdi mdi-earth'],
		'OmniWeb'				=> ['OmniWeb','mdi mdi-earth'],
		'Konqueror'				=> ['Konqueror','mdi mdi-earth'],
		'icab'					=> ['iCab','mdi mdi-earth'],
		'Lynx'					=> ['Lynx','mdi mdi-earth'],
		'Links'					=> ['Links','mdi mdi-earth'],
		'hotjava'				=> ['HotJava','mdi mdi-earth'],
		'amaya'					=> ['Amaya','mdi mdi-earth'],
		'MiuiBrowser'			=> ['MIUI Browser','mdi mdi-earth'],
		'IBrowse'				=> ['IBrowse','mdi mdi-earth'],
		'iTunes'				=> ['iTunes','mdi mdi-earth'],
		'Silk'					=> ['Silk','mdi mdi-earth'],
		'Dillo'					=> ['Dillo','mdi mdi-earth'],
		'Maxthon'				=> ['Maxthon','mdi mdi-earth'],
		'Arora'					=> ['Arora','mdi mdi-earth'],
		'Galeon'				=> ['Galeon','mdi mdi-earth'],
		'Iceape'				=> ['Iceape','mdi mdi-earth'],
		'Iceweasel'				=> ['Iceweasel','mdi mdi-earth'],
		'Midori'				=> ['Midori','mdi mdi-earth'],
		'QupZilla'				=> ['QupZilla','mdi mdi-earth'],
		'Namoroka'				=> ['Namoroka','mdi mdi-earth'],
		'NetSurf'				=> ['NetSurf','mdi mdi-earth'],
		'BOLT'					=> ['BOLT','mdi mdi-earth'],
		'EudoraWeb'				=> ['EudoraWeb','mdi mdi-earth'],
		'shadowfox'				=> ['ShadowFox','mdi mdi-earth'],
		'Swiftfox'				=> ['Swiftfox','mdi mdi-earth'],
		'Uzbl'					=> ['Uzbl','mdi mdi-earth'],
		'UCBrowser'				=> ['UCBrowser','mdi mdi-earth'],
		'Kindle'				=> ['Kindle','mdi mdi-earth'],
		'wOSBrowser'			=> ['wOSBrowser','mdi mdi-earth'],
		'Epiphany'				=> ['Epiphany','mdi mdi-earth'],
		'SeaMonkey'				=> ['SeaMonkey','mdi mdi-earth'],
		'Avant Browser'			=> ['Avant Browser','mdi mdi-earth'],
		'Chrome'				=> ['Google Chrome','mdi mdi-google-chrome'],
		'CriOS'					=> ['Google Chrome','mdi mdi-google-chrome'],
		'Safari'				=> ['Safari','mdi mdi-apple-safari'],
		'Firefox'				=> ['Firefox','mdi mdi-firefox'],
		'Mozilla'				=> ['Mozilla','mdi mdi-firefox']
	];

	$platform_list = [
		'windows'				=> ['Windows','mdi mdi-microsoft-windows'],
		'iPad'					=> ['iPad','mdi mdi-apple'],
		'iPod'					=> ['iPod','mdi mdi-apple'],
		'iPhone'				=> ['iPhone','mdi mdi-apple'],
		'mac'					=> ['Apple MacOS','mdi mdi-apple'],
		'android'				=> ['Android','mdi mdi-android'],
		'linux'					=> ['Linux','mdi mdi-linux'],
		'Nokia'					=> ['Nokia','mdi mdi-microsoft'],
		'BlackBerry'			=> ['BlackBerry','mdi mdi-blackberry'],
		'FreeBSD'				=> ['FreeBSD','mdi mdi-freebsd'],
		'OpenBSD'				=> ['OpenBSD','mdi mdi-linux'],
		'NetBSD'				=> ['NetBSD','mdi mdi-linux'],
		'UNIX'					=> ['UNIX','mdi mdi-mouse'],
		'DragonFly'				=> ['DragonFlyBSD','mdi mdi-linux'],
		'OpenSolaris'			=> ['OpenSolaris','mdi mdi-linux'],
		'SunOS'					=> ['SunOS','mdi mdi-linux'],
		'OS\/2'					=> ['OS/2','mdi mdi-mouse'],
		'BeOS'					=> ['BeOS','mdi mdi-mouse'],
		'win'					=> ['Windows','mdi mdi-windows'],
		'Dillo'					=> ['Linux','mdi mdi-linux'],
		'PalmOS'				=> ['PalmOS','mdi mdi-mouse'],
		'RebelMouse'			=> ['RebelMouse','mdi mdi-mouse']
	];

	foreach($browser_list as $pattern => $name) {
		if ( preg_match("/".$pattern."/i",$ua, $match)) {
			$b_icon = $name[1];
			$browser = $name[0];
			$known = ['Version', $pattern, 'other'];
			$pattern_version = '#(?<browser>' . join('|', $known).')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
			preg_match_all($pattern_version, $ua, $matches);
			//if (!preg_match_all($pattern_version, $ua, $matches)) {
			//}
			$i = count($matches['browser']);
			if ($i != 1) {
				if (strripos($ua,"Version") < strripos($ua,$pattern)){
					$version = @$matches['version'][0];
				}
				else {
					$version = @$matches['version'][1];
				}
			}
			else {
				$version = @$matches['version'][0];
			}
			break;
		}
	}

	foreach($platform_list as $key => $platform) {
		if (stripos($ua, $key) !== false) {
			$p_icon = $platform[1];
			$platform = $platform[0];
			break;
		}
	}

	if ($browser=='') {
		$browser = lang('undetected');
	}
	if ($platform=='') {
		$platform = lang('undetected');
	}

	$os_array = [
		'/windows nt 10/i'      =>  'Windows 10',
		'/windows nt 6.3/i'     =>  'Windows 8.1',
		'/windows nt 6.2/i'     =>  'Windows 8',
		'/windows nt 6.1/i'     =>  'Windows 7',
		'/windows nt 6.0/i'     =>  'Windows Vista',
		'/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
		'/windows nt 5.1/i'     =>  'Windows XP',
		'/windows xp/i'         =>  'Windows XP',
		'/windows nt 5.0/i'     =>  'Windows 2000',
		'/windows me/i'         =>  'Windows ME',
		'/win98/i'              =>  'Windows 98',
		'/win95/i'              =>  'Windows 95',
		'/win16/i'              =>  'Windows 3.11',
		'/macintosh|mac os x/i' =>  'Mac OS X',
		'/mac_powerpc/i'        =>  'Mac OS 9',
		'/linux/i'              =>  'Linux',
		'/ubuntu/i'             =>  'Ubuntu',
		'/iphone/i'             =>  'iPhone',
		'/ipod/i'               =>  'iPod',
		'/ipad/i'               =>  'iPad',
		'/android/i'            =>  'Android',
		'/blackberry/i'         =>  'BlackBerry',
		'/webos/i'              =>  'Mobile'
	];

	foreach ($os_array as $regex => $value)
	{
		if (preg_match($regex, $ua))
		{
			$os_platform = $value;
		}
	}
	$version = empty($version) ? '' : 'v'.$version;
	$os_platform = isset($os_platform) === false ? lang('undetected') : $os_platform;

	return [
		'user_agent'=> $ua,			// User Agent
		'browser'	=> $browser,	// Browser Name
		'version'	=> $version,	// Version
		'platform'	=> $platform,	// Platform
		'os'		=> $os_platform,// Platform Detail
		'b_icon'	=> $b_icon,		// Browser Icon(icon class name like from Material Design Icon)
		'p_icon'	=> $p_icon		// Platform Icon(icon class name like from Material Design Icon)
	];
}

/**
 * Get String to Slug
 * @param $str
 * @param array $options
 * @return string
 */

function slugGenerator($str, $options=[]): string
{

	$str = mb_convert_encoding((string)$str, 'UTF-8', mb_list_encodings());
	$defaults = [
		'delimiter' => '-',
		'limit' => null,
		'lowercase' => true,
		'replacements' => [],
		'transliterate' => true
	];
	$options = array_merge($defaults, $options);
	$char_map = [
		// Latin
		'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'AE',
		'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I',
		'Ð' => 'D', 'Ñ' => 'N', 'Ò' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ő' => 'O',
		'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ű' => 'U', 'Ý' => 'Y', 'Þ' => 'TH',
		'ß' => 'ss',
		'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'ae',
		'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i',
		'ð' => 'd', 'ñ' => 'n', 'ò' => 'o', 'ô' => 'o', 'õ' => 'o', 'ő' => 'o',
		'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ű' => 'u', 'ý' => 'y', 'þ' => 'th',
		'ÿ' => 'y',
		// Latin symbols
		'©' => '(c)',
		// Greek
		'Α' => 'A', 'Β' => 'B', 'Γ' => 'G', 'Δ' => 'D', 'Ε' => 'E', 'Ζ' => 'Z', 'Η' => 'H', 'Θ' => '8',
		'Ι' => 'I', 'Κ' => 'K', 'Λ' => 'L', 'Μ' => 'M', 'Ν' => 'N', 'Ξ' => '3', 'Ο' => 'O', 'Π' => 'P',
		'Ρ' => 'R', 'Σ' => 'S', 'Τ' => 'T', 'Υ' => 'Y', 'Φ' => 'F', 'Χ' => 'X', 'Ψ' => 'PS', 'Ω' => 'W',
		'Ά' => 'A', 'Έ' => 'E', 'Ί' => 'I', 'Ό' => 'O', 'Ύ' => 'Y', 'Ή' => 'H', 'Ώ' => 'W', 'Ϊ' => 'I',
		'Ϋ' => 'Y',
		'α' => 'a', 'β' => 'b', 'γ' => 'g', 'δ' => 'd', 'ε' => 'e', 'ζ' => 'z', 'η' => 'h', 'θ' => '8',
		'ι' => 'i', 'κ' => 'k', 'λ' => 'l', 'μ' => 'm', 'ν' => 'n', 'ξ' => '3', 'ο' => 'o', 'π' => 'p',
		'ρ' => 'r', 'σ' => 's', 'τ' => 't', 'υ' => 'y', 'φ' => 'f', 'χ' => 'x', 'ψ' => 'ps', 'ω' => 'w',
		'ά' => 'a', 'έ' => 'e', 'ί' => 'i', 'ό' => 'o', 'ύ' => 'y', 'ή' => 'h', 'ώ' => 'w', 'ς' => 's',
		'ϊ' => 'i', 'ΰ' => 'y', 'ϋ' => 'y', 'ΐ' => 'i',
		// Turkish
		'Ş' => 'S', 'İ' => 'I', 'Ç' => 'C', 'Ü' => 'U', 'Ö' => 'O', 'Ğ' => 'G',
		'ş' => 's', 'ı' => 'i', 'ç' => 'c', 'ü' => 'u', 'ö' => 'o', 'ğ' => 'g',
		// Russian
		'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D', 'Е' => 'E', 'Ё' => 'Yo', 'Ж' => 'Zh',
		'З' => 'Z', 'И' => 'I', 'Й' => 'J', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O',
		'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C',
		'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sh', 'Ъ' => '', 'Ы' => 'Y', 'Ь' => '', 'Э' => 'E', 'Ю' => 'Yu',
		'Я' => 'Ya',
		'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo', 'ж' => 'zh',
		'з' => 'z', 'и' => 'i', 'й' => 'j', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o',
		'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c',
		'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sh', 'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e', 'ю' => 'yu',
		'я' => 'ya',
		// Ukrainian
		'Є' => 'Ye', 'І' => 'I', 'Ї' => 'Yi', 'Ґ' => 'G',
		'є' => 'ye', 'і' => 'i', 'ї' => 'yi', 'ґ' => 'g',
		// Czech
		'Ď' => 'D', 'Ě' => 'E', 'Ň' => 'N', 'Ř' => 'R', 'Ť' => 'T', 'Ů' => 'U', 'ď' => 'd', 'ě' => 'e',
		'ň' => 'n', 'ř' => 'r', 'ť' => 't', 'ů' => 'u',
		// Polish
		'Ą' => 'A', 'Ć' => 'C', 'Ę' => 'e', 'Ł' => 'L', 'Ń' => 'N', 'Ó' => 'o', 'Ś' => 'S', 'Ź' => 'Z',
		'Ż' => 'Z',
		'ą' => 'a', 'ć' => 'c', 'ę' => 'e', 'ł' => 'l', 'ń' => 'n', 'ó' => 'o', 'ś' => 's', 'ź' => 'z',
		'ż' => 'z',
		// Latvian
		'Ā' => 'A', 'Č' => 'C', 'Ē' => 'E', 'Ģ' => 'G', 'Ī' => 'i', 'Ķ' => 'k', 'Ļ' => 'L', 'Ņ' => 'N',
		'Š' => 'S', 'Ū' => 'u', 'Ž' => 'Z',
		'ā' => 'a', 'č' => 'c', 'ē' => 'e', 'ģ' => 'g', 'ī' => 'i', 'ķ' => 'k', 'ļ' => 'l', 'ņ' => 'n',
		'š' => 's', 'ū' => 'u', 'ž' => 'z'
	];
	$str = preg_replace(array_keys($options['replacements']), $options['replacements'], $str);
	if ($options['transliterate']) {
		$str = str_replace(array_keys($char_map), $char_map, $str);
	}
	$str = preg_replace('/[^\p{L}\p{Nd}]+/u', $options['delimiter'], $str);
	$str = preg_replace('/(' . preg_quote($options['delimiter'], '/') . '){2,}/', '$1', $str);
	$str = mb_substr($str, 0, ($options['limit'] ? $options['limit'] : mb_strlen($str, 'UTF-8')), 'UTF-8');
	$str = trim($str, $options['delimiter']);
	return $options['lowercase'] ? mb_strtolower($str, 'UTF-8') : $str;
}

/**
 * String Transformator
 * @param $type
 * @param $data
 * @param null $lang
 * @return string
 */
function stringTransform($type, $data, $lang = null): string
{

	if (is_null($lang)) {
		$lang = currentLang();
	}

	switch ($type) {
		case 'uppercasewords':
		case 'ucw':
			$data = Transliterator::create("Any-Title")->transliterate($data);
			break;

		case 'uppercasefirst':
		case 'ucf':
			$data = Transliterator::create("Any-Title")->transliterate($data);
			$data = explode(' ', $data);
			if (count($data)>1) {

				$_data = [ 0 => $data[0] ];
				foreach ($data as $index => $text) {

					if ($index) {

						$_data[$index] = stringTransform('l', $text);

					}
				}
				$data = implode(' ', $_data);
			} else {
				$data = implode(' ', $data);
			}
			break;

		case 'lowercase':
		case 'l':
			$data = Transliterator::create("Any-Lower")->transliterate($data);
			break;

		case 'uppercase':
		case 'u':
			$data = Transliterator::create("Any-Upper")->transliterate($data);
			break;

		default:
			break;
	}

	return $data;
}

/**
 * Data Encrypter
 * @param $text
 * @return string
 */
function encryptKey($text): string
{

	$ciphering = "AES-128-CTR";
	$iv_length = openssl_cipher_iv_length($ciphering);
	$options = 0;
	$encryption_iv = '1234567891011121';
	$encryption_key = md5(config('app.name'));
	return openssl_encrypt($text, $ciphering,
		$encryption_key, $options, $encryption_iv);
}

/**
 * Data Decrypter
 * @param $encrypted_string
 * @return string
 */
function decryptKey($encrypted_string): string
{

	$ciphering = "AES-128-CTR";
	$iv_length = openssl_cipher_iv_length($ciphering);
	$decryption_iv = '1234567891011121';
	$options = 0;
	$decryption_key = md5(config('app.name'));
	return openssl_decrypt ($encrypted_string, $ciphering,
		$decryption_key, $options, $decryption_iv);
}

function filter($data=null, $parameter='text')
{
	/*	parameters
		- text - strip_tags - trim
		- html- htmlspecialchars - trim
		- check - strip_tags - trim ? on : off
		- pass - password_hash - trim
	*/
	if (is_array($data))
	{	
		$_value = [];
		foreach ($data as $key => $value)
		{
			if (is_array($value))
			{
				$_value[$key] = filter($value, $parameter);
			}
			else
			{
				$value = str_replace('<p><br></p>', '<br>', $value);
				switch ($parameter)
				{
					case 'html': $_value[$key] = htmlspecialchars(trim($value)); break;
					case 'nulled_html': 
						$_value[$key] = htmlspecialchars(trim($value)); 
						$_value[$key] = $_value[$key] == '' ? null : $_value[$key]; 
						if (trim(strip_tags($value)) == '') {
							$_value[$key] = null;
						}
					break;
					case 'check': $_value[$key] = !is_null($value) ? 'on' : 'off'; break;
					case 'check_as_boolean': $_value[$key] = !is_null($value) ? true : false; break;
					case 'int': $_value[$key] = (integer)$value; break;
					case 'nulled_int': $_value[$key] = (integer)$value == 0 ? null : (integer)$value; break;
					case 'float': $_value[$key] = floatval($value); break;
					case 'pass': $_value[$key] = password_hash(trim($value), PASSWORD_DEFAULT); break;
					case 'nulled_pass': $_value[$key] = trim($value) != '' ? password_hash(trim($value), PASSWORD_DEFAULT) : null; break;
					case 'date': $_value[$key] = strtotime($value. ' 12:00'); break;
					case 'nulled_text': $_value[$key] = strip_tags(trim($value)) == '' ? null : strip_tags(trim($value)); break;
					case 'slug': $_value[$key] = strip_tags(trim($value)) == '' ? null : slugGenerator(strip_tags(trim($value))); break;
					default: $_value[$key] = strip_tags(trim($value)); break;
				}
			}
			
			if (strpos($parameter, 'nulled') !== false AND $_value[$key] == '') {
				$_value[$key] = null;
			}
		}
		$data = $_value;
	}
	else
	{
		$data = str_replace('<p><br></p>', '<br>', $data);
		switch ($parameter)
		{
			case 'html': $data = htmlspecialchars(trim($data)); break;
			case 'nulled_html': 
				$data = htmlspecialchars(trim($data));
				$data = $data == '' ? null : $data; 
				if ($data AND trim(strip_tags(htmlspecialchars_decode($data))) == '') {
					$data = null;
				}
			break;
			case 'check': $data = !is_null($data) ? 'on' : 'off'; break;
			case 'check_as_boolean': $data = !is_null($data) ? true : false; break;
			case 'int': $data = (integer)$data; break;
			case 'nulled_int': $data  = (integer)$data  == 0 ? null : (integer)$data; break;
			case 'float': $data = (float)$data; break;
			case 'pass': $data = password_hash(trim($data), PASSWORD_DEFAULT); break;
			case 'nulled_pass': $data = trim($data) != '' ? password_hash(trim($data), PASSWORD_DEFAULT) : null; break;
			case 'date': $data = strtotime($data. ' 12:00'); break;
			case 'nulled_text': $data = strip_tags(trim($data)) == '' ? null : strip_tags(trim($data)); break;
			case 'slug': $data = strip_tags(trim($data)) == '' ? null : slugGenerator(strip_tags(trim($data))); break;
			default: $data = strip_tags(trim($data)); break;
		}

		if (strpos($parameter, 'nulled') !== false AND $data == '') {
			$data = null;
		}
	}

	return $data;

}

function in($extract, $var): array
{
	$return = [];
	if (is_array($extract) AND is_array($var))
	{
		foreach ($extract as $key => $value)
		{
			if (isset($var[$key])) $return[$key] = filter($var[$key], $value);
			else $return[$key] = filter(null, $value);
		}
	}
	return $return;
}


function out($type, $val) {

	switch ($type) {
		case 'float_with_zero':
		$val = str_replace(['.', ','], ['', '.'], $val);

		if (strpos($val, '.') === false) {
		$val .= '.00';
		}
		break;

		case 'with_comma':
		$val = str_replace(['.', ','], ['', '.'], $val);

		if (strpos($val, '.') === false) {
		$val .= '.00';
		}
		$val = str_replace('.', ',', $val);
		break;

		case 'row_count':
		$val = count(preg_split('/[\n\r]/', $val)) - 1;

		if ($val <= 0) $val = 1;
		break;

		case 'html':
		$val = htmlspecialchars_decode($val);
		break;

		case 'message':
			$val = nl2br($val);
		break;

		default:
		# code...
		break;
	}

	return $val;

}

function auth() {

	global $isLogged;

	if (is_null($isLogged)) {

		$isLogged = (new \app\core\helpers\Session)->isLogged();

	}

	return $isLogged;

}