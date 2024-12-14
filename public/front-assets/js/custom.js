if (document.readyState == "loading") {
    console.log("alert alert");
    function Alert() {
        this.ele = document.querySelector('.Alert');
        this.content = document.querySelector('.alert_messsage');
        this.close_btn = document.querySelector('.close_alert');

        this.set_success = function (msg) {
            this.ele.classList.add('success');
            this.ele.classList.remove('error');
            this.content.textContent = msg;
            console.log('message', msg);
        }

        this.set_error = function (msg) {
            this.ele.classList.add('error');
            this.ele.classList.remove('success');
            this.content.textContent = msg;
            console.log('message', msg);
        }

        this.close_alert = function (type) {
            this.ele.classList.remove(`${type}`);
        };
    }

    Alert.prototype.alert_invoke = function (type, message) {
        let timer = null;

        if (type == "success") {
            this.set_success(message);
        } else {
            this.set_error(message);
        }

        timer = setTimeout(() => {
            this.close_alert(type);
        }, 3000);

        this.close_btn.onclick = (e) => {
            this.close_alert(type);
            if (timer != null) {
                clearTimeout(timer);
                timer = null;
            }
        }

    }

    function FormValidate(elem) {
        this.element = elem;
        this.form_groups = null;
        this.ajax_queue = [];
        this.process_count = 0;
        this.form_valid = true;
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


}