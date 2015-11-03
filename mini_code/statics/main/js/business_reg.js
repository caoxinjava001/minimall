/**
 * Created by tony on 15-4-23.
 */
;
(function (window) {
    var curr_form="#xinnuo_reg", t,
        blank_reg = /(^\s+)|(\s+$)/g ,
        errorCode = "40006",
        _status="1",
        _msg = {msg: "", time: 2500, width: "300px", height: "150px"},
        login_success_code = "41001",member_id=$("#member_id").val();

        /**
         * params {form_name:"",elements:[{"ele_id":reg}]}
         */
        function validate() {
        var form, parent_node;
        form = ((typeof arguments[0]) == 'object') ? arguments[0] : {};
        parent_node = form.form_selector;


        //绑定元素的focus blur
        $.each(form.elements, function (i, o) {
            var  error_node= $(o.ele_selector).nextAll("span");
            if (o.ele_type == "text" || o.ele_type == "pwd" || o.ele_type == "repwd") {
                var self = $(o.ele_selector, parent_node), v;
                $(o.ele_selector, parent_node).on({
                    focus: function () {
                        error_node.removeClass("span4").addClass("span2");
                        error_node.html(o.tips["msg"]).fadeIn();
                    },
                    blur: function () {
                        //判断是否为空
                        v = self.val();
                        self.val($.trim(v));

                        if ($.trim(v)) {//不为空获取焦点需要校验，添加阴影
                            if (!o.ele_regular.test(v)) {//校验不通过，添加红色框框

                                error_node.removeClass("span2").addClass("span4");
                                error_node.html(o.tips["reg"]).fadeIn();
                            } else {//绿色
                                if (i == "pwd" || i == "repwd") {
                                    if (i == "pwd") {
                                        //获取repwd值
                                        var repwd_v = $(form.elements['repwd'].ele_selector).val();
                                        if (v && repwd_v) {
                                            if (v != repwd_v) {
                                                error_node.html(o.tips["to"]);
                                                error_node.removeClass("span2").addClass("span4").fadeIn();
                                                $(form.elements['repwd'].ele_selector).next("span").removeClass("span4").addClass("span2").fadeIn();
                                            } else {
                                                error_node.removeClass("span4").addClass("span2");
                                                $(form.elements['repwd'].ele_selector).next("span").removeClass("span4").addClass("span2");
                                                error_node.html(o.tips["msg"]).fadeOut();
                                                $(form.elements['repwd'].ele_selector).next("span").html("请填写确认密码").fadeOut();
                                            }
                                        }
                                    }
                                    if (i == "repwd") {
                                        var pwd_v = $(form.elements['pwd'].ele_selector).val();
                                        if (v && pwd_v) {
                                            if (v != pwd_v) {
                                                error_node.html(o.tips["to"]);
                                                error_node.removeClass("span2").addClass("span4").fadeIn();
                                                $(form.elements['pwd'].ele_selector).next("span").removeClass("span4").addClass("span2").fadeIn();
                                            } else {
                                                error_node.removeClass("span4").addClass("span2");
                                                $(form.elements['pwd'].ele_selector).next("span").removeClass("span4").addClass("span2");
                                                error_node.html(o.tips["msg"]).fadeOut();
                                                $(form.elements['pwd'].ele_selector).next("span").html("请填写密码").fadeOut();
                                            }
                                        }
                                    }
                                } else {
                                    error_node.html(o.tips["msg"]);
                                    error_node.removeClass("span4").addClass("span2").fadeOut();
                                }
                            }
                        } else {//空不校验去除红色样式
                            error_node.removeClass("span4").fadeOut();
                        }
                    }
                });
            }

            if (o.ele_type == "select") {
                $(o.ele_selector, parent_node).on({
                    change: function () {
                        if (!o.ele_regular(v)){
                            error_node.removeClass("span2").addClass("span4");
                            error_node.html(o.tips["reg"]).fadeIn();
                        } else {
                            error_node.removeClass("span4").addClass("span2");
                            error_node.html(o.tips["msg"]).fadeOut();
                        }
                    }
                });
            }

        });
        //绑定sub 按钮
        $(form.sub_btn, parent_node).on("click", function () {
            var _mark = [], p, v, username, pwd;
            _util.showAutoBg(true);
            $.each(form.elements, function (i, o) {
                var error_node=$(o.ele_selector).nextAll("span");
                if (o.ele_type == "text" || o.ele_type == "pwd" || o.ele_type == "repwd") {
                    v = $(o.ele_selector, parent_node).val();
                    $(o.ele_selector, parent_node).val($.trim(v));
                    if (!o.ele_regular.test(v)) {//校验不通过，添加红色框框
                        error_node.removeClass("span2").addClass("span4");
                        error_node.html(o.tips["reg"]).fadeIn();
                        _mark.push({"key_n": o.ele_selector, "key_v": false});
                        _util.hideAutoBg(true);
                        return false;
                    } else {
                        if (i == "pwd" || i == "repwd") {
                            if (i == "pwd") {
                                //获取repwd值
                                var repwd_v = $(form.elements['repwd'].ele_selector).val();
                                if (v && repwd_v) {
                                    if (v != repwd_v) {
                                        error_node.html(o.tips["to"]);
                                        error_node.removeClass("span2").addClass("span4").fadeIn();
                                        $(form.elements['repwd'].ele_selector).nextAll("span").removeClass("span2").addClass("span4").fadeIn();
                                        _mark.push({"key_n": o.ele_selector, "key_v": false});
                                        _util.hideAutoBg(true);
                                        return false;
                                    }
                                }
                            }
                            if (i == "repwd") {
                                var pwd_v = $(form.elements['pwd'].ele_selector).val();
                                if (v && pwd_v) {
                                    if (v != pwd_v) {
                                        error_node.html(o.tips["to"]);
                                        error_node.removeClass("span2").addClass("span4").fadeIn();
                                        $(form.elements['pwd'].ele_selector).nextAll("span").removeClass("span2").addClass("span4").fadeIn();
                                        _mark.push({"key_n": o.ele_selector, "key_v": false});
                                        _util.hideAutoBg(true);
                                        return false;
                                    }
                                }
                            }
                        }

                    }
                }

                if (o.ele_type == "select") {
                    v = $(o.ele_selector, parent_node).val();
                    if (!o.ele_regular(v)&&!$(o.ele_selector, parent_node).attr("no_select")) {
                        error_node.html(o.tips["reg"]);
                        error_node.removeClass("span2").addClass("span4").fadeIn();
                        _mark.push({"key_n": o.ele_selector, "key_v": false});
                        _util.hideAutoBg(true);
                        return false;
                    }
                }

            });

            if (_mark.length == 0) {
                //验证手机是否唯一
                p = {url: config.domain.wf + "/bs/is_single_mobile?", data: { mobile: $(form.elements["mobile"].ele_selector).val()}};
                _util.ajax(p, function (d) {
                    if (d&& d.status != _status) {
                        $(form.elements["mobile"].ele_selector).next("span").removeClass("span2").addClass("span4");
                        $(form.elements["mobile"].ele_selector).next("span").html(d.msg).fadeIn();
                        _util.hideAutoBg(true);
                        return false;
                    } else {
                        p.type="post";
                        p.url=config.domain.wf+"/bs/saveFirstAjax";
                        p.data=$(curr_form).serialize();
                        _util.ajax(p,function(d){
                            _util.hideAutoBg(true);
                            if(d&& d.status&& d.status==_status){
                                _msg.msg= d.msg;
                                _msg.callback=function(){
                                    _util.showAutoBg(true);
                                    window.location.href=config.domain.wf+"/bs/show_two?member_id="+ d.data;
                                }
                                _show_msg(_msg);

                            }else{
                                _msg.msg= d.msg;
                                _show_msg(_msg);
                            }

                        });
                    }
                });

            }

        });

    }

        var mb_reg = /^(((13[0-9]{1})|(14[0-9]{1})|(15[0-9]{1})|(18[0-9]{1})|(17[0-9]{1}))+\d{8})$/,
            pwd_reg = /^[\S]{6,20}$/,
            repwd_reg = /^[\S]{6,20}$/,
            name = /^[\S]{1,20}$/,
            org_name= /^[\S]{2,30}$/,
            select  = function(v){
                var valid = true;
                if(v=="0")valid= false;
                return valid;
            };
            if(member_id){
                pwd_reg = /^[\S]{6,20}$|^$/;
                repwd_reg = /^[\S]{6,20}$|^$/;
            }
        var v_data = {
            form_selector: curr_form,
            elements: {
                "name": {ele_selector: "[name='member_info[name]']", ele_type: "text", ele_regular: name, tips: {msg: "中英文均可，不超过20个字", reg: "请正确填写姓名"}},
                "intro_id": {ele_selector: "[name='member_info[intro_id]']", ele_type: "select", ele_regular: select, tips: {msg: "请选择您的介绍人", reg: "请选择您的介绍人"}},
                "pwd": {ele_selector: "[name='member_info[password]']", ele_type: "pwd", ele_regular: pwd_reg, tips: {msg: "请填写密码", reg: "请输入6-20位密码，区分大小写", to: "两次密码输入不一致，请重新输入！"}},
                "repwd": {ele_selector: "[name='member_info[repassword]']", ele_type: "repwd", ele_regular: repwd_reg, tips: {msg: "请填写确认密码", reg: "请输入6-20位密码，区分大小写", to: "两次密码输入不一致，请重新输入！"}},
                "mobile": {ele_selector: "[name='member_info[mobile]']", ele_type: "text", ele_regular: mb_reg, tips: {msg: "请填写手机号码", reg: "请正确填写手机号码"}},
                "org_name": {ele_selector: "[name='member_info[org_name]']", ele_type: "text", ele_regular: org_name, tips: {msg: "请填写机构名称", reg: "请正确填写机构名称"}},
            },
            sub_btn: "#regFormSubBtnC"
        };
        validate(v_data);

})(window);
