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

function base() {

	$url = (config('settings.ssl') ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . '/';

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


			$file = path() . 'app/config/' . $setting[0] . '.json';
			if (file_exists($file)) {

				$settings = json_decode(file_get_contents($file));
				if ($settings AND isset($settings->{$setting[1]}) !== false) {

					$return = $settings->{$setting[1]};
				}
			}

		} else { // PHP

			$file = path() . 'app/config/' . $setting[0] . '.php';
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