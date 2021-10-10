<?php
	
$output = '';
$action = '';
$title = 'Welcome to Sandbox';

if (isset($_GET['action']) !== false) {

	$action = $_GET['action'];

	if ($action == 'licences') { // Licences

		$output = '';
		$title = 'Credits';

		$licences = [
			'PHP Basicplate by Kalipso Collective <a target="_blank" href="https://github.com/halillusion/php_basicplate">(Github)</a>' => 'MIT License

Copyright (c) 2021 KalipsoCollective

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.',
			'PDOx by İzni Burak <a target="_blank" href="https://github.com/izniburak/pdox">(Github)</a>' => 'The MIT License (MIT)

Copyright (c) 2015, İzni Burak Demirtaş info@burakdemirtas.org

Permission is hereby granted, free of charge, to any person obtaining a copy 
of this software and associated documentation files (the "Software"), to deal 
in the Software without restriction, including without limitation the rights 
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell 
copies of the Software, and to permit persons to whom the Software is 
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all 
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR 
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, 
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE 
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER 
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, 
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE 
SOFTWARE.',
		];

		foreach($licences as $name => $licence) {

			$output .= '<details><summary>'.$name.'</summary><pre>'.$licence.'</pre></details>';

		}

	} elseif ($action == 'php-info') {

		$title = 'PHP Info';

		ob_start ();
		phpinfo ();
		$output = ob_get_clean();
		$output = preg_replace('/(<script[^>]*>.+?<\/script>|<style[^>]*>.+?<\/style>|<meta[^>]*>|<title[^>]*>.+?<\/title>)/is', "", $output);
		$output = '<pre>'.trim(strip_tags($output)).'</pre>';

	}

}

?>
<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<title><?php echo $title.' - '.config('app.name'); ?></title>
	<?php echo assets('css/basic.css', true, true); ?>

</head>
<body class="basicplate-front">
	<nav>
		<a href="/sandbox">
			<svg width="193" height="193" viewBox="0 0 193 193" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M90.7598 1.37769C94.4352 -0.144721 98.5648 -0.144719 102.24 1.37769L159.703 25.1794C163.378 26.7018 166.298 29.6219 167.821 33.2973L191.622 90.7598C193.145 94.4352 193.145 98.5648 191.622 102.24L167.821 159.703C166.298 163.378 163.378 166.298 159.703 167.821L102.24 191.622C98.5648 193.145 94.4352 193.145 90.7597 191.622L33.2973 167.821C29.6219 166.298 26.7018 163.378 25.1794 159.703L1.37769 102.24C-0.144721 98.5648 -0.144719 94.4352 1.37769 90.7597L25.1794 33.2973C26.7018 29.6219 29.6219 26.7018 33.2973 25.1794L90.7598 1.37769Z" fill="url(#paint0_linear)"/>
				<mask id="mask0" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="193" height="193">
				<path d="M90.7598 1.37769C94.4352 -0.144721 98.5648 -0.144719 102.24 1.37769L159.703 25.1794C163.378 26.7018 166.298 29.6219 167.821 33.2973L191.622 90.7598C193.145 94.4352 193.145 98.5648 191.622 102.24L167.821 159.703C166.298 163.378 163.378 166.298 159.703 167.821L102.24 191.622C98.5648 193.145 94.4352 193.145 90.7597 191.622L33.2973 167.821C29.6219 166.298 26.7018 163.378 25.1794 159.703L1.37769 102.24C-0.144721 98.5648 -0.144719 94.4352 1.37769 90.7597L25.1794 33.2973C26.7018 29.6219 29.6219 26.7018 33.2973 25.1794L90.7598 1.37769Z" fill="url(#paint1_linear)"/>
				</mask>
				<g mask="url(#mask0)">
				<line x1="43.5869" y1="-1" x2="43.5869" y2="194" stroke="white" stroke-opacity="0.2"/>
				<line x1="97.8478" y1="-1" x2="97.8478" y2="194" stroke="white" stroke-opacity="0.2"/>
				<line x1="148.717" y1="-1" x2="148.717" y2="194" stroke="white" stroke-opacity="0.2"/>
				<line x1="-1" y1="37.5" x2="194" y2="37.5" stroke="white" stroke-opacity="0.2"/>
				<line x1="-1" y1="95.1522" x2="194" y2="95.1522" stroke="white" stroke-opacity="0.2"/>
				<line x1="-1" y1="154.5" x2="194" y2="154.5" stroke="white" stroke-opacity="0.2"/>
				<line x1="43.5869" y1="-1" x2="43.5869" y2="194" stroke="white" stroke-opacity="0.2"/>
				<path d="M64.402 99H70.245V90.0398H75.157C81.5128 90.0398 85.2102 86.2479 85.2102 80.7287C85.2102 75.2365 81.5803 71.3636 75.3054 71.3636H64.402V99ZM70.245 85.3572V76.1406H74.1854C77.5589 76.1406 79.1918 77.9759 79.1918 80.7287C79.1918 83.468 77.5589 85.3572 74.2124 85.3572H70.245ZM85.2055 99H91.0485V87.5838H102.91V99H108.74V71.3636H102.91V82.7663H91.0485V71.3636H85.2055V99ZM109.757 99H115.6V90.0398H120.512C126.868 90.0398 130.565 86.2479 130.565 80.7287C130.565 75.2365 126.935 71.3636 120.66 71.3636H109.757V99ZM115.6 85.3572V76.1406H119.54C122.914 76.1406 124.547 77.9759 124.547 80.7287C124.547 83.468 122.914 85.3572 119.567 85.3572H115.6Z" fill="white"/>
				<path d="M24.3466 122H34.3324C40.8636 122 43.5625 118.815 43.5625 114.659C43.5625 110.287 40.5398 107.912 38.0028 107.75V107.48C40.3778 106.832 42.483 105.267 42.483 101.705C42.483 97.6562 39.7841 94.3636 34.0085 94.3636H24.3466V122ZM27.6932 119.031V109.423H34.4943C38.1108 109.423 40.3778 111.852 40.3778 114.659C40.3778 117.088 38.7045 119.031 34.3324 119.031H27.6932ZM27.6932 106.509V97.3324H34.0085C37.679 97.3324 39.2983 99.2756 39.2983 101.705C39.2983 104.619 36.9233 106.509 33.9006 106.509H27.6932ZM50.929 122.486C54.5455 122.486 56.4347 120.543 57.0825 119.193H57.2444V122H60.4291V108.344C60.4291 101.759 55.4092 101.003 52.7643 101.003C49.6336 101.003 46.0711 102.082 44.4518 105.861L47.4745 106.94C48.1762 105.429 49.836 103.81 52.8722 103.81C55.8005 103.81 57.2444 105.362 57.2444 108.02V108.128C57.2444 109.666 55.679 109.531 51.9006 110.017C48.0548 110.516 43.858 111.366 43.858 116.116C43.858 120.165 46.9887 122.486 50.929 122.486ZM51.4148 119.625C48.8779 119.625 47.0427 118.491 47.0427 116.278C47.0427 113.849 49.2558 113.094 51.7387 112.77C53.0881 112.608 56.7046 112.23 57.2444 111.582V114.497C57.2444 117.088 55.1933 119.625 51.4148 119.625ZM77.1235 105.915C76.125 102.973 73.8849 101.003 69.7826 101.003C65.4105 101.003 62.1718 103.486 62.1718 106.994C62.1718 109.855 63.8721 111.771 67.6775 112.662L71.1321 113.472C73.2237 113.957 74.2088 114.956 74.2088 116.386C74.2088 118.168 72.3196 119.625 69.3508 119.625C66.7464 119.625 65.1136 118.505 64.5468 116.278L61.5241 117.034C62.2663 120.556 65.1676 122.432 69.4048 122.432C74.2223 122.432 77.5014 119.8 77.5014 116.224C77.5014 113.337 75.6931 111.515 71.9957 110.611L68.919 109.855C66.463 109.248 65.3565 108.425 65.3565 106.832C65.3565 105.051 67.2457 103.756 69.7826 103.756C72.5625 103.756 73.7095 105.294 74.2628 106.724L77.1235 105.915ZM78.4952 122H81.6799V101.273H78.4952V122ZM80.1146 97.8182C81.356 97.8182 82.3816 96.8466 82.3816 95.6591C82.3816 94.4716 81.356 93.5 80.1146 93.5C78.8731 93.5 77.8475 94.4716 77.8475 95.6591C77.8475 96.8466 78.8731 97.8182 80.1146 97.8182ZM92.1333 122.432C96.7213 122.432 99.7441 119.625 100.284 115.955H97.0992C96.5054 118.222 94.6162 119.571 92.1333 119.571C88.3549 119.571 85.9259 116.44 85.9259 111.636C85.9259 106.94 88.4088 103.864 92.1333 103.864C94.9401 103.864 96.6134 105.591 97.0992 107.48H100.284C99.7441 103.594 96.4515 101.003 92.0793 101.003C86.4657 101.003 82.7412 105.429 82.7412 111.744C82.7412 117.952 86.3037 122.432 92.1333 122.432ZM101.139 129.773H104.324V118.815H104.594C105.296 119.949 106.645 122.432 110.639 122.432C115.821 122.432 119.438 118.276 119.438 111.69C119.438 105.159 115.821 101.003 110.585 101.003C106.537 101.003 105.296 103.486 104.594 104.565H104.216V101.273H101.139V129.773ZM104.27 111.636C104.27 106.994 106.321 103.864 110.208 103.864C114.256 103.864 116.253 107.264 116.253 111.636C116.253 116.062 114.202 119.571 110.208 119.571C106.375 119.571 104.27 116.332 104.27 111.636ZM123.68 94.3636H120.496V122H123.68V94.3636ZM131.813 122.486C135.429 122.486 137.318 120.543 137.966 119.193H138.128V122H141.313V108.344C141.313 101.759 136.293 101.003 133.648 101.003C130.517 101.003 126.955 102.082 125.335 105.861L128.358 106.94C129.06 105.429 130.72 103.81 133.756 103.81C136.684 103.81 138.128 105.362 138.128 108.02V108.128C138.128 109.666 136.563 109.531 132.784 110.017C128.938 110.516 124.742 111.366 124.742 116.116C124.742 120.165 127.872 122.486 131.813 122.486ZM132.298 119.625C129.762 119.625 127.926 118.491 127.926 116.278C127.926 113.849 130.139 113.094 132.622 112.77C133.972 112.608 137.588 112.23 138.128 111.582V114.497C138.128 117.088 136.077 119.625 132.298 119.625ZM152.34 101.273H147.913V96.3068H144.729V101.273H141.598V103.972H144.729V116.926C144.729 120.543 147.644 122.27 150.342 122.27C151.53 122.27 152.286 122.054 152.717 121.892L152.07 119.031C151.8 119.085 151.368 119.193 150.666 119.193C149.263 119.193 147.913 118.761 147.913 116.062V103.972H152.34V101.273ZM161.798 122.432C166.008 122.432 169.085 120.327 170.056 117.196L166.98 116.332C166.17 118.491 164.294 119.571 161.798 119.571C158.06 119.571 155.482 117.156 155.334 112.716H170.38V111.366C170.38 103.648 165.792 101.003 161.474 101.003C155.86 101.003 152.136 105.429 152.136 111.798C152.136 118.168 155.806 122.432 161.798 122.432ZM155.334 109.963C155.55 106.738 157.83 103.864 161.474 103.864C164.928 103.864 167.141 106.455 167.141 109.963H155.334Z" fill="white"/>
				</g>
				<defs>
				<linearGradient id="paint0_linear" x1="26.5" y1="166.5" x2="165" y2="30.5" gradientUnits="userSpaceOnUse">
				<stop stop-color="#232531"/>
				<stop offset="1" stop-color="#8993BE"/>
				</linearGradient>
				<linearGradient id="paint1_linear" x1="26.5" y1="166.5" x2="165" y2="30.5" gradientUnits="userSpaceOnUse">
				<stop stop-color="#232531"/>
				<stop offset="1" stop-color="#8993BE"/>
				</linearGradient>
				</defs>
			</svg>
		</a>
		<ul>
			<li>
				<a <?php if ($action == '') echo ' class="active"'; ?>href="/sandbox">Welcome</a>
			</li>
			<li>
				<a <?php if ($action == 'db-init') echo ' class="active"'; ?>href="/sandbox?action=db-init">Prepare Database</a>
			</li>
			<li>
				<a <?php if ($action == 'db-seed') echo ' class="active"'; ?>href="/sandbox?action=db-seed">Seed Database</a>
			</li>
			<li>
				<a <?php if ($action == 'php-info') echo ' class="active"'; ?>href="/sandbox?action=php-info">PHP Info</a>
			</li>
			<li>
				<a <?php if ($action == 'sys-info') echo ' class="active"'; ?>href="/sandbox?action=sys-info">System Info</a>
			</li>
			<li>
				<a <?php if ($action == 'licences') echo ' class="active"'; ?>href="/sandbox?action=licences">Credits</a>
			</li>
		</ul>
	</nav>
	<main>
		<h1><?php echo $title; ?></h1>
		<?php echo $output; ?>
	</main>
</body>
</html>