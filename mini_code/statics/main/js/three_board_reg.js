/**
 * Created by tony on 15-4-23.
 */
;
(function (window) {
    var curr_form="#three_board", t,
        blank_reg = /(^\s+)|(\s+$)/g ,
        errorCode = "40006",
        _msg = {msg: "", time: 2500, width: "300px", height: "150px"},
        login_success_code = "41001";

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

            if (o.ele_type == "check_box") {
                $(o.ele_selector, parent_node).on({
                    click: function () {
                        if ($(this).prop("checked")) {
                            $("#cxy_phoneC").val(1);
                        } else {
                            $("#cxy_phoneC").val(0);
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
            _Util.showAutoBg(true);
            $.each(form.elements, function (i, o) {
                var error_node=$(o.ele_selector).nextAll("span");
                if (o.ele_type == "text" || o.ele_type == "pwd" || o.ele_type == "repwd") {
                    v = $(o.ele_selector, parent_node).val();
                    $(o.ele_selector, parent_node).val($.trim(v));
                    if (!o.ele_regular.test(v)) {//校验不通过，添加红色框框
                        error_node.removeClass("span2").addClass("span4");
                        error_node.html(o.tips["reg"]).fadeIn();
                        _mark.push({"key_n": o.ele_selector, "key_v": false});
                        _Util.hideAutoBg(true);
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
                                        _Util.hideAutoBg(true);
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
                                        _Util.hideAutoBg(true);
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
                        _Util.hideAutoBg(true);
                        return false;
                    }
                }
                if (o.ele_type == "check_box") {

                    if (!$(o.ele_selector, parent_node).prop("checked")) {
                        _show_msg(o.tips["reg"]);
                        _mark.push({"key_n": o.ele_selector, "key_v": false});
                        _Util.hideAutoBg(true);
                        return false;
                    }
                }

            });

            //TODO 校验完基础信息后判断手机和验证码
            if (_mark.length == 0) {
                //验证手机是否唯一
                p = {url: config.domain.edu + "/register/checkMobile?jsoncallback=?", data: {ajax: 1, mobile: $(form.elements["mobile"].ele_selector).val()}};
                edu.cm.service(p, function (d) {
                    if (d.code != errorCode) {
                        $(form.elements["mobile"].ele_selector).next("span").removeClass("span2").addClass("span4");
                        $(form.elements["mobile"].ele_selector).next("span").html(d.msg).fadeIn();
                        _Util.hideAutoBg(true);
                        return false;
                    } else {
                        //再验证验证码是否正确
                        p = {url: config.domain.edu + "/register/checkMobileCode?jsoncallback=?", data: {ajax: 1, code: $(form.elements["mob_code"].ele_selector).val(), mobile: $(form.elements["mobile"].ele_selector).val()}};
                        edu.cm.service(p, function (d) {
                            if (d.code != errorCode) {
                                $(form.elements["mob_code"].ele_selector).nextAll("span").removeClass("span2").addClass("span4");
                                $(form.elements["mob_code"].ele_selector).nextAll("span").html(d.msg).fadeIn();
                                _Util.hideAutoBg(true);
                                return false;
                            } else {//注册调用接口
                                p = {type: "post", url: config.domain.passport + "/register/actionReg", data: $(parent_node).serialize()};
                                edu.cm.service(p, function (d) {
                                    if (d && d.code && d.code ==errorCode) {//注册成功后再调用登录信息
                                        _Util.hideAutoBg(true);
                                        _Util.showAutoBg(".ok_dialog");
                                        setTimeout(function () {
                                            _Util.hideAutoBg(".ok_dialog");
                                            p = {type: "post", url: config.domain.login + '/login/ajaxHtmlLogin?jsoncallback=?'};
                                            username = $("[name='mobile']").val();
                                            pwd = $("[name='password']").val();
                                            p.data = {username: username, password: pwd, user_type: 1};
                                            edu.cm.service(p, function (d) {
                                                if (d.code == login_success_code) {
                                                    window.location.href = config.domain.home + "/course/index"
                                                } else {//登录失败 //TODO
                                                    _msg.msg = d.msg;
                                                    _show_msg(_msg);
                                                }
                                            });
                                        }, 2500);


                                    } else {//注册失败
                                        _Util.hideAutoBg(true);
                                        _show_msg(d.msg);
                                    }
                                });
                            }
                        });
                    }
                });

            }

        });

        var levels = $(form.elements["levels"].ele_selector, form.form_selector),
            profession= $(form.elements["profession"].ele_selector, form.form_selector),
            quan_arr=["\u8BC1\u5238\u516C\u53F8", "\u4F1A\u8BA1\u4E8B\u52A1\u6240", "\u5F8B\u5E08\u4E8B\u52A1\u6240"];
        levels.change(function(){
            var select=$(this).find("option:selected");
            for(var i=0;i<quan_arr.length;i++){
                if(select.text() == quan_arr[i]){
                    //隐藏
                    profession.attr("no_select","no").parent().hide();
                    break;
                }else{
                    profession.removeAttr("no_select").parent().show();
                }
            }
        });
    }

    var mb_reg = /^(((13[0-9]{1})|(14[0-9]{1})|(15[0-9]{1})|(18[0-9]{1})|(17[0-9]{1}))+\d{8})$/,
        pic_code = /^[\S]{5,6}$/,
        code_reg = /^[\S]{4,6}$/,
        pwd_reg = /^[\S]{6,20}$/,
        repwd_reg = /^[\S]{6,20}$/,
        nickname = /^[\S]{1,20}$/,
        company_name= /^[\S]{2,30}$/,
        email=/^([a-zA-Z0-9]+[_|\-|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\-|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/,
        select  = function(v){
            var valid = true;
            if(v=="0")valid= false;
            return valid;
        },
        protocol = function () {
            var valid = true, is_check;
            is_check = $("#regFormProtocolC", curr_form).prop("checked");
            if (is_check) {
                valid = true;
            } else {
                valid = false;
            }
            return valid;
        };
    var v_data = {
        form_selector: curr_form,
        elements: {
            "levels": {ele_selector: "[name='levels']", ele_type: "select", ele_regular: select, tips: {msg: "请选择您的职级", reg: "请选择职级"}},
            "nickname": {ele_selector: "[name='nickname']", ele_type: "text", ele_regular: nickname, tips: {msg: "中英文均可，不超过14个英文或7个汉字", reg: "请正确填写昵称"}},
            "mobile": {ele_selector: "[name='mobile']", ele_type: "text", ele_regular: mb_reg, tips: {msg: "请填写手机号码", reg: "请正确填写手机号码"}},
            "mob_code": {ele_selector: "[name='mob_code']", ele_type: "text", ele_regular: code_reg, tips: {msg: "请填写短信验证码", reg: "验证码格式不正确！"}},
            "email": {ele_selector: "[name='email']", ele_type: "text", ele_regular: email, tips: {msg: "请填写邮箱", reg: "请正确填写邮箱"}},
            "profession": {ele_selector: "[name='course_type_id']", ele_type: "select", ele_regular: select, tips: {msg: "请选择行业", reg: "请选择行业"}},
            "company_name": {ele_selector: "[name='company_name']", ele_type: "text", ele_regular: company_name, tips: {msg: "请填写公司名称，不超过30个字", reg: "请正确填写公司名称"}},
            "pwd": {ele_selector: "[name='password']", ele_type: "pwd", ele_regular: pwd_reg, tips: {msg: "请填写密码", reg: "请输入6-20位密码，区分大小写", to: "两次密码输入不一致，请重新输入！"}},
            "repwd": {ele_selector: "[name='repassword']", ele_type: "repwd", ele_regular: repwd_reg, tips: {msg: "请填写确认密码", reg: "请输入6-20位密码，区分大小写", to: "两次密码输入不一致，请重新输入！"}},
            "check_box": {ele_selector: "#regFormProtocolC", ele_type: "check_box", ele_regular: protocol, tips: {msg:"请选择协议", reg: "请选择协议"}}
        },
        sub_btn: "#regFormSubBtnC"
    };
    validate(v_data);

    /**
     * 获取手机验证码
     * @type {*|jQuery|HTMLElement}
     */
    var phone = $(v_data.elements["mobile"].ele_selector, v_data.form_selector),
        error_node,pic_code_node=$("#img_code_d");
    $("#regFormGetCodeC").on("click", function () {
        var self = $(this);
        error_node=phone.next("span");
        phone.val($.trim(phone.val()));

        if (!v_data.elements["mobile"].ele_regular.test(phone.val())) {//校验不通过，添加红色框框
            error_node.removeClass("span2").addClass("span4");
            error_node.html(v_data.elements["mobile"].tips["reg"]).fadeIn();
        } else {

            //验证手机是否唯一
            var p = {url: config.domain.edu + "/register/checkMobile?jsoncallback=?", data: {ajax: 1, mobile: phone.val()}};
            _Util.showAutoBg(true);
            edu.cm.service(p, function (d) {
                if (d.code != errorCode) {
                    error_node.removeClass("span2").addClass("span4");
                    error_node.html(d.msg).fadeIn();
                    _Util.hideAutoBg(true);
                    return false;
                } else {
                    //判断是否需要图片验证
                    p = {url: config.domain.api + "/sms/isVaildSmsByMobile?jsoncallback=?", data: {mobile: phone.val()}};
                    edu.cm.service(p, function (d) {
                                _Util.hideAutoBg(true);
                                if(!d){ //弹窗
                                    up_img_code(function(){
                                        var tmp="获取中",loading,send_btn= $(".text_a2",".verification_dialog");
                                        _Util.showAutoBg(".verification_dialog");
                                        $("#img_code_d",".verification_dialog").on({
                                            focus:function(){
                                                $(".text_p p",".verification_dialog").removeClass("p2").addClass("p1").html("请输入图片验证码");
                                            },blur:function(){
                                                if(!pic_code.test($.trim($("#img_code_d").val()))){
                                                    $(".text_p p",".verification_dialog").removeClass("p1").addClass("p2").html("请输入正确的验证码");
                                                }
                                            }}
                                        );
                                        $(".text_a4",".verification_dialog").click(function(){
                                            _Util.hideAutoBg(".verification_dialog");
                                            send_btn.removeAttr("disabled");
                                            send_btn.html("发送验证码");
                                            loading&&clearInterval(loading);
                                        });

                                        //绑定发送验证码按钮
                                        send_btn.click(function(){
                                            if(!send_btn.attr("disabled")){
                                                //判断是否为空
                                                if(!pic_code.test($.trim($("#img_code_d").val()))){
                                                    $(".text_p p",".verification_dialog").removeClass("p1").addClass("p2").html("请输入正确的验证码");
                                                }else{
                                                    send_btn.attr("disabled","disabled");
                                                    send_btn.html('发送中.');
                                                    loading = setInterval(function(){
                                                        var l_v =send_btn.html();
                                                        if(l_v.length < 6){
                                                            l_v = l_v+'.';
                                                        }else{
                                                            l_v = '发送中.';
                                                        }
                                                        send_btn.html(l_v);
                                                    },1000);

                                                    get_mb_code($(this),function(d,o){
                                                        var scope=$("#regFormGetCodeC",curr_form),ct=60;
                                                        clearInterval(loading);
                                                        send_btn.html("发送验证码");
                                                        send_btn.removeAttr("disabled");
                                                        if(d){
                                                            //关闭弹窗
                                                            _Util.hideAutoBg(".verification_dialog");
                                                            //倒计时效果
                                                            countDown_self(scope,ct,t);
                                                        }else{
                                                            //输出msg
                                                            $(".text_p p",".verification_dialog").removeClass("p1").addClass("p2").html(o.msg);
                                                        }
                                                    });
                                                }
                                            }


                                        });
                                    });

                                }else{//倒计时
                                    countDownC(self, function () {
                                        _Util.hideAutoBg(true);
                                        return false;
                                    });
                                }
                    });

                }
            });

        }

    });

    //倒计时
    function countDownC() {
        var ct = 60, scope = arguments[0] ? arguments[0] : "",
            call = (typeof arguments[1] == "function" ? arguments[1] : ""),
            code = pic_code_node.val(), img_key = $("#img_key").val(),error_node=$(v_data.elements["mob_code"].ele_selector).nextAll("span");
        var p = {url: config.domain.edu + "/register/get_mobile_code_n_n?jsoncallback=?", data: {ajax: 1, mobile: phone.val(), img_key: img_key, img_code: code}};
        if (scope.attr("disabled") != "disabled") {
            edu.cm.service(p, function (d) {
                if (d.code != errorCode) {
                    clearInterval(t);
                    scope.val("获取短信验证码");
                    scope.removeAttr("disabled");
                    //输出msg
                    error_node.html(d.msg).removeClass("span2").addClass("span4").fadeIn();
                    call && call();
                }
            });
        }

        countDown_self(scope,ct,t);

    }

    $(v_data.form_selector).on("keydown", function (e) {
        if (e.keyCode == 13) {
            $("#regFormSubBtnC").trigger("click");
        }
    });
    /**
     *
     * @param scope 按钮
     * @param ct   倒计时
     * @param t    setInterval 变量
     */
    function countDown_self(scope,ct,t){
        scope.attr("disabled", "disabled").val(ct + "秒后重新获取");
        t = setInterval(function () {
            ct--;
            if (ct == 0) {
                clearInterval(t);
                scope.val("获取短信验证码");
                scope.removeAttr("disabled");
            } else {
                scope.val(ct + "秒后重新获取");
            }
        }, 1000);
    }

    //获取验证码
    function get_mb_code(){
        var scope = arguments[0] ? arguments[0] : "",
            call = (typeof arguments[1] == "function" ? arguments[1] : ""),
            code = pic_code_node.val(), img_key = $("#img_key").val();
        var p = {url: config.domain.edu + "/register/get_mobile_code_n_n?jsoncallback=?", data: {ajax: 1, mobile: phone.val(), img_key: img_key, img_code: code}};
            edu.cm.service(p, function (d) {
                if (d.code != errorCode) {
                    call && call(false,d);
                }else{
                    call && call(true,d);
                }
            });
    }

})(window);
/*//验证码弹窗
_Util.showAutoBg(".verification_dialog");
$(".text_a4",".verification_dialog").on("click",function(){
    _Util.hideAutoBg(".verification_dialog");
});
//注册成功弹窗
_Util.showAutoBg(".ok_dialog");
$(".text_a3",".ok_dialog").on("click",function(){
    _Util.hideAutoBg(".ok_dialog");
});*/
function up_img_code(call){
    $.post('/register/ajaxImgCode',{},function(data){
        if(data.img_code){
            $('.verification_dialog img.text_img').attr('src',config.domain.edu+'/captcha/'+data.times+'.jpg');
            $("#img_key").val(data.m_key);
            call&&call();
        }
    },'json');
}