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

		const vanillaPjax = new vPjax('a:not([target="_blank"])', '#wrap').form('[data-vpjax]').init();

		setTimeout(() => {
			vanillaPjax.reload();
		}, 2000)

		/*
		document.addEventListener('submit', 'form[data-pjax]', function(event) {
			$.pjax.submit(event, '#pjax-container')
		})
		*/

		document.addEventListener("vPjax:start", (e) => {
			NProgress.start();
		})

		document.addEventListener("vPjax:finish", (e) => {
			NProgress.done();
		});

	}, 500)
})();

<?php
if (defined('INLINE_JS')) {
?>
</script>
<?php
}	?>