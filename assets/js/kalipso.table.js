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
        this.tempResult = []

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
            params: {}
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
    prepareBody() {

        if (typeof this.options.source === 'object') { // client-side



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
    body() {

        let tbody = ``
        console.log(this.result.length)

        if (this.result.length === 0) {
            tbody = `<tbody><tr><td colspan="100%" class="no_result_info">` + this.l10n("no_record") + `</td></tr></tbody>`
        } else {
            tbody = `<tbody>content</tbody>`
        }
        /*

        if (this.options.tableFooter.visible) {

            tbody = `<tbody><tr>`

            for (const [index, col] of Object.entries(this.options.columns)) {

                tbody +=  `<td>` + col.title + `</td>`

            }

            tbody += `</tr></tbody>`
        }
        */
        return tbody

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

                    this.prepareBody()

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
        let tempParams = {} 
        for (const [key, value] of Object.entries(this.options.params)) {

            if (value !== "") tempParams[key] = value
        }
        this.options.params = tempParams

        this.prepareBody()

    }

}