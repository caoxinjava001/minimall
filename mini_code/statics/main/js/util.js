/**
 * Created by tony on 14-12-9.
 */

_suffix="xinnuos.com";
config = {
    domain: {
        wf:"http://wf."+_suffix,
        statics: "http://wf."+_suffix+"/statics/",
        upload : "http://upload.kangm.cn/"
    },
    d:function(){return new Date();},
    //版本号
    v:function(){return this.d().getFullYear()+""+(this.d().getMonth()+1)+""+this.d().getDate()+""+"_03"}
}

;(function(){
    var _util = _util || {};
    /* 浏览器检测 \x24 */
    _util.browser =  _util.browser || function (w, d, n){

        /* userAgent */

        var u = n.userAgent.toLowerCase(), browser = {};

        browser.u = u;

        /* 渲染模式 (标准CSS1Compat)*/

        browser.render = d.compatMode;

        /* gecko */

        if(n.product === 'Gecko') browser.gecko = true;

        /* webkit */

        if(/ applewebkit\/(\d+\.\d+)/i.test(u)) browser.webkit = RegExp['\x241'];

        /* ie */

        if(!!w.ActiveXObject){

            browser.ie = /msie (\d+\.\d+)/i.test(u) ? RegExp['\x241'] : d.documentMode;

            /* 引擎 */

            if(/\s+trident\/?(\d+\.\d+)?/i.test(u)) browser.trident = RegExp['\x241'];

            /* 向后兼容模式 */

            browser.quirks = (d.compatMode == 'BackCompat');

            /* 标准模式 */

            browser.norm = d.documentMode;

            return browser;

        }

        /* firefox */

        if(browser.gecko && /firefox\/(\d+\.\d+)/i.test(u)){

            browser.firefox = RegExp['\x241'];

            return browser;

        }

        /* chrome */

        if(/chrome\/(\d+\.\d)/i.test(u)){

            browser.chrome = RegExp['\x241'];

            return browser;

        }

        /* safari(chrome 相同) */

        if(browser.gecko && /\s+safari\/?(\d+\.\d+)?/i.test(u)){

            browser.safari = RegExp['\x241'];

            return browser;

        }

        /* opera */

        if(!!w.opera && /opera(?:\/| )(\d+(?:\.\d+)?)/i.test(u)){

            browser.opera = RegExp['\x241'];

            /* 引擎 */

            if(/\s+presto\/?(\d+\.\d+)?/i.test(u)) browser.presto = RegExp['\x241'];

            return browser;

        }

        return browser

    }(window, document, navigator);
    /**
     * 弹窗
     * @param
     * params 字符串 为选择器或者boolean
     * close 字符串 传入close选择器，可传入多个。如.xxx,#cccc,.aaa
     * 如果传入值为true或false 则加载loading 否则加载传入选择器的弹窗
     */
    _util.showAutoBg=function(params){
        var loadNode='<div style="z-index: 10008; display: none;" id="loading"><img width="100" height="100" alt="" src="'+config.domain.statics+'main/images/img/loading.gif"></div>',
            loadDom,topv,leftv,z_index=10007,list_dialog=$(".edu_ui_dialog"),d_zindex=10006,params_bg="",options,
            selectedEffect={
                "blind":"blind","bounce":"bounce","clip":"clip","drop":"drop",
                "explode":"explode","fold":"fold" ,"highlight":"highlight","puff":"puff",
                "pulsate":"pulsate","scale":"scale","shake":"shake","size":"size","slide":"slide"
            };

        $("#edu_dialog").length==0?$("<div id='edu_dialog'></div>").appendTo(document.body):"";

        //如果是loading 那么位置设置到最高
        if(typeof params=='boolean'){
            if(list_dialog.length==0){
                z_index=10007;
            }else{
                z_index=parseInt(list_dialog.eq(list_dialog.length-1).css("z-index"))+1;
            }
            $("#full_bg").length==0?$("<div id='full_bg'></div>").appendTo(document.body):'';
            $("#full_bg").show().attr("show","true");
            $('#full_bg').css({
                "position": "absolute",
                "margin-left": "0px",
                "margin-top": "0px",
                "background-image": "url('"+config.domain.statics+"main/images/img/pngbg1.png')",
                "background-repeat":"repeat",
                "height":  $(document).height(),
                "filter": "alpha(opacity=30)",
                "opacity": "0.35",
                "overflow": "hidden",
                "width": $(document).width(),
                "z-index": z_index,
                "text-indent":"-10000px"
            });

            $("#loading").length==0?$(document.body).append(loadNode):loadDom=$("#loading");
            loadDom=$("#loading");
            if(_util.browser.ie=="6.0"){
                var h=(function(){
                    var wh=$(window).height();
                    var lh=$(window).scrollTop();
                    var selfh=loadDom.height();
                    return lh+(wh-selfh)/2;
                })();
                loadDom.css({position:"absolute",top:h+"px"});
            }

            loadDom.css({
                "z-index":(z_index+1)
            });

            loadDom.show();

        }else{
            params_bg=$('<div id="bg_'+(params.replace("#","_").replace(".","_"))+'"></div>');
            params_bg.appendTo(document.body);

            if(list_dialog.length==0){
                d_zindex=10007;
            }else{
                d_zindex=parseInt(list_dialog.eq(list_dialog.length-1).css("z-index"))+1;
            }

            params_bg.show();
            $(params_bg).css({
                "position": "absolute",
                "background-image": "url('"+config.domain.statics+"main/images/img/pngbg1.png')",
                "background-repeat":"repeat",
                "height": $(document).height() ,
                "filter": "alpha(opacity=30)",
                "opacity": "0.5",
                "overflow": "hidden",
                "width":  $(document).width(),
                "z-index": d_zindex,
                "left":0,
                "top":0,
                "text-indent":"-10000px"
            });

            if( $(params).parent()[0]!=document.body){
                $("#edu_dialog").length>0?$("#edu_dialog").after($(params)):"";
            }

            $(params).addClass("edu_ui_dialog");
            $(params).show();

            topv=_util.getMiddleTop(params);
            leftv=_util.getMiddleLeft(params);

            if(_util.browser.ie=="6.0"){
                var h=(function(){
                    var wh=$(window).height();
                    var lh=$(window).scrollTop();
                    var selfh=$(params).height();
                    return lh+(wh-selfh)/2;
                })();
                $(params).css({
                    position:"absolute",
                    "z-index" :(d_zindex+1),
                    "top"     : "50%",
                    "left"    : "50%",
                    "margin-top" :"-"+topv,
                    "margin-left":"-"+leftv
                });
            }else{
                $(params).css({
                    "z-index" :(d_zindex+1),
                    "top"     : "50%",
                    "left"    : "50%",
                    "margin-top" :"-"+topv,
                    "margin-left":"-"+leftv,
                    "position": "fixed"
                });
            }



            typeof arguments[1]&&$(arguments[1],params).on("click",function(){
                _util.hideAutoBg(params);
            });
            //效果
            /*if((typeof arguments[1])=="object"){
                o=arguments[1];
                $(params).effect(selectedEffect[o.effect],o.options,650);
            }
*/

        }
        window.resize=function(){
            topv=_util.getMiddleTop(params);
            leftv=_util.getMiddleLeft(params);
            $(loadDom||params).css({
                "top"     : "50%",
                "left"    : "50%",
                "margin-top" :"-"+topv,
                "margin-left":"-"+leftv,
                "position": "fixed"
            });
        };
    };
    _util.hideAutoBg=function(params){
        var dialogs=$(".edu_ui_dialog"),show_list= 1,curr="";
        if(typeof params=='boolean'){
            $("#full_bg").hide();
            $("#loading").hide();
        }else{
            curr=(params.replace("#","_").replace(".","_"));
            $("#bg_"+curr).remove();
            $(params).hide();
        }

        /*if(dialogs.length>1){
         $.each(dialogs,function(i,o){
         if($(o).css("display")!="none"){
         show_list++;
         }
         });
         //如果没有显示的dialog 则隐藏fullbg
         if(show_list<=1){
         $("#fullbg").hide();
         }
         }else{
         $("#fullbg").hide();
         }*/
    };


    _util.getMiddleTop=function(selector){
        var top,sTop;
        top=($(selector).height()/2)+"px";
        return top;
    }
    _util.getMiddleLeft=function(selector){
        var left;
        left=($(selector).width()/2)+"px";
        return left;
    }
    /**
     * 复制连接
     * @param selector .xxx或#xxx
     * @传入参数为要复制连接地址的input的selector
     */
    _util.copyToClipboard=function(a) {
        var c = $(a);
        try {
            if(c.length>0){
                c.select();
                if (c[0].createTextRange) {
                    document.execCommand("Copy");
                    alert("\u590d\u5236\u6210\u529f\u3002");
                    return;
                }
            }else{
                alert("没有要复制的内容");
            }

        } catch (d) {
        }
        alert("\u60a8\u4f7f\u7528\u7684\u6d4f\u89c8\u5668\u4e0d\u652f\u6301\u6b64\u590d\u5236\u529f\u80fd\uff0c\u8bf7\u4f7f\u7528Ctrl+C\u6216\u9f20\u6807\u53f3\u952e\u3002")
    };
    _util.ajax=function(params,callback,complete,error,time){
        $.ajax({
            type: params.type,
            url:  params.url,
            data: params.data||"",
            dataType:params.dataType||"json",
            async:typeof(params.isAsync)=="undefined"?true:params.isAsync,
            beforeSend: function(XMLHttpRequest){
            },
            success: function(data, textStatus){
                callback(data);
            },
            complete: function(XMLHttpRequest, textStatus){
                complete&&complete(XMLHttpRequest, textStatus);
            },
            error: function(XMLHttpRequest, errormsg, errorlog){
                error&&error(XMLHttpRequest, errormsg, errorlog);
                //请求出错处理
                (typeof console=="object")?console.log("--------错误类型："+errormsg):"";
                (typeof console=="object")?console.log("--------错误日志："+errorlog):"";
            },
            timeout:time||0
        });
    };
    _util.tabs=function(tid,cid,params){
        if(tid&&cid){
            var t=$("#"+tid).find("ul li.tabs_t").length>0?$("#"+tid).find("ul li.tabs_t"):$("#"+tid).find(".tabs_t"),c=$("#"+cid).children();
            if(t.length>0){
                $.each(t,function(i){
                    $(this).attr("id",tid+"_t_"+i);
                    if(params){
                        if(params.c_event=="hover"){
                            $(this).hover(function(){
                                $(t).removeClass(params.t_currentClass);
                                $.each(t,function(i){
                                    if(!$(this).hasClass(params.t_otherClass)){
                                        $(this).addClass(params.t_otherClass);
                                    }
                                });

                                $(this).removeClass(params.t_otherClass).addClass(params.t_currentClass);
                                $(c).hide();
                                $("#"+cid+"_c_"+i).show();
                            },function(){
                                $(this).removeClass(params.t_currentClass).addClass(params.t_otherClass);
                                $("#"+cid+"_c_"+i).hide();
                            });
                        }else{
                            $(this).bind(params.c_event||"mouseover",function(){
                                $(t).removeClass(params.t_currentClass);
                                $.each(t,function(i){
                                    if(!$(this).hasClass(params.t_otherClass)){
                                        $(this).addClass(params.t_otherClass);
                                    }
                                });

                                $(this).removeClass(params.t_otherClass).addClass(params.t_currentClass);
                                $(c).hide();
                                $("#"+cid+"_c_"+i).show();
                                //切换完成后回调
                                params.callback&&params.callback(i);
                            });
                        }
                    }else{
                        return;
                    }
                });

            }
            if(c.length>0){
                $.each(c,function(i){
                    $(this).attr("id",cid+"_c_"+i);
                    if(params.c_event=="hover"){
                        $("#"+cid+"_c_"+i).hover(function(){
                            $("#"+tid+"_t_"+i).removeClass(params.t_otherClass).addClass(params.t_currentClass);
                            $(this).show();
                        },function(){
                            $("#"+tid+"_t_"+i).removeClass(params.t_currentClass).addClass(params.t_otherClass);
                            $(this).hide();
                        });
                    }
                });
            }
            //默认选中
            if(params&&params.position>=0){
                $(t).removeClass(params.t_currentClass);
                $.each(t,function(i){
                    if(!$(this).hasClass(params.t_otherClass)){
                        $(this).addClass(params.t_otherClass);
                    }
                    if(params.position==i){
                        $(this).addClass(params.t_currentClass);
                        $(c).hide();
                        $("#"+cid+"_c_"+i).show();
                    }
                });

            }
        }
    };

    _util._init_upload_js=function(callback){
        var self=this;
        self._load_js({"url":config.domain.statics+"main/swfupload/","fileName":"swfupload.js"},function(){
            self._load_js({"url":config.domain.statics+"main/swfupload/","fileName":"swfupload.queue.js"},function(){
                self._load_js({"url":config.domain.statics+"main/swfupload/","fileName":"fileprogress.js"},function(){
                    self._load_js({"url":config.domain.statics+"main/swfupload/","fileName":"handlers.js"},function(){
                        self.load_Js=true;
                        callback();
                    });
                });
            });
        });
    }
    /**
     * 动态加载文件
     * @param file callback
     * file.url 路径
     * file.fileName 文件名
     * file.labelName 类型支持script&link 默认是script
     * file.type 默认是text/javascript
     * file.attrType 默认是src
     * file.container 存放script不放在head里
     */
    _util._load_js=function(file,callback){
        try {
            var  self=this,_doc,js;
            if(file.fileName?self._isInclude(file.fileName):false){ //判断文件是否存在，如果存在直接调用回调
                callback&&callback();
            }else{
                //添加容器，存放script
                if(file.container){
                    _doc = file.container;
                }else{
                    _doc = window.document.getElementsByTagName('head')[0];
                }

                js = window.document.createElement(file.labelName||'script');

                js.setAttribute('type', file.type||'text/javascript');
                js.setAttribute('charset', 'utf-8');
                js.setAttribute(file.attrType||'src', file.url+file.fileName+((typeof config=="object")?("?v="+config.v()):""));

                if(file.fileName==""){
                    js.setAttribute(file.attrType||'src', file.url+file.fileName);
                }else{
                    js.setAttribute(file.attrType||'src', file.url+file.fileName+((typeof config=="object")?("?v="+config.v()):""));
                }

                _doc.appendChild(js);

                if (window.document.all) { //如果是IE
                    js.onreadystatechange = function () {
                        if (js.readyState == 'loaded' || js.readyState == 'complete') {
                            callback &&callback();
                        }
                    }
                }
                else {
                    js.onload = function () {
                        callback && callback();
                    }
                }

            }
        } catch (e) {
            // TODO: handle exception
            (typeof console=="object")?console.error(e):"";
        }


    }
    //判断是否引入该文件
    _util._isInclude=function(name){
        var js= /js$/i.test(name);
        var es=window.document.getElementsByTagName(js?'script':'link');
        for(var i=0;i<es.length;i++)
            if(es[i][js?'src':'href'].indexOf(name)!=-1)return true;
        return false;
    }






//计算字符串长度
    String.prototype.strLen = function() {
        var len = 0;
        for (var i = 0; i < this.length; i++) {
            if (this.charCodeAt(i) > 255 || this.charCodeAt(i) < 0) len += 2; else len ++;
        }
        return len;
    };


//将字符串拆成字符，并存到数组中
    String.prototype.strToChars = function(){
        var chars = new Array();
        for (var i = 0; i < this.length; i++){
            chars[i] = [this.substr(i, 1), this.isCHS(i)];
        }
        String.prototype.charsArray = chars;
        return chars;
    };

//判断某个字符是否是汉字
    String.prototype.isCHS = function(i){
        if (this.charCodeAt(i) > 255 || this.charCodeAt(i) < 0)
            return true;
        else
            return false;
    };

//截取字符串（从start字节到end字节）
    String.prototype.subCHString = function(start, end){
        var len = 0;
        var str = "";
        this.strToChars();
        for (var i = 0; i < this.length; i++) {
            if(this.charsArray[i][1])
                len += 2;
            else
                len++;
            if (end < len)
                return str;
            else if (start < len)
                str += this.charsArray[i][0];
        }
        return str;
    };

//截取字符串（从start字节截取length个字节）

    String.prototype.subCHStr = function(start, length){
        return this.subCHString(start, start + length);
    };

    String.prototype.trims = function(){
        return this.replace(/\s+/g,"");
    };

    //数组位置交换
    Array.prototype.exchange=function(a,b){
        var l,r;
        if(isNaN(a)||a>this.length||isNaN(b)||b>this.length){return false;};
        l=this[a],r=this[b];
        this.splice(a,1,r);
        this.splice(b,1,l);
    };

    window._util=_util;
})();

!(function(){
   var cookie={};
	//set cookie
    cookie.setCookie=function(name,value,time,domain){
        var strsec,exp;
        strsec = cookie.getsec(time);
        exp = new Date();
        exp.setTime(exp.getTime() + strsec*1);
        document.cookie = name + "="+ encodeURI  (value) + ";expires=" + exp.toGMTString()+";"+(domain?("path=/;domain="+domain):"");
    };


    cookie.getsec=function(str){
        var str1,str2;
        str1=str.substring(1,str.length)*1;
        str2=str.substring(0,1);
        if (str2=="s"){
            return str1*1000;
        }else if (str2=="h"){
            return str1*60*60*1000;
        }else if (str2=="d"){
            return str1*24*60*60*1000;
        }else if (str2=="m"){
            return str1*24*30*60*60*1000;
        }else if (str2=="y"){
            return str1*24*30*12*60*60*1000;
        }
    };

    cookie.getCookie=function(name){
        var arr,reg=new RegExp("(^| )"+name+"=([^;]*)(;|$)");
        if(arr=document.cookie.match(reg)){
            return decodeURI(arr[2]);
        }else{
            return null;
        }
    };
    cookie.delCookie=function(name,domain){
        var exp,cval;
        exp = new Date();
        exp.setTime(exp.getTime() - 1);
        cval=cookie.getCookie(name);
        if(cval!=null) document.cookie= name + "="+cval+";expires="+exp.toGMTString()+";"+(domain?("path=/;domain="+domain):"");
    };

    window.cookie=cookie;
    /*demo*/
    /*if(cookie.getCookie("king")==null){
     cookie.setCookie("king","window_AD","d1");
     }else{
     //do something
     }*/
})();


/**
 * 公共显示信息方法
 * @param msg
 * @param time
 * @param title
 * @param height 高
 * @param width  宽
 * @param draggable 是否拖拽
 * @param resizable 是否缩放
 * @param effect 效果
 * @param btns 按钮名字及回调 如：｛btns:[{text:"确定",callback:function(){}},...]｝
 *  params string|object
 *  {msg:"xxx",title:"xxxx"}或 show_msg("xxx",1000,'clip',callback);
 */
;
(function (self) {
    var _no_timer, _ok_timer;//倒计时
    function _show_msg(params) {
        var str = '', msg = "", title = "温馨提示", btns = [], height , width , time = 1500, now_t = new Date().getTime(),is_close_call,
            _error_msg = "请输入message或参数！",effect="",options,selectedEffect={
            "blind":"blind","bounce":"bounce","clip":"clip","drop":"drop",
            "explode":"explode","fold":"fold" ,"highlight":"highlight","puff":"puff",
            "pulsate":"pulsate","scale":"scale","shake":"shake","size":"size","slide":"slide"
            };

        //判断传入参数是否为对象 获取效果参数
        if(typeof(arguments[0]) == "object"){
            effect = arguments[0];
        }else{
            effect = arguments[2];
        }
        //如果为scale 或 size 则单独设置options
        if ( effect === "scale" ) {
            options = { percent: 100 };
        } else if ( effect === "size" ) {
            options = { to: { width: 280, height: 185 } };
        }

        //判断如果传入参数大于0则
        if (arguments.length > 0) {
            if (typeof(arguments[0]) == "object") {
                if (arguments[0].msg) {
                    msg = arguments[0].msg;
                    if (arguments[0].title) {
                        title = arguments[0].title;
                    }
                    if (arguments[0].btns) {
                         btns = arguments[0].btns;
                    }
                    if(arguments[0].width){
                        width=arguments[0].width;
                    }
                    if(arguments[0].height){
                        height=arguments[0].height;
                    }

                    str = getStr(msg, title, btns, now_t,width,height);

                    $(document.body).append(str);
                    if (btns&&btns.length>0) {
                       $(".msg_btn_ok",".cm_msg_dialog_"+now_t).on("click",function(){
                            btns[0]&&btns[0].callback(now_t);
                       });
                        $(".msg_btn_cancel",".cm_msg_dialog_"+now_t).on("click",function(){
                            btns[1]&&btns[1].callback(now_t);
                        });

                    }


                    _util.showAutoBg(".cm_msg_dialog_" + now_t);

                    if (arguments[0].draggable) {
                        $(".cm_msg_dialog_" + now_t).find(".edu_msg_title").css("cursor", "move");
                        $(".cm_msg_dialog_" + now_t).draggable({ handle: ".edu_msg_title", cursor: "move" });
                    }
                    if (arguments[0].resizable) {
                        $(".cm_msg_dialog_" + now_t).resizable({ animate: true });
                    }
                    is_close_call=arguments[0].callback?arguments[0].callback:"";

                    //绑定关闭
                    binding_alls(now_t,is_close_call);

                    //定时关闭
                    if ((typeof(arguments[0].time) == "number")) {
                        _ok_timer = setTimeout(function () {
                            msg_close(now_t,is_close_call);
                        }, arguments[0].time);
                    }
                } else {
                    typeof(console) ? console.error(_error_msg) : "";
                }

            } else {
                //TODO
                msg = arguments[0];
                str = getStr(msg, now_t);

                $(document.body).append(str);

                is_close_call=arguments[3]?arguments[3]:"";

                binding_alls(now_t,is_close_call);


                $(".cm_msg_dialog_" + now_t).find(".edu_msg_title").css("cursor", "");
                _util.showAutoBg(".cm_msg_dialog_" + now_t);

                //定时关闭
                if ((typeof(arguments[1]) == "number")) {
                    _no_timer = setTimeout(function () {
                        msg_close(now_t,is_close_call);
                    }, arguments[1]);
                }
            }


        } else {
            typeof(console) ? console.error(_error_msg) : "";
        }

    };

    function binding_alls(now_t,callback){
        //绑定关闭弹窗
        $(".close",".cm_msg_dialog_" + now_t).click(function () {
            msg_close(now_t);
            self.clearTimeout(_no_timer);
            callback&&callback();
        });
    }
    //msg,title,btns,now_t
    function getStr() {
        var str = '', self_str = '', self_msg = "", self_title = "温馨提示", self_btns = [], now_t,width,height;
        if (arguments.length > 2) {
            self_title = arguments[1];
            self_msg = arguments[0];
            self_btns = arguments[2];
            now_t = arguments[3];
            width = arguments[4]?arguments[4]:"";
            height = arguments[5]?arguments[5]:"";
        } else {
            self_msg = arguments[0];
            now_t = arguments[1];
        }

        str += ' <div class="edu_msg cm_msg_dialog_' + now_t + '" style="display: none;width:'+width+';height:'+height+';">';
        str += '    <div class="edu_msg_title">';
        str += '        <span class="t_con">' + self_title + '</span>';
        str += '         <a class="close">关闭</a>';
        str += '    </div>';
        str += '     <div class="edu_msg_content">' + self_msg + ' </div>';
        str += '    <div class="edu_msg_footer">';
        if(self_btns.length!=0){
            str += '       <a class="msg_btn_ok" href="javascript:;">'+(self_btns[0].text||"确定")+'</a>';
            str += '      <a class="msg_btn_cancel" href="javascript:;">'+(self_btns[1].text||"关闭")+'</a>';
        }else{
            //TODO 倒计时关闭
        }

        str += '   </div>';
        str += ' </div>';
        return str;
    }
    //关闭message
    function msg_close(now_t,callback){

        _util.hideAutoBg(".cm_msg_dialog_" + now_t);
        $(".cm_msg_dialog_" + now_t).remove();
        callback&&callback();
    }
    self._show_msg = _show_msg;
    self._hide_msg = msg_close;
})(window);


/**
 * 上传
 * @ param
 * upload 上传控件容器
 * params 上传的参数Object
 * params.url　涵盖了各种参数包括宽高
 * callback 上传成功回调
 * upload_progress 上传进度回调
 * upload_error 上传失败回调
 */
function up_load(uploadDiv,params,callback,upload_progress,upload_error){
    var domNode='',_t,_l,self=this,swfu=uploadDiv+"_"+new Date().getTime(),swfu_arr=[],vid_url="",str="",flashPlaceHolder=uploadDiv+swfu;


    var userId =  params.userId ,
        button_text=params.button_text,
        button_width=params.button_width,
        button_height=params.button_height,
        createdBy =  params.createdBy,
        source =  params.source,
        type =  params.type,
        fileType= params.fileType,
        file_size=params.file_size,
        fileSource= params.fileSource,
        button_image_url=params.button_image_url,
        button_text_style=params.button_text_style,
        button_text_left_padding=params.button_text_left_padding,
        button_text_top_padding=params.button_text_top_padding,
        cancel_btn_id=params.cancel_btn_id,
        default_cancel_id="id_"+swfu;

    if(type=='2'){
        vid_url="http://upcc.159jh.com:88/upcc.url?cmd=upload_video&uid="+userId+"&filesource="+fileSource;
    };

    if(_util.browser.ie){
        if(_util.browser.norm==8||_util.browser.norm==7){
            str+='<div style="position:absolute;" class="posi_'+swfu+'">';
        }
    }

    str+='<form class="form-horizontal" action="'+config.domain.upload+'upload/file/upload" method="post" enctype="multipart/form-data">';
    str+='<div style="position:relative"><span id="'+flashPlaceHolder+'"></span>';
    //如果没有传入取消按钮id，就默认
    if(!cancel_btn_id){
        str+='<input id="id_'+swfu+'" type="button" value="取消上传"  disabled="disabled" style="display:none;position:absolute; margin-left: 2px; font-size: 12px; height: 26px;line-height:26px;background: url('+config.domain.statics+'main/swfupload/up_btn.png) no-repeat left -1px;border: medium none;width: 88px; color:#000000;font-family:宋体;" />';
    }else{
        $(cancel_btn_id).attr("id",default_cancel_id);
    }

    str+='</div></form>';

    if(_util.browser.ie){
        if(_util.browser.norm==8||_util.browser.norm==7){
            str+'<div>';
        }
    }

    if(_util.browser.ie){
        if(_util.browser.norm==8||_util.browser.norm==7){
            $(document.body).append(str);
            $("#"+uploadDiv).css({width:"233px",height:"30px",display:"block"});
            //设置位置显示
            _t=getTop( $("#"+uploadDiv)[0]);
            _l=getLeft($("#"+uploadDiv)[0]);
            $(".posi_"+swfu).css({left:_l,top:_t,zIndex:"3"});
            $(window).resize(function(){
                _t=getTop( $("#"+uploadDiv)[0]);
                _l=getLeft($("#"+uploadDiv)[0]);
                $(".posi_"+swfu).css({left:_l,top:_t,zIndex:"3"});
            });
            $(document).click(function(){
                _t=getTop( $("#"+uploadDiv)[0]);
                _l=getLeft($("#"+uploadDiv)[0]);
                $(".posi_"+swfu).css({left:_l,top:_t,zIndex:"3"});
            });
        }else{
            $("#"+uploadDiv).append(str);
        }
    }else{
        $("#"+uploadDiv).append(str);
    }

    //取消按钮绑定
    $("#id_"+swfu).click(function(){
        swfu_arr[swfu].cancelQueue();
    });

    //参数设置
    var settings = {
        flash_url : config.domain.statics+"main/swfupload/swfupload.swf",
        vid_url:vid_url,
        upload_url: config.domain.upload+ "upload/file/upload",
        post_params: {"userId" : userId,"type":type,"createdBy":createdBy,"source":source,"fileSource":fileSource},
        file_size_limit : file_size||"3 GB",
        file_post_name: "file",
        file_types : fileType,
        file_types_description : "All Files",
        file_upload_limit : 100,
        file_queue_limit : 0,
        custom_settings : {
            //progressTarget : "fsUploadProgress",
            cancelButtonId : default_cancel_id
        },
        //debug:true,
        // Button settings
        button_image_url: button_image_url||config.domain.statics+"main/swfupload/up_btn.png",
        button_width: button_width||"88",
        button_height: button_height||"29",
        button_cursor:"-2",//-1是箭头 ,-2是小手
        button_placeholder_id: flashPlaceHolder,
        button_text: '<span class="uploadBtn">'+(button_text||"开始上传")+'</span>',
        button_text_style: button_text_style||".uploadBtn { font-size: 12; text-align:center; }",
        button_text_left_padding:button_text_left_padding||2,
        button_text_top_padding:button_text_top_padding||3,
        button_window_mode:"transparent",
        // The event handler functions are defined in handlers.js
        file_queued_handler : fileQueued,
        file_queue_error_handler : fileQueueError,
        file_dialog_complete_handler : fileDialogComplete,
        //upload_start_handler :uploadStart,
        //upload_progress_handler : uploadProgress,
        //upload_error_handler : uploadError,
        //upload_success_handler : uploadSuccess,
        upload_complete_handler : uploadComplete,
        queue_complete_handler : queueComplete	// Queue plugin event
    };

    swfu_arr[swfu]= new SWFUpload(settings);


    if(swfu_arr[swfu]){
        swfu_arr[swfu].settings.upload_start_handler=function(file){

        };
        swfu_arr[swfu].settings.upload_success_handler=function(file, serverData){
            if(serverData=="fail"){
                callback(file,serverData);
            }else{
                var d=$.parseJSON(serverData);
                callback(file,d);
            }
        }
        swfu_arr[swfu].settings.upload_progress_handler=function(file, bytesLoaded, bytesTotal){
            var percent = Math.ceil((bytesLoaded / bytesTotal) * 100);
            upload_progress&&upload_progress(file,percent);
            if(file.size>1*1024*1024){
                if(99>percent&&percent>1){
                    //添加业务逻辑首先判断是否传入了取消按钮
                    if(cancel_btn_id){
                        //TODO
                        document.getElementById("id_"+swfu).style.display="";
                    }else{
                        document.getElementById("id_"+swfu).style.display="block";
                        document.getElementById("id_"+swfu).style.top="1px";
                        if(_util.browser.ie){
                            document.getElementById("id_"+swfu).style.marginLeft=(parseInt((button_width||"90"))+2)+"px";
                            if(_util.browser.norm==7){
                                document.getElementById("id_"+swfu).style.marginLeft="2px";
                            }
                        }else{
                            document.getElementById("id_"+swfu).style.marginLeft=(parseInt((button_width||"90"))+2)+"px";
                        }
                    }
                }else{
                    document.getElementById("id_"+swfu).style.display="none";
                }
            }
        }
        swfu_arr[swfu].settings.upload_error_handler=function(file, errorCode, message){
            if(errorCode=="-290"||errorCode=="-280"){
                if(errorCode=="-290"){
                    _show_msg("取消上传成功！",2000);
                    document.getElementById("id_"+swfu).style.display="none";
                    upload_error(true);//取消上传成功添加回调
                }
            }else{
                upload_error(file, errorCode, message);
            }

        }
    };
}

//getTopPosition
function getTop(e){
    var offset=e.offsetTop;
    if(e.offsetParent!=null) offset+=getTop(e.offsetParent);
    return offset;
}
//getleftPosition
function getLeft(e){
    var offset=e.offsetLeft;
    if(e.offsetParent!=null) offset+=getLeft(e.offsetParent);
    return offset;
}
