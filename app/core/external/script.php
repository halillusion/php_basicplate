<?php
if (defined('INLINE_JS')) { ?>
<script>
<?php
} else {

	http('content_type', 'js');

}	?>
NProgress.start();
(function() {

	setTimeout(() => {
		NProgress.done()
		console.log('ready!')

		const vanillaPjax = new vPjax('a:not([target="_blank"])', '#wrap')

		/*

		document.pjax('a:not([target="_blank"])', '#wrap');
		document.on('submit', 'form[data-async]', function(event) {
			$.pjax.submit(event, '#wrap')
		})

		$(document).on('pjax:send', function() {

			if (window.refreshVal !== undefined) {
				clearTimeout(window.refreshVal);
				window.refreshVal = undefined;
			}

			NProgress.start();
		})
		$(document).on('pjax:complete', function(e) {
			initialize();
			NProgress.done();
			if ($(document).find('#is_auth')[0] !== undefined) {
				$('body').removeAttr('class');
			}
		})

		$(document).on('pjax:popstate', function(e) {
			initialize(true);
		})

		$(document).on('pjax:error', function(xhr, textStatus, error, options) {
		  console.log(xhr, textStatus, error, options)
		})

		$.pjax({ url: $(this).val(), container: '#wrap' });
		$.pjax.reload({ container: '#wrap' });
		*/

	}, 500)
})();

<?php
if (defined('INLINE_JS')) {
?>
</script>
<?php
}	?>