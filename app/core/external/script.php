<?php
if (defined('INLINE_JS')) { ?>
<script>
<?php
} else {

	http('content_type', 'js');

}	?>
NProgress.start();
(function() {

	const vanillaPjax = new vPjax('a:not([target="_blank"])', '#wrap').form('[data-vpjax]').init();
	document.addEventListener("vPjax:start", (e) => {
		NProgress.start();
	})
	document.addEventListener("vPjax:finish", (e) => {
		NProgress.done();
	});

	setTimeout(() => {
		NProgress.done()
	}, 500)

})();

/* Helpers */
function trimAny(str, chars) {
    var start = 0, 
        end = str.length;

    while(start < end && chars.indexOf(str[start]) >= 0)
        ++start;

    while(end > start && chars.indexOf(str[end - 1]) >= 0)
        --end;

    return (start > 0 || end < str.length) ? str.substring(start, end) : str;
}

/* /Helpers */

async function formSender(e, url) {

	// Preparing URL and Form Data
	let formId = '#' + e.target.id
	document.querySelector(formId).classList.add('form-section-active')
	NProgress.start();
	url = location.origin + '/form/' + trimAny(url, '/')

	const formData = new FormData(e.target);

	// Preparing Fetch API
	let abortController = new AbortController()

	// Fetching
	const request = await fetch(url, {
		method: 'POST',
		mode: 'cors',
		cache: 'no-cache',
		credentials: 'same-origin',
		headers: {},
		redirect: 'follow',
		referrerPolicy: 'same-origin',
		signal: abortController.signal,
		body: formData
	}).then(function (response) {

		return response.ok ? response.text() : false

	}).then(function (dom) {

		return dom

	}).catch(function (err) {

		throw err
		return false

	})
	
	setTimeout(() => {
		NProgress.done();
		document.querySelector(formId).classList.remove('form-section-active')
	}, 500)
	
	e.preventDefault()
}

<?php
if (defined('INLINE_JS')) {
?>
</script>
<?php
}	?>