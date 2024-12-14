if(document.readyState==="loading"){

    window.addEventListener("click",(evt)=>{
        let target=evt.target;
        let login_options=document.querySelector(".logo-option-btn");
        if(login_options!==null && login_options!==undefined){
            if(login_options.contains(target)){
                document.querySelector(".login-options").style.display="block";
            }else{
                document.querySelector(".login-options").style.display="none";
            }
        }
    });

    function AlertBox(){
        className="";
        outer=this;
        alert=document.querySelector(".alert");
        timer=null;
        
        remove_alert=function(){
            alert.classList.remove("active");
            alert.querySelector(".alert-text").classList.remove(className);
        }

        get_and_set_type=function(type){
            switch(type){
                case "success":
                    className="alert-success";
                    break;
                case "danger":
                    className="alert-danger";
                    break;
                default:
                    className="alert-success";
                    break;
            }
            alert.querySelector(".alert-text").classList.add(className);
        }

        set_msg=function(msg){
            alert.querySelector(".alert-text > .al-text").textContent=msg;
        }
    }
    AlertBox.prototype.show_alert=function(msg,type){
        get_and_set_type(type);
        set_msg(msg);
        alert.classList.add("active");
        alert.querySelector(".alert-text > .alert-close").onclick=function(){
            remove_alert();
            if(timer!==null){
                clearTimeout(timer);
                timer=null;
            }
        };
        timer=setTimeout(()=>{
            remove_alert();
        },2500);
    }


    /********ERROR CLOSE BTN **********/
    let error_close_btns=document.querySelectorAll(".error-close-btn");
    if(error_close_btns!==null && error_close_btns!==undefined){
        error_close_btns.forEach((item)=>{
            item.onclick=function(evt){
                item.parentElement.remove();
            }
        })
    }

    function FormValidate(elem){
        this.element=elem;
        this.form_groups=elem.querySelectorAll(".form-group");
        this.ajax_queue=[];
        this.process_count=0;
        this.form_valid=true;
        //milliseconds
        this.max_wait=20;

        this.required_check=function(x){
            let value=x.querySelector(".field-check").value;
            if(value!==null && value!==undefined && value.trim()!==""){
                x.querySelector(".error-text").textContent="";
                x.classList.remove("form-error");
            }else{
                x.querySelector(".error-text").textContent="Field is required";
                x.classList.add("form-error");
                this.form_valid=false;
            }
            this.process_count--;
        }
        this.validation_start=function(){
            this.ajax_queue=[];
            this.process_count=0;
            this.form_valid=true;
            for(let i=0;i<this.form_groups.length;i++){
                let classlist=this.form_groups[i].classList;
                if(classlist.contains("field-required")){
                    this.process_count++;
                    this.required_check(this.form_groups[i]);
                }
                if(classlist.contains("field-ajax")){
                    this.process_count++;
                    this.ajax_queue.push({elem:this.form_groups[i],url:this.form_groups[i].getAttribute("data-ajax-url")});
                }
            }
            this.invoke_ajax_validation();
        }

        this.set_ajax_handler=function(param){
            let ajax_url=param.url;
            let elem=param.elem;
            let xhr=new XMLHttpRequest();
            let data=elem.querySelector(".field-check").value;
            let that=this;
            xhr.open("GET",ajax_url+"data="+data,true);
            xhr.send(null);
            xhr.onreadystatechange=function(evt){
                if(xhr.readyState==4 && xhr.status==200){
                    let resp=JSON.parse(xhr.responseText);
                    if(resp['valid']==1){
                        //elem.querySelector(".error-text").textContent="";
                        //elem.classList.remove("form-error");
                    }else{
                        elem.querySelector(".error-text").textContent=resp['msg'];
                        elem.classList.add("form-error");
                        that.form_valid=false;
                    }
                    that.process_count--;
                }
            }
        }

        this.invoke_ajax_validation=function(){
            for(let i=0;i<this.ajax_queue.length;i++){
                this.set_ajax_handler(this.ajax_queue[i]);
            }
        }
    }
    FormValidate.prototype.validate=function(success,error,params){
        this.validation_start();
        let timer=setInterval(()=>{
            if(this.process_count==0){
                if(this.form_valid){
                    success(params);
                }else{
                    error(params);
                }
                clearInterval(timer);
            }
        },this.max_wait);
    }

    function Toggler(elem){
        this.element=elem;

        this.changeState=function(status){
            let that=this;
            let xhr=new XMLHttpRequest();
            xhr.open("GET",this.element.getAttribute("data-ajax-url")+"status="+status,true);
            xhr.send(null);
            xhr.onreadystatechange=function(evt){
                if(xhr.readyState==4 && xhr.status==200){
                    let resp=JSON.parse(xhr.responseText);
                    if(resp['error']==1){
                        if(status==1){
                            that.element.querySelector("input[type=checkbox]").checked=false;
                        }else{
                            that.element.querySelector("input[type=checkbox]").checked=true;
                        }
                    }
                }
            }
        }
    }

    Toggler.prototype.init=function(){
        this.element.querySelector("input[type=checkbox]").onchange=(evt)=>{
            let status=0;
            if(evt.target.checked){
                status=1;
            }
            this.changeState(status);
        }
    }
}