<link href="<?php echo STATICS_PATH;?>/css/ui/jquery-ui-custom.min.css" type="text/css" rel="stylesheet"/>
<link href="<?php echo STATICS_PATH;?>/css/b_reg.css" type="text/css" rel="stylesheet"/>
<script type="text/javascript" src="<?php echo STATICS_PATH;?>/js/ui/jquery-ui.custom.min.js"></script>

<div class="xu" id="xu">
    <div class="cont_left">
        <div class="cont_demand">
            <p class="demand_p1">创建管理员</p>
        </div>
        <form id="manage_add">
            <input type="hidden" name="id" value="<?php echo $data_info['id'];?>"/>
            <div class="demand_text">
                <div class="text">
                    <span class="floatL span1"><i>*</i>用户管理员:</span>
                    <select <?php if($data_info['id'] == $this->mid){ echo 'disabled="disabled"';}?> class="sect_1 floatL" name="role_id">
                        <option value="0">请选择用户角色</option>
                        <?php foreach($roler as $v){?>
                        <option <?php if($data_info['role_id'] && $v['id'] == $data_info['role_id']) echo 'selected="selected"';?> value="<?php echo $v[id];?>"><?php echo $v['name'];?></option>
                        <?php }?>
                    </select>
                    <span class="span2 floatL">请选择用户角色</span>
                </div>
                <div class="text">
                    <span class="floatL span1"><i>*</i>姓名:</span>
                    <input type="text" class="sect_1 floatL" name="name" placeholder="请填写真实姓名" value="<?php echo !empty($data_info['name'])?$data_info['name']:'';?>">
                    <span class="span2 floatL">请填写用户姓名</span>
                </div>
                <div class="text">
                    <span class="floatL span1"><i>*</i>手机号:</span>
                    <input type="text" class="sect_1 floatL" name="mobile" placeholder="请填写用户手机号" value="<?php echo !empty($data_info['mobile'])?$data_info['mobile']:'';?>">
                    <span class="span2 floatL">请正确填写用户手机号</span>
                </div>
                <div class="text">
                    <span class="floatL span1"><i></i>业务代码:</span>
                    <input type="text" class="sect_1 floatL" name="service_code" placeholder="请填写业务代码" value="<?php echo !empty($data_info['service_code'])?$data_info['service_code']:'';?>">
                    <span class="span2 floatL">请正确填写业务代码</span>
                </div>
                <div class="text">
                    <span class="floatL span1"><i>*</i>密码:</span>
                    <input type="password" class="sect_1 floatL" name="password" placeholder="请输入6位密码">
                    <span class="span2 floatL">请填写用户6位密码</span>
                </div>
                <div class="text">
                    <span class="floatL span1"><i>*</i>确认密码:</span>
                    <input type="password" class="sect_1 floatL" name="repassword" placeholder="请确认密码">
                    <span class="span2 floatL">请确认密码一致</span>
                </div>
                <div class="text">
                    <span class="floatL span1"></span>
                    <input type="button" class="btn btn-blue" id="createManager" value="确 定">
                    <input type="reset" class="btn btn-blue" id="" value="重置">
                </div>
            </div>
        </form>
    </div>
</div>

<!--<script>
    /**
     * Created By YJ 2015-05-25
     */
    var b=true,colum =$(".sect_1");

    /**
     * ajax 创建用户
     */
    $('#createManager').bind('click',function(){
        if(b) {
            var obj = {}, _msg={};
            var up_data = $('#form1').serialize();
            obj.data = up_data;
            obj.type = 'post';
            obj.url = config.domain.wf+'/manage/z';
           _util.ajax(obj, function (d) {
               _msg.msg = d.msg;
               if(d.status==1) {
                   _msg.callback = function()
                   {
                       colum.attr('value','');
                       location.href = '/manage/allManager';
                       //跳转到绑定权限页面
                       //location.href = '/information_auth/binding?mid='+d.data;
                   }
               }
                _show_msg(_msg,1000);
            });
        }else{
            _show_msg('表单填写有误！');
        }
    })

</script>-->
<script type="text/javascript" src="<?php echo STATICS_PATH;?>/js/manage_add.js"></script>
