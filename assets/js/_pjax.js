/*!
 * Copyright 2021, Halil Ibrahim Ercelik
 * Released under the MIT License
 * {@link https://github.com/halillusion/vpjax GitHub}
 */

class vPjax {

	// Define basic parameters and initialize class
	constructor (selector, wrap) {

		this.version = '0.2.0'
		this.options = {
			selector,
			wrap,
			url: null,
			cacheExpire: 300
		}
		this.store = {}

		// Init
		this.init()

		window.onpopstate = function(event) {
			this.getStore(event)
		};
	}

	// Add event listener for targets.
	init () {
		let links = document.querySelectorAll(this.options.selector)
		for (let i = 0; i < links.length; i++) {
			links[i].addEventListener("click", (e) => {
				e.preventDefault()
				this.handler(e, links[i])
			})
		}
	}

	// Handle the click event.
	handler (event, element) {

		// Ignore for hash-only addresses.
		if ( element.getAttribute('href') === '#') 
			return

		const link = new URL(element.getAttribute('href'))

		// Middle click, command click, and control click should open links in a new tab as normal.
		if ( event.ctrlKey || event.shiftKey || event.altKey || event.metaKey || event.which > 1 ) 
			return

		// Ignore cross origin links
		if ( location.protocol !== link.protocol || location.hostname !== link.hostname ) {
			location.href = link 
			return
		}

		// Ignore case when a hash is being tracked on the current URL
		if ( link.href.indexOf('#') > -1 && this.stripHash(link) === this.stripHash(location) ) 
			return

		let clickEvent = new CustomEvent('vPjax:click', this.options);
		document.dispatchEvent(clickEvent);
		this.get(link)
		event.preventDefault()
	}

	async get (url) {

		this.options.url = url
		let response = await fetch(url).then(function (response) {
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
			location.href = this.options.url
		}
	}

	loadContent (html) {
		const parser = new DOMParser()
		let dom = parser.parseFromString(html, 'text/html')
		let wrap = dom.querySelector(this.options.wrap)
		if (wrap) {
			let currentWrap = document.querySelector(this.options.wrap)
			if (currentWrap) {
				let inner = wrap.innerHTML
				let title = document.querySelector("title").textContent;
				this.setStore(location.href, currentWrap.innerHTML, title)
				currentWrap.innerHTML = inner
				title = dom.querySelector("title").textContent
				if (title) {
					document.querySelector("title").textContent = title
				}
				let url = new URL(this.options.url);
				window.history.pushState({}, '', url);
				this.init()
			} else {
				location.href = this.options.url
				throw "The element specified as selector does not exist!"
			}
		} else {
			location.href = this.options.url
			throw "Server response is not correct! -> " + html
		}
	}

	// Returns the "href" component of the given URL object, with the hash removed.
	stripHash(location) {
		return location.href.replace(/#.*/, '')
	}

	setStore (href, body, title) {
		this.store[href] = {
			href,
			body,
			title,
			expire: (Math.floor(Date.now() / 1000) + (this.options.cacheExpire * 1000))
		}
		console.log(this.store)
	}

	getStore (event) {
		alert("location: " + document.location + ", state: " + JSON.stringify(event.state));
	}
}