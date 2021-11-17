/*!
 * Copyright 2021, Halil Ibrahim Ercelik
 * Released under the MIT License
 * {@link https://github.com/halillusion/KalipsoTable GitHub}
 * Inspired by jQuery.DataTables
 */


class KalipsoTable {

    // The class is started with the default options.
    constructor (options, data = null) {

        this.version = '0.0.1'
        let defaultOptions = {
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
            defaultOrder: ["id", "asc"],
            fetch: null,
            lengthOptions: [
                {"name": "10", "value": 10, "default":true},
                {"name": "10", "value": 50},
                {"name": this.l10n("all"), "value": 0}
            ],
            selector: null,
            tableFooter: {
                "visible": true,
                "class": '',
                "searchBar": true
            },
            searchBar: {
                class: "form-control form-control-sm"
            }
        }

        this.data = []
        this.bomb( this.version, "debug" )

        if (typeof options === 'string') {

            defaultOptions.selector = options
            this.options = defaultOptions

        } else if (typeof options === 'object') {

            this.options = {...defaultOptions, ...options}

        } else {

            this.bomb(this.l10n("init_option_error"), "error")
            this.options = defaultOptions
        }

        if (this.options.selector !== undefined && document.querySelector(this.options.selector)) {
            this.init(document.querySelector(this.options.selector))
        } else {
            this.bomb(this.l10n("target_selector_not_found") + ' (' + this.options.selector + ')', "warning")
        }
    }

    // Returns translation using key according to active language.
    l10n (key) {

        let languageDefinitions = {
            "init_option_error": "KalipsoTable cannot be initialized without default options!",
            "target_selector_not_found": "Target selector not found!",

            "all": "All"
        }

        if (languageDefinitions[key] !== undefined) {
            return languageDefinitions[key]
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

    // The table structure is created.
    init (element) {

        element.innerHTML = this.head() +
        this.body() +
        this.footer()

    }

    head() {

        let thead = ``
        if (this.options.tableFooter.visible) {

            thead = `<thead><tr>`

            for (const [index, col] of Object.entries(this.options.columns)) {

                thead +=  this.options.tableFooter.searchBar ? `<th>` + 
                    col.title + 
                `</th>` : `<th>` + col.title + `</th>`

            }

            thead += `</tr></thead>`
        }
        return thead

    }

    body() {

        let tbody = ``
        if (this.options.tableFooter.visible) {

            tbody = `<tbody><tr>`

            for (const [index, col] of Object.entries(this.options.columns)) {

                tbody +=  this.options.tableFooter.searchBar ? `<td>` + 
                    (! col.searchable ? col.title : this.generateSearchArea(col.searchable)) + 
                `</td>` : `<td>` + col.title + `</td>`

            }

            tbody += `</tr></tbody>`
        }
        return tbody

    }

    footer() {

        let tfoot = ``
        if (this.options.tableFooter.visible) {

            tfoot = `<tfoot><tr>`

            for (const [index, col] of Object.entries(this.options.columns)) {
                tfoot +=  this.options.tableFooter.searchBar ? `<td>` + 
                    (! col.searchable ? col.title : this.generateSearchArea(col.searchable)) + 
                `</td>` : `<td>` + col.title + `</td>`

            }

            tfoot += `</tr></tfoot>`
        }
        return tfoot

    }

    generateSearchArea(areaDatas) {

        // number | text | date | select
        let bar = ``
        switch (areaDatas.type) {

            case "number":
            case "text":
            case "date":
                bar = `<input type="` + areaDatas.type + `"` + 
                (this.options.searchBar.class !== undefined && this.options.searchBar.class ? ` class="` + this.options.searchBar.class + `" ` : ` `) +
                (areaDatas.min !== undefined && areaDatas.min ? ` min="` + areaDatas.min + `" ` : ` `) + 
                (areaDatas.max !== undefined && areaDatas.max ? ` max="` + areaDatas.max + `" ` : ` `) + 
                (areaDatas.maxlenght !== undefined && areaDatas.maxlenght ? ` maxlenght="` + areaDatas.maxlenght + `" ` : ` `) + 
                `/>`
                break;

            case "select":
                bar = `<select` + 
                (this.options.searchBar.class !== undefined && this.options.searchBar.class ? ` class="` + this.options.searchBar.class + `" ` : ` `) +
                `>`

                for (const [index, option] of Object.entries(areaDatas.datas)) {
                    bar += `<option value="` + option.value + `">` + option.name + `</option>`
                }

                bar += `</select`
                break;
        }

        return bar
    }

}