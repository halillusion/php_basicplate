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
            "no_record": "No record!",
            "out_of_x_records": "out of [X] records",
            "showing_x_out_of_y_records": "Showing [X] out of [Y] records.",
        }

        let defaultOptions = {
            language: "en",
            schema: '<div class="table-row">' + 
                '<div class="column-25">[L]</div>' + // Listing option select
                '<div class="column-25">[S]</div>' + // Full search input
                '<div class="column-100">[T]</div>' + // Table
                '<div class="column-50">[I]</div>' + // Info
                '<div class="column-50">[P]</div>' + // Pagination
            '</div>',
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
            ],
            selector: null,
            tableHeader: {
                searchBar: true
            },
            customize: {
                tableWrapClass: "kalipso-table-wrapper",
                tableClass: "kalipso-table",
                tableHeadClass: null,
                tableBodyClass: null,
                tableFooterClass: null,
                inputClass: 'form-input',
                selectClass: 'form-input',
                paginationUlClass: 'paginate',
                paginationLiClass: 'paginate-item',
                paginationAClass: 'paginate-item-link'
            },
            tableFooter: {
                "visible": false,
                "searchBar": true
            },
            params: [],
            pageLenght: 0,
            fullSearch: true,
            fullSearchParam: "",
            totalRecord: 0
        }

        this.data = []
        this.bomb( this.version, "debug" )

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
            this.bomb(this.l10n("target_selector_not_found") + ' (' + this.options.selector + ')', "debug")
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

        if (window.KalipsoTable.languages[dir] === undefined) {
            this.bomb("Language definitions not found for " + dir, "error")
        }

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

            this.options.totalRecord = Object.keys(this.options.source).length

            let results = [] // this.options.source
            if (Object.keys(this.options.params).length) { // search

                this.options.source.forEach((p) => {
                    for (const [key, value] of Object.entries(this.options.params)) {

                        if (p[key] !== undefined) {
                            let string = p[key]
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

            if (results.length && this.options.fullSearch && this.options.fullSearchParam) { // full search
                let tempResults = []
                results.forEach((p) => {
                    for (const [key, value] of Object.entries(p)) {
                        let string = value.toString()
                        if (string.indexOf(this.options.fullSearchParam) >= 0) {
                            tempResults.push(p)
                            break;
                        }
                    }
                })
                results = tempResults
            }

            if (results.length && this.options.order.length) { // order

                results = results.sort((a, b) => {
                    const key = this.options.order[0]
                    if (this.options.order[1] === 'desc') {
                        return this.strip(b[key]) > this.strip(a[key]) ? 1 : -1
                    } else {
                        return this.strip(a[key]) > this.strip(b[key]) ? 1 : -1
                    }
                })

            }

            this.result = results

            if (push) {
                document.querySelector(this.options.selector + ' tbody').innerHTML = this.body(false)
                document.querySelector(this.options.selector + ' [data-info]').innerHTML = this.information()
            }


        } else { // server-side



        }
    }

    // table information text
    information() {

        let info = ``

        if (this.result && this.result.length !== 0) {
            info = this.l10n("showing_x_out_of_y_records").replace("[X]", this.result.length)
            info = info.replace("[Y]", "1-1");
        } else {
            info = this.l10n("no_record");
        }

        if (this.options.totalRecord > 0 || this.result.length !== this.options.totalRecord) {
            info = info + ` (` + this.l10n("out_of_x_records").replace("[X]", this.options.totalRecord) + `)`
        }

        console.log(this.options.totalRecord)

        return `<span class="kalipso-information" data-info>` + info + `</span>`
    }

    // The table structure is created.
    init (element) {

        this.prepareBody()
        const sorting = this.sorting()
        const fullSearch = this.fullSearchArea()
        const info = this.information()

        let schema = this.options.schema
        const table = `<div`+(this.options.customize.tableWrapClass ? ` class="` + this.options.customize.tableWrapClass + `"` : ``)+`>` + 
            `<table`+(this.options.customize.tableClass ? ` class="kalipso-table ` + this.options.customize.tableClass + `"` : ` class="kalipso-table"`) + `>` +
                this.head() +
                this.body() +
                this.footer() +
            `</table>` + 
        `</div>`
        
        schema = schema.replace("[T]", table)
        schema = schema.replace("[L]", sorting)
        schema = schema.replace("[S]", fullSearch)
        schema = schema.replace("[I]", info)


        element.innerHTML = schema

        this.eventListener()

    }

    fullSearchArea() {

        let area = ``
        if (this.options.fullSearch) {
            area = `<input data-full-search type="text" class="` + this.options.customize.inputClass + `"/>`
        }
        return area
    }

    fullSearch(param) {

        this.options.fullSearchParam = param
        this.prepareBody(true)

    }

    sorting () {

        let sortingDom = ``

        if (this.options.lengthOptions.length) {
            let defaultSelected = false
            let selected = ``

            sortingDom = `<select data-perpage` + 
                (this.options.customize.selectClass !== undefined && this.options.customize.selectClass 
                    ? ` class="` + this.options.customize.selectClass + `" ` : ` `) +
                `>`

            for (let i = 0; i < this.options.lengthOptions.length; i++) {
                if (this.options.lengthOptions[i].default !== undefined && this.options.lengthOptions[i].default) {
                    defaultSelected = this.options.lengthOptions[i].value
                }
            }

            for (let i = 0; i < this.options.lengthOptions.length; i++) {

                const val = this.options.lengthOptions[i].value.toString()
                const name = this.options.lengthOptions[i].name
                selected = ``

                if (defaultSelected === this.options.lengthOptions[i].value || (defaultSelected === false && i === 0)) {
                    selected = ` selected`
                    if (defaultSelected === false && i === 0) {
                        defaultSelected = this.options.lengthOptions[i].value
                    }
                }

                sortingDom = sortingDom.concat(`<option value="` + val + `"` + selected + `>` + name + `</option>`)
            }

            sortingDom = sortingDom.concat(`</select>`)

            this.pageLenght = defaultSelected
        }

        return sortingDom
    }

    // Prepares the table header.
    head() {

        let thead = `<thead`+(this.options.customize.tableHeadClass ? ` class="` + this.options.customize.tableHeadClass + `"` : ``)+`><tr>`

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
    body(withBodyTag = true) {

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
        return withBodyTag ? `<tbody`+(this.options.customize.tableBodyClass ? ` class="` + this.options.customize.tableBodyClass + `"` : ``)+`>` + tbody + `</tbody>` : tbody

    }

    // Prepares the table footer.
    footer() {

        let tfoot = ``
        if (this.options.tableFooter.visible) {

            tfoot = `<tfoot`+(this.options.customize.tableFooterClass ? ` class="` + this.options.customize.tableFooterClass + `"` : ``)+`><tr>`

            for (const [index, col] of Object.entries(this.options.columns)) {
                
                tfoot +=  this.options.tableFooter.searchBar ? `<td>` + 
                    (! col.searchable ? col.title : this.generateSearchArea(col.searchable, col.key)) + 
                `</td>` : `<td>` + col.title + `</td>`

            }

            tfoot += `</tr></tfoot>`
        }
        return tfoot

    }

    // Clean tags.
    strip(text) {

        let tmp = document.createElement("DIV");
        tmp.innerHTML = text;
        return tmp.textContent || tmp.innerText || "";
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
                (this.options.customize.inputClass !== undefined && this.options.customize.inputClass ? ` class="` + this.options.customize.inputClass + `" ` : ` `) +
                (areaDatas.min !== undefined && areaDatas.min ? ` min="` + areaDatas.min + `" ` : ` `) + 
                (areaDatas.max !== undefined && areaDatas.max ? ` max="` + areaDatas.max + `" ` : ` `) + 
                (areaDatas.maxlenght !== undefined && areaDatas.maxlenght ? ` maxlenght="` + areaDatas.maxlenght + `" ` : ` `) + 
                `/>`
                break;

            case "select":
                bar = `<select data-search="` + key + `"` + 
                (this.options.customize.selectClass !== undefined && this.options.customize.selectClass ? ` class="` + this.options.customize.selectClass + `" ` : ` `) +
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

        if (this.options.fullSearch) {

            let searchInput = document.querySelector(this.options.selector + ' [data-full-search]')
            if (searchInput) {
                searchInput.addEventListener("change", a => {
                    this.fullSearch(searchInput.value)
                })

                searchInput.addEventListener("input", a => {
                    this.fullSearch(searchInput.value)
                })
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