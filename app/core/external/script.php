<?php
if (defined('INLINE_JS')) { ?>
<script>
<?php
} else {

	http('content_type', 'js');

}	?>
/* Init */
NProgress.start();
(function() {

	const vanillaPjax = new vPjax('a:not([target="_blank"])', '#wrap').form('[data-vpjax]').init()
	document.addEventListener("vPjax:start", (e) => {
		NProgress.start()
	})
	document.addEventListener("vPjax:finish", (e) => {
		NProgress.done()
	})

	setTimeout(() => {
		NProgress.done()
	}, 500)

})()

/* Helpers */
function trimAny(str, chars) {
    var start = 0, 
        end = str.length

    while(start < end && chars.indexOf(str[start]) >= 0)
        ++start

    while(end > start && chars.indexOf(str[end - 1]) >= 0)
        --end

    return (start > 0 || end < str.length) ? str.substring(start, end) : str
}

/* /Helpers */

async function formSender(e, url) {

	// Preparing URL and Form Data
	let formId = '#' + e.target.id
	document.querySelector(formId).classList.add('form-section-active')
	NProgress.start()
	url = location.origin + '/form/' + trimAny(url, '/')

	const formData = new FormData(e.target)

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

		return response.ok ? response.json() : false

	}).then(function (dom) {

		return dom

	}).catch(function (err) {

		throw err
		return false

	})

	if (request) {
		asyncResponse(request, formId)
	} else {
		alert("A problem occurred")
	}
	
	setTimeout(() => {
		NProgress.done()
		document.querySelector(formId).classList.remove('form-section-active')
	}, 500)
	
	e.preventDefault()
}

/* Async Response Formatter */
function asyncResponse(response, selector = null) {

	if (response.alert_type !== undefined) {
		switch (response.alert_type) {
			case 'toast': 

				wrapper = document.querySelector('#toastPlacement')

				if (wrapper) {

					// calculating reading time
					const words = (response.title + ' ' + response.message).trim().split(/\s+/).length;
					const time = (words * 0.50) * 1000;

					// creating toast element
					let toastId = "toast_" + (Math.random() + 1).toString(36).substring(7)
					const toast = document.createElement('div');
					toast.classList.add('toast', 'toast-' + response.status)
					toast.setAttribute("role", "alert")
					toast.setAttribute("id", toastId)
					toast.setAttribute("aria-live", "assertive")
					toast.setAttribute("aria-atomic", "true")
					toast.setAttribute("data-bs-delay", time)
					toast.innerHTML = `<div class="toast-header">` + 
					`	<strong class="me-auto">` + response.title + `</strong>` + 
					`	<button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>` + 
					`</div>` + 
					`<div class="toast-body">` + 
					response.message +
					`</div>`

					// adding dom and starting
					wrapper.appendChild(toast)

					const toastEl = document.querySelector('#' + toastId)
					const toastIns = new bootstrap.Toast(toastEl)
					toastIns.show()

				} else {
					alert(response.title + ' ' + response.message)
				}

			break
			case 'card': 

				if (selector && document.querySelector(selector)) {
					// Creating response div
					let el = document.querySelector(selector)
					if (! el.querySelector('.response-message')) {

						const wrap = document.createElement('div');
						wrap.classList.add('response-message')
						el.insertBefore(wrap, el.firstChild)

					}

					el.querySelector('.response-message').innerHTML = `<div class="alert alert-` + response.status + ` alert-dismissible fade show" role="alert">` +
					`<strong>' + response.title + '</strong> ` + response.message +
					`<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>` +
					`</div>`

				} else {
					alert(response.title + ' ' + response.message)
				}

			break
			default: 
				alert(response.title + ' ' + response.message)
		}
	}

}

<?php
if (defined('INLINE_JS')) {
?>
</script>
<?php
}	?>