class vPjax {

	constructor (selector, wrap) {

		this.selector = selector
		this.wrap = wrap

		const links = document.querySelectorAll(this.selector)

		for (let i = 0; i < links.length; i++) {
			links[i].addEventListener("click", (e) => {
				this.get(links[i].getAttribute('href'))
				e.preventDefault()
			});
		}
	}

	async get (url) {
		
		const response = await fetch(url).then(function (response) {
			return response.ok ? response.text() : false
		}).then(function (dom) {
			return dom
		}).catch(function (err) {
			throw err
	        return false
	    })

	    if (response) {
	    	this.loadContent(response)
	    } else {
	    	console.log("A problem occurred on the server response.", response);
	    	window.location.href = url
	    }
	}

	async loadContent (html) {

		console.log(await DOMParser.parseFromString(html))

	}
}