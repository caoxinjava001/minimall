
/**
 * 智慧云权限管理
 */
information_auth={
		callback_auth_del_code:"45101",//删除权限返回成功code
		callback_auth_add_code:"45108",//绑定权限返回成功code
		callback_auth_stop_code:"45111",//开启或关闭权限返回成功code
		_auth_index:function(){
			var self = this;
			$("input[name='username']").focusin(function(){
				if($(this).val() == '请输入手机号或邮箱'){
					$(this).val('');
				}
			})
			$("input[name='username']").focusout(function(){
				if($(this).val() == ''){
					$(this).val('请输入手机号或邮箱');
				}
			});
			$(".search_auth").click(function(){
				if($("input[name='username']").val() == '请输入手机号或邮箱'){
					$.jhh.cm.show_dialog({msg:'请输入手机号或邮箱',width:200,height:80});
					 return false;
				}
				$("#form_auth").submit();
			});
			$(".del_auth").click(function(){//删除绑定权限的用户记录
				 var aid = [];
				 $(".auth_list").each(function(){
					 if($(this).prop("checked")){
						 aid.push($(this).val());
					 }
				 })
				 var new_aid = aid.join(',');
				 if(new_aid == ''){
					 $.jhh.cm.show_dialog({msg:"请选择要删除的教务",width:200,height:80});
					 return false;
				 }
				 params={type:"post",url:"/information_auth/ajaxDel",data:{aid:new_aid}};
				 _util.ajax(params,function(d){//ajax返回结果
					 if(d.code == self.callback_auth_del_code){
						 window.location.reload(true);
					 }else{
						 $.jhh.cm.show_dialog({msg:d.msg,width:200,height:80});
						 return false;
					 }
				 }); 
			});
			$(".stop_auth").click(function(){
				 var aid = $(this).attr('rel');//获取已选中的权限菜单
				 _Util.showAutoBg(true);
				 params={type:"post",url:"/information_auth/ajaxStop",data:{aid:aid}};
				 _util.ajax(params,function(d){//ajax返回结果
					 _Util.hideAutoBg(true);
					 if(d.code == self.callback_auth_stop_code){
						 window.location.reload(true);
					 }else{
						 $.jhh.cm.show_dialog({msg:d.msg,width:200,height:80});
						 return false;
					 }
				 }); 
			});
			$("#check_all").click(function(){
				$.jhh.cm.checkAll('check_all','auth_list[]');
			});
		},
		/**
		 * 绑定权限
		 */
		_auth_binding:function(){
			var self = this;
			$(".dx_list input").click(function(e){
				if($(e.target).prop("checked")){
					$(this).siblings("div.pad_l").find("input").attr('checked','checked');
				}else{
					$(this).siblings("div.pad_l").find("input").removeAttr('checked');
				}

				var val = $(this).attr('id');
				var val_array = val.split('_');
				if(val_array.length >2){
					if($(e.target).prop("checked")){
						var param  = val_array[0]+'_'+val_array[1];
						$('#'+param).attr('checked','checked');
						for(var i=1,len=val_array.length;i<len;i++){
							$('#'+param+'_'+val_array[i]).attr('checked','checked');
						}
					}
				}

				var ischeck=false,current_div=$(this).parents('.glqx_con'),parent_div=$(this).parents('.pad_l');
				$.each(current_div.find("input"),function(i,item){
					if($(this).prop("checked")){
						ischeck=true;
					}
				});
				if(ischeck){
					//不许换结构
					current_div.prev().attr("checked","checked");
				}else{
					current_div.prev().removeAttr("checked");
				}
			});
			
			$(".auth_sumbit").click(function(){
				//_Util.showAutoBg(true);
				var menu_list = $("#form_auth").serialize();//获取已选中的权限菜单
				 params={type:"post",url:"/information_auth/binding",data:menu_list};
				 _util.ajax(params,function(d){//ajax返回结果
					 if(d.code == self.callback_auth_add_code){
						 //window.location.href = config.domain.cloud+d.data;
						 window.location.href = d.data;
					 }else{
						 _Util.hideAutoBg(true);
						 $.jhh.cm.show_dialog({msg:d.msg,width:200,height:80});
						 return false;
					 }
				 }); 
			});
		},
		_auth_menu:function(){
			//权限选项卡切换
			//$.jhh.tabs("lg_tabs_t","lg_tabs_c",{t_otherClass:"other",t_currentClass:"this",c_event:"click"}); 	

			//权限展开关闭
			$("span.tab_menu").live('click',function(){
				if($(this).hasClass('current')){
					$(this).removeClass('current');
				}else{
					$(this).addClass('current');
				}
				$(this).next('div.pad_l').toggle();
			})
		}
}
