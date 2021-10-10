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

function dump($value) {

	echo '<pre>';
	var_dump($value);
	echo '</pre>';

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

    echo str_replace('[OUTPUT]', $errorOutput, $handlerInterface);
}

function fatalHandler($exception) {

	$error = error_get_last();
    if ( $error["type"] == E_ERROR )
        errorHandler( $error["type"], $error["message"], $error["file"], $error["line"] );
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

function title() {

	echo config('app.name');

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