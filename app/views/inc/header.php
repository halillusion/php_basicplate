<!doctype html>
<html lang="<?php echo lang('app.lang_code'); ?>" dir="<?php echo lang('app.dir'); ?>">
	<head>
		<meta charset="<?php echo lang('app.charset'); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title><?php title(); ?></title>
		<?php meta();
		assets('libs/bootstrap/css/bootstrap'.(lang('app.dir') == 'rtl' ? '.rtl' : '').'.min.css', true, true, true);
		assets('css/app.css', true, true, true); // Basicplate Styles
		assets('css/style.css', true, true, true); // Your Styles
		?>
	</head>
	<body id="wrap">