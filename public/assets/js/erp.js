if (document.readyState == "loading") {

    /**
     * Window click closes all apps that are registered in the registry
     */
    function WindowCloser() {
        this.registry = [];
    }

    WindowCloser.prototype.register_shutdown = function (cb, emitter) {
        this.registry.push({ callback: cb, emitter_except: emitter });
    }

    WindowCloser.prototype.init = function () {
        window.onclick = (evt) => {
            let target = evt.target;
            for (let i = 0; i < this.registry.length; i++) {
                if (!this.registry[i]['emitter_except'].contains(target)) {
                    this.registry[i]['callback']();
                }
            }
        }
    }
    /***********WINDOW CLOSER ENDS************ */

    function FormValidate(elem) {
        this.element = elem;
        this.form_groups = null;
        this.ajax_queue = [];
        this.process_count = 0;
        this.form_valid = true;
        //milliseconds
        this.max_wait = 10;

        this.required_check = function (x) {
            let value = x.querySelector(".field-check").value;

            if (value !== null && value !== undefined && value.trim() !== "") {
                x.querySelector(".error-text").textContent = "";
                x.classList.remove("form-error");
            } else {
                x.querySelector(".error-text").textContent = "Field is required";
                x.classList.add("form-error");
                this.form_valid = false;
                x.querySelector(".field-check").onkeyup = function (evt) {
                    x.querySelector(".error-text").textContent = "";
                    x.classList.remove("form-error");
                }
            }
            this.process_count--;
        }

        this.email_check = function (x) {
            let value = x.querySelector(".field-check").value;
            let pattern = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            if (value === null || value === undefined || value.toString().trim() === "") {
                this.form_valid = false;
                x.querySelector(".error-text").textContent = "Field is required";
                x.classList.add("form-error");
                x.querySelector(".field-check").onkeyup = function (evt) {
                    x.querySelector(".error-text").textContent = "";
                    x.classList.remove("form-error");
                }
            } else if (!pattern.test(value)) {
                this.form_valid = false;
                x.querySelector(".error-text").textContent = "Invalid Email ID";
                x.classList.add("form-error");
                x.querySelector(".field-check").onkeyup = function (evt) {
                    x.querySelector(".error-text").textContent = "";
                    x.classList.remove("form-error");
                }
            } else {
                x.querySelector(".error-text").textContent = "";
                x.classList.remove("form-error");
            }
            this.process_count--;
        }

        this.phone_check = function (x) {
            let value = x.querySelector(".field-check").value;
            let pattern = /^[0-9]{10}$/;
            if (value === null || value === undefined || value.toString().trim() === "") {
                this.form_valid = false;
                x.querySelector(".error-text").textContent = "Field is required";
                x.classList.add("form-error");
                x.querySelector(".field-check").onkeyup = function (evt) {
                    x.querySelector(".error-text").textContent = "";
                    x.classList.remove("form-error");
                }
            } else if (!pattern.test(value)) {
                this.form_valid = false;
                x.querySelector(".error-text").textContent = "Invalid Phone Number";
                x.classList.add("form-error");
                x.querySelector(".field-check").onkeyup = function (evt) {
                    x.querySelector(".error-text").textContent = "";
                    x.classList.remove("form-error");
                }
            } else {
                x.querySelector(".error-text").textContent = "";
                x.classList.remove("form-error");
            }
            this.process_count--;
        }

        this.number_check = function (x) {
            let value = x.querySelector(".field-check").value;
            let pattern = /^[0-9]+$/;
            if (value === null || value === undefined || value.toString().trim() === "") {
                this.form_valid = false;
                x.querySelector(".error-text").textContent = "Field is required";
                x.classList.add("form-error");
                x.querySelector(".field-check").onkeyup = function (evt) {
                    x.querySelector(".error-text").textContent = "";
                    x.classList.remove("form-error");
                }
            } else if (!pattern.test(value)) {
                this.form_valid = false;
                x.querySelector(".error-text").textContent = "Invalid Number";
                x.classList.add("form-error");
                x.querySelector(".field-check").onkeyup = function (evt) {
                    x.querySelector(".error-text").textContent = "";
                    x.classList.remove("form-error");
                }
            } else {
                x.querySelector(".error-text").textContent = "";
                x.classList.remove("form-error");
            }
            this.process_count--;
        }

        this.money_check = function (x) {
            let value = x.querySelector(".field-check").value;
            let pattern = /^[0-9]+\.[0-9]{2}$/;
            if (value === null || value === undefined || value.toString().trim() === "") {
                this.form_valid = false;
                x.querySelector(".error-text").textContent = "Field is required";
                x.classList.add("form-error");
                x.querySelector(".field-check").onkeyup = function (evt) {
                    x.querySelector(".error-text").textContent = "";
                    x.classList.remove("form-error");
                }
            } else if (!pattern.test(value)) {
                this.form_valid = false;
                x.querySelector(".error-text").textContent = "Invalid Money; Use Ex: 12.34";
                x.classList.add("form-error");
                x.querySelector(".field-check").onkeyup = function (evt) {
                    x.querySelector(".error-text").textContent = "";
                    x.classList.remove("form-error");
                }
            } else {
                x.querySelector(".error-text").textContent = "";
                x.classList.remove("form-error");
            }
            this.process_count--;
        }

        this.checkbox_check = function (x) {
            let checked = x.querySelector(".field-check").checked;
            if (!checked) {
                this.form_valid = false;
                x.querySelector(".error-text").textContent = "Check this";
                x.classList.add("form-error");
                x.querySelector(".field-check").onchange = function (evt) {
                    x.querySelector(".error-text").textContent = "";
                    x.classList.remove("form-error");
                }
            } else {
                x.querySelector(".error-text").textContent = "";
                x.classList.remove("form-error");
            }
            this.process_count--;
        }

        this.radio_check = function (x) {
            let radios = x.querySelectorAll(".field-check");
            let any_one = false;
            for (let i = 0; i < radios.length; i++) {
                if (radios[i].checked) {
                    any_one = true;
                    break;
                }
            }

            if (!any_one) {
                this.form_valid = false;
                x.querySelector(".error-text").textContent = "Check any one radio";
                x.classList.add("form-error");
                x.querySelectorAll(".field-check").forEach((radio) => {
                    radio.onchange = function (evt) {
                        x.querySelector(".error-text").textContent = "";
                        x.classList.remove("form-error");
                    }
                });
            } else {
                x.querySelector(".error-text").textContent = "";
                x.classList.remove("form-error");
            }
            this.process_count--;
        }

        this.ajax_select_check = function (x) {
            let value = x.querySelector(".ajaxselectBox_Value").value;
            if (value === null || value === undefined || value.toString().trim() === "") {
                this.form_valid = false;
                x.querySelector(".error-text").textContent = "Field is required";
                x.classList.add("form-error");
                x.querySelector(".ajaxselectBox_Search").onclick = function (evt) {
                    x.querySelector(".error-text").textContent = "";
                    x.classList.remove("form-error");
                }
            } else {
                x.querySelector(".error-text").textContent = "";
                x.classList.remove("form-error");
            }
            this.process_count--;
        }


        this.validation_start = function () {
            // console.log(this.element);
            this.form_groups = this.element.querySelectorAll(".form-group:not(.field-no-validate)");
            this.form_valid = true;
            this.ajax_queue = [];
            this.process_count = 0;
            for (let i = 0; i < this.form_groups.length; i++) {
                let classlist = this.form_groups[i].classList;
                if (classlist.contains("field-required")) {
                    this.process_count++;
                    this.required_check(this.form_groups[i]);
                }
                if (classlist.contains("field-email")) {
                    this.process_count++;
                    this.email_check(this.form_groups[i]);
                }
                if (classlist.contains("field-phone")) {
                    this.process_count++;
                    this.phone_check(this.form_groups[i]);
                }
                if (classlist.contains("field-number")) {
                    this.process_count++;
                    this.number_check(this.form_groups[i]);
                }
                // if(classlist.contains("field-money")){
                //     this.process_count++;
                //     this.money_check(this.form_groups[i]);
                // }
                if (classlist.contains("field-checked")) {
                    this.process_count++;
                    this.checkbox_check(this.form_groups[i]);
                }
                if (classlist.contains("field-checked-any")) {
                    this.process_count++;
                    this.radio_check(this.form_groups[i]);
                }
                if (classlist.contains("field-ajax-select")) {
                    this.process_count++;
                    this.ajax_select_check(this.form_groups[i]);
                }
                if (classlist.contains("field-ajax")) {
                    this.process_count++;
                    this.ajax_queue.push({ elem: this.form_groups[i], url: this.form_groups[i].getAttribute("data-ajax-url") });
                }
            }
            this.invoke_ajax_validation();
        }

        this.set_ajax_handler = function (param) {
            let ajax_url = param.url;
            let elem = param.elem;
            let xhr = new XMLHttpRequest();
            let data = elem.querySelector(".field-check").value;

            elem.querySelector(".field-check").onkeyup = function (evt) {
                elem.querySelector(".error-text").textContent = "";
                elem.classList.remove("form-error");
            }

            if (data != null && data != undefined && data.trim() != "") {
                let that = this;
                xhr.open("GET", ajax_url + "data=" + data, true);
                xhr.send(null);
                xhr.onreadystatechange = function (evt) {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        let resp = JSON.parse(xhr.responseText);
                        if (resp['valid'] == 1) {
                            elem.querySelector(".error-text").textContent = "";
                            elem.classList.remove("form-error");
                        } else {
                            elem.querySelector(".error-text").textContent = resp['msg'];
                            elem.classList.add("form-error");
                            that.form_valid = false;
                        }
                        that.process_count--;
                    }
                }
            } else {
                elem.querySelector(".error-text").textContent = 'Field is required';
                elem.classList.add("form-error");
                this.form_valid = false;
                this.process_count--;
            }
        }

        this.invoke_ajax_validation = function () {
            for (let i = 0; i < this.ajax_queue.length; i++) {
                this.set_ajax_handler(this.ajax_queue[i]);
            }
        }
    }

    FormValidate.prototype.validate = function (success, error, params) {
        this.validation_start();
        let timer = setInterval(() => {
            if (this.process_count == 0) {
                if (this.form_valid) {
                    success(params);
                } else {
                    params['valid'] = this.form_valid;
                    error(params);
                }
                clearInterval(timer);
            }
        }, this.max_wait);
    }

    /*************FORM VALIDATOR ENDS*************** */



    function AjaxSelectBox(elem) {
        this.element = elem;
        this.window_state = false;
        this.xhr = new XMLHttpRequest();
        this.close_btn = this.element.querySelector(".ajaxselectBoxBtn .close");
        this.drops_btn = this.element.querySelector(".ajaxselectBoxBtn .drops");
        this.drops_btn = this.element.querySelector(".ajaxselectBoxBtn");
        this.textflow = this.element.querySelector(".ajaxselectBoxBtn .textFlow");
        this.ajaxsearch = this.element.querySelector(".ajaxselectBox_Search");
        this.result_holder = this.element.querySelector(".ajaxselectBox_Container ul");
        this.value_holder = this.element.querySelector(".ajaxselectBoxBtn .ajaxselectBox_Value");
        this.listener = null;
        this.params = {};

        this.show_container = function () {
            this.element.querySelector(".ajaxselectBox_Container").style.display = "block";
            this.drops_btn.classList.add("active");
        }

        this.hide_container = function () {
            this.element.querySelector(".ajaxselectBox_Container").style.display = "none";
            this.ajaxsearch.value = "";
            this.drops_btn.classList.remove("active");
        }

        this.fetch_results = function (search) {
            let ajax_url = this.element.getAttribute("data-ajax-url");
            let add_para = this.element.getAttribute("data-add-para");
            if(add_para != null || add_para != undefined){
                ajax_url += "?search=" + search+ add_para;
            }else{
                ajax_url += "?search=" + search;
            }
            console.log("ajax_url");
            console.log(ajax_url);
            this.xhr.open("GET", ajax_url, true);
            this.xhr.send(null);
            let that = this;
            this.xhr.onreadystatechange = function (evt) {
                if (that.xhr.readyState == 4 && that.xhr.status == 200) {
                    let resp = JSON.parse(that.xhr.responseText);
                    let result = ``;
                    if (resp['error'] == 0) {
                        let data = resp['data'];
                        for (let i = 0; i < data.length; i++) {
                            let extra = [];
                            if (data[i]['extra'] !== null && data[i]['extra'] !== undefined && data[i]['extra'] !== "") {
                                extra = JSON.stringify(data[i]['extra']);
                            }
                            result += `<li role="option" data-value="` + data[i]['key'] + `" data-extra='` + extra + `' >` + data[i]['value'] + `</li>`;
                        }
                    }
                    if (result === "") {
                        result = `<span>no results for "` + search + `" </span>`;
                    }
                    that.result_holder.innerHTML = result;
                }
            }
        }

        this.construct = () => {
            let value = this.value_holder.value;
            if (value !== null && value !== undefined && value !== "") {
                this.close_btn.style.display = "inline-block";
                this.drops_btn.classList.add("active");
            } else {
                this.close_btn.style.display = "none";
                this.drops_btn.classList.remove("active");
                this.textflow.textContent = this.textflow.getAttribute("data-default");
            }
            this.result_holder.innerHTML = "";
        }

        this.get_container = () => {
            return this.element;
        }

        this.shutdown = () => {
            this.hide_container();
            this.window_state = false;
        }
    }

    AjaxSelectBox.prototype.add_listener = function (cb, params) {
        this.listener = cb;
        this.params = params;
    }

    AjaxSelectBox.prototype.init = function () {
        let that = this;
        this.ajaxsearch.onkeyup = function (evt) {
            let value = that.ajaxsearch.value;
            that.fetch_results(value);
        }
        this.drops_btn.onclick = function (evt) {
            console.log("I am clicked");
            if (that.window_state) {
                that.hide_container();
                that.window_state = false;
            } else {
                that.show_container();
                that.window_state = true;
            }
        }

        this.close_btn.onclick = function (evt) {
            that.value_holder.value = "";
            that.textflow.textContent = that.textflow.getAttribute("data-default");
            that.close_btn.style.display = "none";
            if (that.listener != null && typeof that.listener === "function") {
                that.params['value'] = "";
                that.params['extra'] = "";
                that.listener(that.params);
            }
        }

        this.result_holder.onclick = function (evt) {
            let target = evt.target;
            that.result_holder.querySelectorAll("li").forEach((item) => {
                if (item.contains(target)) {
                    that.value_holder.value = item.getAttribute("data-value");
                    that.value_holder.setAttribute("value", item.getAttribute("data-value"));
                    that.textflow.textContent = item.textContent;

                    if (that.listener != null && typeof that.listener === "function") {
                        that.params['value'] = item.getAttribute("data-value");
                        let json = item.getAttribute("data-extra");
                        if (json !== null && json !== undefined && json !== "") {
                            that.params['extra'] = JSON.parse(json);
                        }
                        that.listener(that.params);
                    }
                }
            });
            that.close_btn.style.display = "inline-block";
            that.hide_container();
            that.window_state = false;
            that.drops_btn.classList.add("active");
        }
    }


    function SelectBox(elem) {
        this.element = elem;
        this.window_state = false;
        this.close_btn = this.element.querySelector(".selectBoxBtn .close");
        this.drops_btn = this.element.querySelector(".selectBoxBtn .drops");
        this.drops_btn = this.element.querySelector(".selectBoxBtn");
        this.textflow = this.element.querySelector(".selectBoxBtn .textFlow");
        this.result_holder = this.element.querySelector(".selectBox_Container");
        this.value_holder = this.element.querySelector(".selectBoxBtn .selectBox_Value");
        this.listener = null;
        this.params = {};

        this.show_container = function () {
            this.element.querySelector(".selectBox_Container").style.display = "block";
            this.drops_btn.classList.add("active");
        }

        this.hide_container = function () {
            this.element.querySelector(".selectBox_Container").style.display = "none";
            this.drops_btn.classList.remove("active");
        }

        this.construct = function () {
            let value = this.value_holder.value;
            if (value != null && value != undefined && value.trim() != "") {
                if (this.close_btn !== null && this.close_btn !== undefined) {
                    this.close_btn.style.display = "inline-block";
                }
                this.result_holder.querySelectorAll("li").forEach((item) => {
                    let check_value = item.getAttribute("data-value");
                    if (check_value == value) {
                        this.textflow.textContent = item.textContent;
                    }
                });
            } else {
                if (this.close_btn !== null && this.close_btn !== undefined) {
                    this.close_btn.style.display = "none";
                }
                this.textflow.textContent = this.textflow.getAttribute("data-default");
            }
        }

        this.get_container = () => {
            return this.element;
        }

        this.shutdown = () => {
            this.hide_container();
            this.window_state = false;
        }
    }

    SelectBox.prototype.add_listener = function (cb, params) {
        this.listener = cb;
        this.params = params;
    }

    SelectBox.prototype.init = function () {
        this.drops_btn.onclick = (evt) => {
            if (this.window_state) {
                this.hide_container();
                this.window_state = false;
            } else {
                this.show_container();
                this.window_state = true;
            }
        }

        if (this.close_btn !== null && this.close_btn !== undefined) {
            this.close_btn.onclick = (evt) => {
                this.value_holder.value = "";
                this.textflow.textContent = this.textflow.getAttribute("data-default");
                this.close_btn.style.display = "none";

                if (this.listener != null && typeof this.listener === "function") {
                    this.params['value'] = "";
                    this.params['extra'] = "";
                    this.listener(this.params);
                }
            }
        }

        this.result_holder.querySelectorAll("li").forEach((item) => {
            item.onclick = (evt) => {
                this.value_holder.value = item.getAttribute("data-value");
                this.value_holder.setAttribute("value", item.getAttribute("data-value"));
                this.textflow.textContent = item.textContent;

                if (this.listener != null && typeof this.listener === "function") {
                    this.params['value'] = item.getAttribute("data-value");
                    let json = item.getAttribute("data-extra");
                    if (json !== null && json !== undefined && json !== "") {
                        this.params['extra'] = JSON.parse(json);
                    }
                    this.listener(this.params);
                }
                if (this.close_btn !== null && this.close_btn !== undefined) {
                    this.close_btn.style.display = "inline-block";
                }
                this.hide_container();
                this.window_state = false;
                this.drops_btn.classList.add("active");
            }
        });
        this.construct();
    }


    function MultiSelectBox(elem) {
        this.element = elem;
        this.window_state = false;
        this.drops_btn = this.element.querySelector(".multiSelectBoxBtn .drops2");
        this.textflow = this.element.querySelector(".multiSelectBoxBtn .Multi_InputContainer");
        this.drops_btn = this.element.querySelector(".multiSelectBoxBtn");
        this.container = this.element.querySelector(".MultiselectBox_Container");
        this.value_holder = this.element.querySelector(".multiSelectBoxBtn .multiSelectInput");
        this.value_array = [];
        this.text_array = [];
        this.listener = null;
        this.params = {};

        this.show_container = function () {
            this.container.style.display = "block";
            this.window_state = true;
            this.drops_btn.classList.add("active");
        }

        this.hide_container = function () {
            this.container.style.display = "none";
            this.window_state = false;
            this.drops_btn.classList.remove("active");
        }

        this.remove_value = function (value) {
            let index = -1;
            for (let i = 0; i < this.value_array.length; i++) {
                if (this.value_array[i] === value) {
                    index = i;
                    break;
                }
            }
            if (index != -1) {
                this.value_array.splice(index, 1);
                this.value_holder.value = this.value_array.join(",");
            }
        }

        this.add_value = function (value) {
            if (value !== "") {
                this.value_array.push(value);
                this.value_holder.value = this.value_array.join(",");
            }
        }

        this.remove_text = function (text) {
            let index = -1;
            for (let i = 0; i < this.text_array.length; i++) {
                if (this.text_array[i] === text) {
                    index = i;
                    break;
                }
            }
            if (index != -1) {
                this.text_array.splice(index, 1);
                if (this.text_array.length <= 0) {
                    this.textflow.textContent = this.textflow.getAttribute("data-default");
                } else {
                    this.textflow.textContent = this.text_array.join(",");
                }
            }
        }

        this.add_text = function (text) {
            this.text_array.push(text);
            this.textflow.textContent = this.text_array.join(",");
        }

        this.construct_value_array = function () {
            let list_of_ids = this.value_holder.value;
            if (list_of_ids !== null && list_of_ids !== undefined && list_of_ids !== "") {
                this.value_array = this.value_holder.value.split(",");
                this.container.querySelectorAll("input").forEach((item) => {
                    for (let i = 0; i < this.value_array.length; i++) {
                        if (item.getAttribute("data-value") === this.value_array[i]) {
                            item.checked = true;
                            item.parentElement.classList.add("active");
                            this.add_text(item.parentElement.textContent);
                            break;
                        }
                    }
                });
            }
        }

        this.get_container = () => {
            return this.element;
        }

        this.shutdown = () => {
            this.hide_container();
        }
    }

    MultiSelectBox.prototype.add_listener = function (cb, params) {
        this.listener = cb;
        this.params = params;
    }

    MultiSelectBox.prototype.init = function () {
        let that = this;
        this.drops_btn.onclick = function (evt) {
            if (that.window_state) {
                that.hide_container();
            } else {
                that.show_container();
            }
        }
        this.container.querySelectorAll("input").forEach((item) => {
            item.onchange = function (evt) {
                if (item.checked) {
                    that.add_value(item.getAttribute("data-value"));
                    that.add_text(item.parentElement.textContent);
                    item.parentElement.classList.remove("active");
                } else {
                    that.remove_value(item.getAttribute("data-value"));
                    that.remove_text(item.parentElement.textContent);
                    item.parentElement.classList.remove("active");
                }
                if (that.listener != null && typeof that.listener === "function") {
                    that.params['value'] = that.value_array;
                    console.log(that.params);
                    that.listener(that.params);
                }
            }
        });
        this.construct_value_array();
    }

    function DataTable(elem, config) {
        this.element = elem;

        this.columnNames = config['columnNames'];
        this.is_sortable = config['sortable'];
        this.page_limit = 10;
        this.total_rows = 0;
        this.current_page = 0;
        this.page_rows = 0;

        this.paint_area = this.element.querySelector(".table-paint-area");
        this.pagination = this.element.querySelector(".tableFooterRight");
        this.prev_btn = this.pagination.querySelector(".dt-prev-btn");
        this.next_btn = this.pagination.querySelector(".dt-next-btn");
        this.thead = this.element.querySelector('thead');
        this.ajax_url = this.element.getAttribute("data-ajax-url");
        this.get_vars = [];
        this.get_vars['limit'] = this.page_limit;
        this.get_vars['offset'] = this.current_page;
        this.ajax_processing = false;

        this.export_links = this.element.querySelectorAll(".export_container .exportUl a");

        this.apply_bulk_action = false;

        this.listeners = [];

        if (config['filters'] != null && config['filters'] != undefined) {
            let filters = config['filters'];
            for (let i = 0; i < filters.length; i++) {
                this.get_vars[filters[i]] = "";
            }
        }

        this.fetch_data = function (url) {
            if (!this.ajax_processing) {
                this.ajax_processing = true;
                let xhr = new XMLHttpRequest();
                xhr.open("GET", url, true);
                xhr.send(null);
                let that = this;
                xhr.onreadystatechange = function (evt) {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        //datatable response
                        // console.log(xhr.responseText);
                        let resp = JSON.parse(xhr.responseText);
                        that.page_rows = resp['data'].length;
                        that.load_pagination(resp['total_rows']);
                        if (resp['data'].length > 0) {
                            that.load_data(resp['data']);
                        } else {
                            document.querySelector('.table-paint-area').innerHTML = "";
                        }
                        that.ajax_processing = false;
                    }
                }
            }
        }

        this.paint_no_data = function () {

        }

        this.load_pagination = function (data) {
            if (this.ajax_processing) {
                this.total_rows = parseInt(data);
                this.pagination.querySelector(".dt-total-rows").textContent = data;
                let start_offset = this.current_page + 1;
                let end_offset = this.current_page + this.page_rows;
                this.current_page = end_offset;
                this.pagination.querySelector(".dt-page-start").textContent = start_offset;
                this.pagination.querySelector(".dt-page-end").textContent = end_offset;
                if (start_offset == 1) {
                    this.prev_btn.classList.add("dt-disabled");
                } else {
                    this.prev_btn.classList.remove("dt-disabled");
                }
                if (end_offset >= this.total_rows) {
                    this.next_btn.classList.add("dt-disabled");
                } else {
                    this.next_btn.classList.remove("dt-disabled");
                }
            }
        }

        this.init_pagination = function () {
            let that = this;
            this.prev_btn.onclick = function (evt) {
                if (!that.prev_btn.classList.contains("dt-disabled") && !that.ajax_processing) {
                    that.get_vars['offset'] = that.current_page - that.page_limit - that.page_rows;
                    that.current_page = that.get_vars['offset'];
                    that.paint_table();
                }
            }

            this.next_btn.onclick = function (evt) {
                if (!that.next_btn.classList.contains("dt-disabled") && !that.ajax_processing) {
                    that.get_vars['offset'] = that.current_page;
                    that.current_page = that.get_vars['offset'];
                    that.paint_table();
                }
            }
        }

        this.load_data = function (data) {
            let fragment = new DocumentFragment();
            for (let i = 0; i < data.length; i++) {
                let tr = document.createElement("tr");
                let td = document.createElement("td");
                td.innerHTML = "<input type='checkbox' class='dt-checkbox-child' value='" + data[i][0] + "' />"
                tr.append(td);
                for (let j = 1; j < data[i].length; j++) {
                    td = document.createElement("td");
                    td.innerHTML = data[i][j];
                    tr.append(td);
                }
                fragment.append(tr);
            }
            this.paint_area.innerHTML = "";
            this.paint_area.append(fragment);

            //Listeners invoked at right time after dom gets updated
            for (let i = 0; i < this.listeners.length; i++) {
                let cb = this.listeners[i].cb || null;
                let params = this.listeners[i].params || {};
                if (cb !== null && typeof cb === "function") {
                    cb(params);
                }
            }
        }

        this.paint_table = function () {
            let url = this.ajax_url;
            let query_str = "";
            for (prop in this.get_vars) {
                query_str += prop + "=" + this.get_vars[prop] + "&";
            }
            url += query_str;
            this.export_links.forEach((item) => {
                let href = item.getAttribute("data-default-href");
                href += query_str;
                item.setAttribute("href", href);
            });
            this.fetch_data(url);
        }

        this.sortable = function (col) {
            let that = this;
            col.classList.add("no-sort");
            col.onclick = function (evt) {
                if (col.classList.contains("no-sort")) {
                    that.get_vars['orderby'] = "asc";
                    that.get_vars['ordercol'] = col.getAttribute("data-col-name");
                    col.querySelector("a > i").classList.add("fa-long-arrow-up");
                    col.querySelector("a > i").classList.remove("fa-long-arrow-down");
                    col.classList.add("sort-up");
                    col.classList.remove("no-sort");
                } else if (col.classList.contains("sort-up")) {
                    that.get_vars['orderby'] = "desc";
                    that.get_vars['ordercol'] = col.getAttribute("data-col-name");
                    col.querySelector("a > i").classList.remove("fa-long-arrow-up");
                    col.querySelector("a > i").classList.add("fa-long-arrow-down");
                    col.classList.remove("sort-up");
                    col.classList.add("sort-down");
                } else if (col.classList.contains("sort-down")) {
                    that.get_vars['orderby'] = "asc";
                    that.get_vars['ordercol'] = col.getAttribute("data-col-name");
                    col.querySelector("a > i").classList.add("fa-long-arrow-up");
                    col.querySelector("a > i").classList.remove("fa-long-arrow-down");
                    col.classList.add("sort-up");
                    col.classList.remove("sort-down");
                }

                that.thead.querySelectorAll(".dt-sortable a > i").forEach((itag) => {
                    if (!col.contains(itag)) {
                        itag.classList.add("fa-long-arrow-up");
                        itag.classList.remove("fa-long-arrow-down");
                        let col_classlist = itag.parentElement.parentElement.classList;
                        col_classlist.add("no-sort");
                        col_classlist.remove("sort-up");
                        col_classlist.remove("sort-down");
                    }
                });
                that.current_page = 0;
                that.page_rows = 0;
                that.total_rows = 0;
                that.get_vars['offset'] = 0;
                that.paint_table();
            }
        }

        this.select_filter = function (params) {
            this.get_vars[params['column']] = params['value'];
            this.current_page = 0;
            this.page_rows = 0;
            this.total_rows = 0;
            this.get_vars['offset'] = 0;
            this.paint_table();
        }

        this.ajaxselect_filter = function (params) {

        }

        this.multiselect_filter = function (params) {
            this.get_vars[params['column']] = params['value'];
            this.current_page = 0;
            this.page_rows = 0;
            this.total_rows = 0;
            this.get_vars['offset'] = 0;
            this.paint_table();
        }

        this.input_filter = function (params) {
            this.get_vars[params['column']] = params['value'];
            this.current_page = 0;
            this.page_rows = 0;
            this.total_rows = 0;
            this.get_vars['offset'] = 0;
            this.paint_table();
        }

        this.checkbox_filter = function (params) {
            this.get_vars[params['column']] = params['value'];
            this.current_page = 0;
            this.page_rows = 0;
            this.total_rows = 0;
            this.get_vars['offset'] = 0;
            this.paint_table();
        }

        this.radio_filter = function () {

        }

        this.toggle_checkbox_select = function (parent) {
            let that = this;
            parent.onclick = function (evt) {
                let checked = evt.target.checked;
                that.element.querySelectorAll(".dt-checkbox-child").forEach((item) => {
                    item.checked = checked;
                    item.onclick = function (evt) {
                        if (!item.checked) {
                            parent.checked = false;
                        }
                    }
                });
            }
        }

        this.setup_search = function () {
            let search_elem = this.element.querySelector(".dt-search");
            let that = this;
            if (search_elem !== null && search_elem !== undefined) {
                search_elem.onkeydown = function (evt) {
                    if (evt.keyCode === 13) {
                        let search = search_elem.value;
                        console.log("hit");
                        console.log(search);
                        that.get_vars['search'] = search;
                        that.current_page = 0;
                        that.page_rows = 0;
                        that.total_rows = 0;
                        that.get_vars['offset'] = 0;
                        that.paint_table();
                    }
                }
            }
        }

        this.rows_per_page = (params) => {
            this.current_page = 0;
            this.page_rows = 0;
            this.total_rows = 0;
            this.page_limit = params['value'];
            this.get_vars['offset'] = 0;
            this.get_vars['limit'] = params['value'];
            this.paint_table();
        }

        this.filter = (params) => {
            switch (params['type']) {
                case "select":
                    this.select_filter(params);
                    break;
                case "multiselect":
                    this.multiselect_filter(params);
                    break;
                case "checkbox":
                    this.checkbox_filter(params);
                    break;
                case "input":
                    this.input_filter(params);
                    break;
                default:
                    break;
            }
        }

    }

    DataTable.prototype.init = function () {
        this.setup_search();
        this.init_pagination();

        let tr = document.createElement('tr');
        let th = document.createElement("th");
        let checkbox = document.createElement("input");
        checkbox.setAttribute("class", "dt-checkbox-parent");
        checkbox.setAttribute("type", "checkbox");
        this.toggle_checkbox_select(checkbox);
        th.append(checkbox);
        tr.append(th);
        for (let i = 0; i < this.columnNames.length; i++) {
            th = document.createElement("th");
            th.setAttribute("data-col-name", this.columnNames[i]);
            if (this.is_sortable[i] == 1) {
                th.innerHTML = "<a type='button'>" + this.columnNames[i] + " <i class='fa fa-long-arrow-up'></i></a>";
                th.classList.add("dt-sortable");
                this.sortable(th);
            } else {
                th.innerHTML = "<a type='button'>" + this.columnNames[i] + " </a>";
            }
            tr.append(th);
        }
        this.thead.innerHTML = "";
        this.thead.append(tr);

        this.paint_table();
    }

    DataTable.prototype.add_listener = function (listener) {
        this.listeners.push(listener);
    }

    function ModalAlert() {
        this.element = document.querySelector(".ModalAlert");
        this.close_btn = this.element.querySelector("a");
        this.msg_box = this.element.querySelector(".textFlow");

        this.show_success_alert = function (msg) {
            this.msg_box.classList.add('st_success');
            this.msg_box.classList.remove('st_danger');
            this.msg_box.textContent = msg;
            this.element.style.display = "block";
        }

        this.show_error_alert = function (msg) {
            this.msg_box.classList.add('st_danger');
            this.msg_box.classList.remove('st_success');
            this.msg_box.textContent = msg;
            this.element.style.display = "block";
        }

        this.close_alert = function () {
            this.element.style.display = "none";
        }
    }

    ModalAlert.prototype.invoke_alert = function (msg, type) {
        let timer = null;
        if (type === "success") {
            this.show_success_alert(msg);
        } else {
            this.show_error_alert(msg);
        }
        timer = setTimeout(() => {
            this.close_alert();
        }, 2500);
        this.close_btn.onclick = (evt) => {
            this.close_alert();
            if (timer !== null) {   
                clearTimeout(timer);
                timer = null;
            }
        }
    }

    /*************FILE UPLOADER*************** */
    function FileUploader(elem) {
        this.element = elem;
        this.dropzone = this.element.querySelector(".file-uploader-box");
        this.error_holder = this.element.querySelector(".file-uploader-error");
        this.file_input = this.element.querySelector(".file-uploader-input");

        this.file_progessbar = this.element.querySelector(".file-uploader-progessbar");
        this.progressbar = this.element.querySelector(".file-uploader-progessbar .progressbar");
        this.timer = null;
        //bytes
        this.maxfilesize = 0;
        this.maxfilesize_humanreadable = "";
        this.files_allowed = [];
        this.filesize = 0;

        //Only  for Multiple Preview Upload
        this.files = [];
        this.max_files_limit = 5;
        this.multiple_preview_input = this.element.querySelector(".multiple-preview-uploader-input");
        this.file_preview_box = this.element.querySelector(".file-uploader-preview");
        this.preview_delete_processing = false;

        this.listener = null;

        this.init = function () {
            this.dropzone.onclick = (evt) => {
                this.error_holder.textContent = "";
                this.file_input.click();
            }
            this.dropzone.ondragenter = (evt) => {
                evt.preventDefault();
                evt.stopPropagation();
            }
            this.dropzone.ondragover = (evt) => {
                evt.preventDefault();
                evt.stopPropagation();
            }
        }

        this.fu_invoke_progressbar = function () {
            this.file_progessbar.style.display = "block";
            let speed_factor = 1000;
            let time = this.filesize / speed_factor;
            this.timer = setInterval(() => {
                let curr_width_str = this.progressbar.style.width;
                let curr_width = 0;
                if (curr_width_str === null || curr_width_str === undefined || curr_width_str === "") {
                    curr_width = 0;
                } else {
                    curr_width = parseInt(curr_width_str.substring(0, curr_width_str.indexOf("%")));
                }
                curr_width++;
                if (curr_width <= 80) {
                    this.progressbar.style.width = curr_width + "%";
                }
            }, time);
        }

        this.fu_destroy_progressbar = function () {
            this.file_progessbar.style.display = "none";
            this.progressbar.style.width = "0%";
            clearInterval(this.timer);
            this.timer = null;
        }

        this.fu_reset_uploader = function () {
            this.filesize = 0;
            this.file_input.value = "";
        }

        this.fu_ajax_full_upload = function (files) {
            if (files.length > 0) {
                let file = files[0];
                if (file.size > this.maxfilesize) {
                    this.error_holder.textContent = "Max file size allowed is " + this.maxfilesize_humanreadable;
                } else {
                    let allow = false;
                    for (let i = 0; i < this.files_allowed.length; i++) {
                        if (file.type == this.files_allowed[i]) {
                            allow = true;
                            break;
                        }
                    }
                    if (allow) {
                        let xhr = null;
                        if (window.ActiveXObject) {
                            xhr = new ActiveXObject("Msxml2.XMLHTTP");
                        } else if (window.XMLHttpRequest) {
                            xhr = new XMLHttpRequest();
                        } else {
                            this.error_holder.textContent = "XHR is not supported ";
                        }
                        if (xhr !== null || xhr !== undefined) {
                            this.filesize = file.size;
                            xhr.open("POST", this.element.getAttribute("data-ajax-url"), true);
                            let formdata = new FormData();
                            formdata.set("attachment", file);
                            this.fu_invoke_progressbar();
                            xhr.send(formdata);
                            xhr.onreadystatechange = (evt) => {
                                if (xhr.readyState == 4) {
                                    if (xhr.status == 200) {
                                        let json = JSON.parse(xhr.responseText);
                                        if (json['error'] == 1) {
                                            this.error_holder.textContent = json['reason'];
                                        } else {
                                            if (this.listener !== null && typeof this.listener === "function") {
                                                this.listener(json);
                                            }
                                        }
                                    } else {
                                        this.error_holder.textContent = "Network Error";
                                    }
                                    this.progressbar.style.width = "100%";
                                    this.fu_reset_uploader();
                                    setTimeout(() => {
                                        this.fu_destroy_progressbar();
                                    }, 500);
                                }
                            }
                        }
                    } else {
                        this.error_holder.textContent = "File format is not allowed";
                    }
                }
            } else {
                this.error_holder.textContent = "No file uploaded";
            }
        }

        this.fu_multiple_preview_upload = function (files) {
            if (files.length > 0) {
                let file = files[0];
                if (file.size > this.maxfilesize) {
                    this.error_holder.textContent = "Max file size allowed is " + this.maxfilesize_humanreadable;
                } else {
                    let allow = false;
                    for (let i = 0; i < this.files_allowed.length; i++) {
                        if (file.type == this.files_allowed[i]) {
                            allow = true;
                            break;
                        }
                    }
                    if (allow) {
                        filename = file.name;
                        this.files.push(file);
                        let pos = this.files.length - 1;
                        let div = document.createElement("div");
                        div.classList.add("file-preview");
                        let preview = `
                            <span class="file-preview-name">`+ filename + `</span>
                            <button type="button" data-file-pos="`+ pos + `" class="btn bg-danger file-preview-remove"><i class="fa fa-trash" ></i></button>
                        `;
                        div.innerHTML = preview;
                        this.file_preview_box.append(div);

                        //can't directly create FileList
                        let transfer = new DataTransfer();
                        for (let i = 0; i < this.files.length; i++) {
                            transfer.items.add(this.files[i]);
                        }
                        this.multiple_preview_input.files = transfer.files;
                    } else {
                        this.error_holder.textContent = "File format is not allowed";
                    }
                }
            } else {
                this.error_holder.textContent = "No file uploaded";
            }
        }

        this.fu_multiple_preview_remove = function (elem) {
            let pos = parseInt(elem.getAttribute("data-file-pos"));
            this.files.splice(pos, 1);
            if (this.files.length == 0) {
                this.multiple_preview_input.value = "";
            } else {
                //can't directly create FileList
                let transfer = new DataTransfer();
                for (let i = 0; i < this.files.length; i++) {
                    transfer.items.add(this.files[i]);
                }
                this.multiple_preview_input.files = transfer.files;
            }
            elem.parentElement.remove();
            let items = this.file_preview_box.querySelectorAll(".file-preview");
            for (let i = 0; i < items.length; i++) {
                items[i].querySelector(".file-preview-remove").setAttribute("data-file-pos", i);
            }
            this.preview_delete_processing = false;
        }
    }

    FileUploader.prototype.ajaxFullUpload = function (config = {}) {
        this.maxfilesize = config.maxfilesize || (5 * 1024 * 1024);
        this.files_allowed = config.files_allowed || [];
        this.maxfilesize_humanreadable = config.maxfilesize_humanreadable || "5Mb";
        this.listener = config.listener || null;
        this.init();
        this.dropzone.ondrop = (evt) => {
            evt.preventDefault();
            evt.stopPropagation();
            let files = evt.dataTransfer.files;
            this.error_holder.textContent = "";
            this.fu_ajax_full_upload(files);
        }
        this.file_input.onchange = (evt) => {
            let files = this.file_input.files;
            this.fu_ajax_full_upload(files);
        }
    }

    FileUploader.prototype.multiplePreviewUpload = function (config = {}) {
        this.maxfilesize = config.maxfilesize || (5 * 1024 * 1024);
        this.files_allowed = config.files_allowed || [];
        this.maxfilesize_humanreadable = config.maxfilesize_humanreadable || "5Mb";
        this.listener = config.listener || null;
        this.init();
        this.dropzone.ondrop = (evt) => {
            evt.preventDefault();
            evt.stopPropagation();
            let files = evt.dataTransfer.files;
            this.error_holder.textContent = "";
            this.fu_multiple_preview_upload(files);
        }

        this.file_input.onchange = (evt) => {
            let files = this.file_input.files;
            this.fu_multiple_preview_upload(files);
        }

        this.file_preview_box.onclick = (evt) => {
            let target = evt.target;
            this.file_preview_box.querySelectorAll(".file-preview .file-preview-remove").forEach((item) => {
                if (item.contains(target)) {
                    if (!this.preview_delete_processing) {
                        this.preview_delete_processing = true;
                        this.fu_multiple_preview_remove(item);
                    }
                }
            });
        }
    }

    /*************FILE UPLOADER ENDS********* */

    /****************MODAL BOX************ */
    function ModalBox(elem) {
        this.element = elem;
        this.modal_body = this.element.querySelector(".modalbody");
        this.modal_body.style.opacity = 0;
        this.element.style.display = "none";
        this.modal_body.style.transition = "opacity 0.4s ease";
        this.modal_close = this.element.querySelector(".modalClose");
    }

    ModalBox.prototype.init = function () {
        this.element.onclick = (evt) => {
            let target = evt.target;
            if (!this.modal_body.contains(target)) {
                this.hide_modal();
            }
        }
        this.modal_close.onclick = (evt) => {
            this.hide_modal();
        }
    }

    ModalBox.prototype.show_modal = function () {
        this.element.style.display = "block";
        setTimeout(() => {
            this.modal_body.style.opacity = 1;
        }, 1);
    }

    ModalBox.prototype.hide_modal = function () {
        this.modal_body.style.opacity = 0;
        setTimeout(() => {
            this.element.style.display = "none";
        }, 250);
    }

    /************MODAL BOX ENDS************/

    /**********NOTIFICATION VIA SSE********/

    function Notifier(elem) {
        this.element = elem;
        this.sse_url = this.element.getAttribute("data-sse-url");
        this.invoker = this.element.querySelector(".notifier-invoker");
        this.msg_append_area = this.element.querySelector(".messageDropdown");
        this.clear_all_btn = this.element.querySelector(".notifier-clear-all");
        this.sse = null

        this.clear_all_setup = function () {
            this.clear_all_btn.onclick = (evt) => {
                evt.preventDefault();
                evt.stopPropagation();
                let boxes = this.msg_append_area.querySelectorAll("li");
                for (let i = 1; i < boxes.length - 1; i++) {
                    boxes[i].remove();
                }
            };
        }

        this.sse_setup = function () {
            if (window.EventSource) {
                this.sse = new EventSource(this.sse_url);
                this.sse.addEventListener("pushes", (event) => {
                    let data = JSON.parse(event.data);
                    this.update_area(data);
                })
                //STANDARD SSE EVENTS
                this.sse.addEventListener("open", (event) => {

                });
                this.sse.addEventListener("error", (event) => {

                });
            }
        }

        this.shutdown = function () {
            if (this.sse !== null) {
                this.sse.close();
            }
        }

        this.update_area = function (json) {
            let area = this.msg_append_area.querySelector("li:nth-child(1)");
            for (let i = 0; i < json.length; i++) {
                let li = document.createElement('li');
                let a = `<a href="` + json[i]['link'] + `" >
                        <h4 class="textFlow">`+ json[i]['title'] + `</h4>
                        <p>`+ json[i]['text'] + `</p>
                    </a>`;
                li.innerHTML = a;
                area.insertAfter(li);
            }
            this.invoker.click();
        }
    }

    Notifier.prototype.init = function () {
        this.clear_all_setup();
        this.sse_setup();
        if (this.sse === null) {
            alert("your browser couldn't receive notification , update it");
        }
    }



}

