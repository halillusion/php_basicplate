/*!
 * Copyright 2021, Halil Ibrahim Ercelik
 * Released under the MIT License
 * {@link https://github.com/halillusion/KalipsoTable GitHub}
 * Inspired by jQuery.DataTables
 */


class KalipsoTable {

    // The class is started with the default options and definitions.
    constructor (options, data = null) {

        this.version = '0.0.1'
        this.loading = false
        this.result = []

        if (window.KalipsoTable === undefined) {
            window.KalipsoTable = {}
        }

        if (window.KalipsoTable.languages === undefined) {
            window.KalipsoTable.languages = {}
        }

        window.KalipsoTable.languages["en"] = {
            "init_option_error": "KalipsoTable cannot be initialized without default options!",
            "target_selector_not_found": "Target selector not found!",

            "all": "All",
            "sorting_asc": "Sorting (A-Z)",
            "sorting_desc": "Sorting (Z-A)",
            "no_record": "No record!"
        }

        let defaultOptions = {
            language: "en",
            columns: [
                {
                    "searchable": {
                        "type": "number", // (number | text | date | select)
                        "min": 1,
                        "max": 999
                    },
                    "orderable": true,
                    "title": "#",
                    "key": "id"
                }
            ],
            order: ["id", "asc"],
            source: null, // object or string (url)
            lengthOptions: [
                {"name": "10", "value": 10, "default":true},
                {"name": "10", "value": 50},
                {"name": this.l10n("all"), "value": 0}
            ],
            selector: null,
            tableHeader: {
                "class": "",
                "searchBar": true
            },
            tableFooter: {
                "visible": false,
                "class": "",
                "searchBar": true
            },
            searchBar: {
                inputClass: null,
                selectClass: null
            },
            params: [],
            fullSearch: null
        }

        this.data = []
        // this.bomb( this.version, "debug" )

        if (typeof options === 'string') {

            defaultOptions.selector = options
            this.options = defaultOptions

        } else if (typeof options === 'object') {

            this.options =  this.mergeObject(defaultOptions, options)

        } else {

            this.bomb(this.l10n("init_option_error"), "error")
            this.options = defaultOptions
        }

        if (this.options.selector !== undefined && document.querySelector(this.options.selector)) {
            this.init(document.querySelector(this.options.selector))
        } else {
            // this.bomb(this.l10n("target_selector_not_found") + ' (' + this.options.selector + ')', "warning")
        }

    }
    
    // Provides synchronization of setting data.
    mergeObject(defaultObj, overridedObj, key = null) {
        
        if (defaultObj !== null) {
            const keys = Object.keys(overridedObj)
            let key = null

            for (let i = 0; i < keys.length; i++) {
                key = keys[i]
                if (! defaultObj.hasOwnProperty(key) || typeof overridedObj[key] !== 'object') defaultObj[key] = overridedObj[key];
                else {
                    defaultObj[key] = this.mergeObject(defaultObj[key], overridedObj[key], key);
                }
            }

        } else {
            defaultObj = overridedObj
        }
        return defaultObj;

    }

    // Returns translation using key according to active language.
    l10n (key) {

        const dir = this.options !== undefined ? this.options.language : "en"

        if (window.KalipsoTable.languages[dir][key] !== undefined) {
            return window.KalipsoTable.languages[dir][key]
        } else {
            return false
        }

    }

    // It sends an output to the console by attributes.
    bomb(warn, type = "log") {

        warn = "KalipsoTable: " + warn
        switch (type) {

            case "debug":
                console.debug(warn)
                break;

            case "warning":
                console.warn(warn)
                break;

            case "error":
                console.error(warn)
                break;

            case "info":
                console.info(warn)
                break;

            default:
                console.log(warn)
                break;

        }

    }

    // Prepare content with options
    prepareBody(push = false) {

        if (typeof this.options.source === 'object') { // client-side

            let results = [] // this.options.source
            if (Object.keys(this.options.params).length) { // search

                this.options.source.forEach((p) => {
                    for (const [key, value] of Object.entries(this.options.params)) {

                        if (p[key] !== undefined) {
                            let string = p[key];
                            string = string.toString()
                            if (string.indexOf(value) >= 0) {
                                results.push(p)
                            }
                        }
                    }
                })
            } else {
                results = this.options.source
            }

            if (results.length && this.options.fullSearch) { // full search
                // will return
                /*
                results.forEach((p) => {
                    for (const [key, value] of Object.entries(this.options.columns)) {

                        console.log(key)
                        if (p[key] !== undefined) {
                            let string = p[key];
                            string = string.toString()
                            if (string.indexOf(value) >= 0) {
                                results.push(p)
                            }
                        }
                    }
                })*/

            }


            if (results.length && this.options.order.length) { // order

                results = results.sort((a, b) => {
                    const key = this.options.order[0]
                    if (this.options.order[1] === 'desc') {
                        return b[key] > a[key] ? 1 : -1
                    } else {
                        return a[key] > b[key] ? 1 : -1
                    }
                })

            }

            if (results.length) {
                this.result = results
            }

            if (push) {
                document.querySelector(this.options.selector + ' tbody').innerHTML = this.body(false)
            }


        } else { // front-side



        }
    }

    // The table structure is created.
    init (element) {

        this.prepareBody()

        element.classList.add("kalipsoTable");
        element.innerHTML = this.head() +
        this.body() +
        this.footer()

        this.eventListener()

    }

    // Prepares the table header.
    head() {

        let thead = `<thead><tr>`

        for (const [index, col] of Object.entries(this.options.columns)) {

            let thClass = 'sort'
            let sortingTitle = this.l10n("sorting_asc")
            if (this.options.order[0] !== undefined && this.options.order[0] === col.key) {
                thClass += ` ` + this.options.order[1]
                sortingTitle = this.l10n("sorting_" + (this.options.order[1] === "desc" ? "asc" : "desc"))
            }

            thead +=  `<th` + (col.orderable ? ` class="` + thClass + `" data-sort="` + col.key + `" title="` + sortingTitle + `"` : ``) + `>` + col.title + `</th>`

        }

        if (this.options.tableHeader.searchBar) {

            thead += `</tr><tr>`

            for (const [index, col] of Object.entries(this.options.columns)) {
                
                thead +=  this.options.tableFooter.searchBar ? `<th>` + 
                    (! col.searchable ? `` : this.generateSearchArea(col.searchable, col.key)) + 
                `</th>` : `<th></th>`

            }

        }

        thead += `</tr></thead>`
        return thead

    }

    // Prepares the table body.
    body(withTbodyTag = true) {

        let tbody = ``

        if (this.result.length === 0) {

            tbody = `<tr><td colspan="100%" class="no_result_info">` + this.l10n("no_record") + `</td></tr>`

        } else {

            this.result.forEach((row) => {

                tbody += `<tr>`
                for (const [index, col] of Object.entries(this.options.columns)) {
                
                    if (row[col.key] !== undefined) tbody += `<td>` + row[col.key] + `</td>`
                    else tbody += `<td></td>`

                }
                tbody += `</tr>`
            })

        }
        return withTbodyTag ? `<tbody>` + tbody + `</tbody>` : tbody

    }

    // Prepares the table footer.
    footer() {

        let tfoot = ``
        if (this.options.tableFooter.visible) {

            tfoot = `<tfoot><tr>`

            for (const [index, col] of Object.entries(this.options.columns)) {
                
                tfoot +=  this.options.tableFooter.searchBar ? `<td>` + 
                    (! col.searchable ? col.title : this.generateSearchArea(col.searchable, col.key)) + 
                `</td>` : `<td>` + col.title + `</td>`

            }

            tfoot += `</tr></tfoot>`
        }
        return tfoot

    }

    // Prepares search fields for in-table searches.
    generateSearchArea(areaDatas, key) {

        // number | text | date | select
        let bar = ``
        switch (areaDatas.type) {

            case "number":
            case "text":
            case "date":
                bar = `<input data-search="` + key + `" type="` + areaDatas.type + `"` + 
                (this.options.searchBar.inputClass !== undefined && this.options.searchBar.inputClass ? ` class="` + this.options.searchBar.inputClass + `" ` : ` `) +
                (areaDatas.min !== undefined && areaDatas.min ? ` min="` + areaDatas.min + `" ` : ` `) + 
                (areaDatas.max !== undefined && areaDatas.max ? ` max="` + areaDatas.max + `" ` : ` `) + 
                (areaDatas.maxlenght !== undefined && areaDatas.maxlenght ? ` maxlenght="` + areaDatas.maxlenght + `" ` : ` `) + 
                `/>`
                break;

            case "select":
                bar = `<select data-search="` + key + `"` + 
                (this.options.searchBar.selectClass !== undefined && this.options.searchBar.selectClass ? ` class="` + this.options.searchBar.selectClass + `" ` : ` `) +
                `><option value=""></option>`

                for (const [index, option] of Object.entries(areaDatas.datas)) {
                    bar += `<option value="` + option.value + `">` + option.name + `</option>`
                }

                bar += `</select>`
                break;
        }

        return bar

    }

    // Prepares event listeners so that table actions can be listened to.
    eventListener () {

        const element = document.querySelector(this.options.selector)

        let searchInputs = document.querySelectorAll(this.options.selector + ' [data-search]')
        if (searchInputs.length) {

            for(let e=0; e < searchInputs.length; e++) {

                if (searchInputs[e].nodeName.toLowerCase() === 'select') {

                    searchInputs[e].addEventListener("change", a => {
                        // sync select values
                        this.fieldSynchronizer(searchInputs[e])
                    });

                } else {

                    searchInputs[e].addEventListener("input", a => {
                        // sync input values
                        this.fieldSynchronizer(searchInputs[e])
                    });
                }
                
            }

        }

        let sortingTh = document.querySelectorAll(this.options.selector + ' thead th.sort')
        if (sortingTh.length) {

            for (let th = 0; th < sortingTh.length; th++) {
                
                sortingTh[th].addEventListener("click", a => {

                    if (Array.from(sortingTh[th].classList).indexOf("asc") !== -1) { // asc

                        sortingTh[th].classList.remove("asc")
                        sortingTh[th].classList.add("desc")
                        sortingTh[th].setAttribute("title", this.l10n("sorting_asc"))
                        this.options.order = [sortingTh[th].getAttribute("data-sort"), "desc"]

                    } else if (Array.from(sortingTh[th].classList).indexOf("desc") !== -1) { // desc

                        sortingTh[th].classList.remove("desc")
                        sortingTh[th].classList.add("asc")
                        sortingTh[th].setAttribute("title", this.l10n("sorting_desc"))
                        this.options.order = [sortingTh[th].getAttribute("data-sort"), "asc"]

                    } else { // default

                        sortingTh[th].classList.add("asc")
                        sortingTh[th].setAttribute("title", this.l10n("sorting_desc"))
                        this.options.order = [sortingTh[th].getAttribute("data-sort"), "asc"]

                    }

                    let thAreas =  document.querySelectorAll(this.options.selector + ' thead th.sort')

                    if (thAreas.length) {

                        for (let thIndex = 0; thIndex < thAreas.length; thIndex++) {
                            if (thIndex !== th) thAreas[thIndex].classList.remove("asc", "desc")
                        }

                    }

                    this.prepareBody(true)

                })

            }

        }

    }

    // If there is more than one of the changing search fields, it ensures that all search fields are synchronized with the same data.
    fieldSynchronizer(field) {

        const searchAttr = field.getAttribute("data-search")
        const targetElements = document.querySelectorAll(this.options.selector + ` [data-search="` + searchAttr + `"]`)
        targetElements.forEach( (input) => {
            input.value = field.value
        })
        this.options.params[searchAttr] = field.value

        // clear empty string parameters
        let tempParams = []
        for (const [key, value] of Object.entries(this.options.params)) {

            if (value !== "") tempParams[key] = value
        }
        this.options.params = tempParams

        this.prepareBody(true)

    }

}