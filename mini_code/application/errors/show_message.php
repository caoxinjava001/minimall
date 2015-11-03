<?php $this->_ci = & get_instance();   
$this->_ci->load->view('admin/header'); ?>  
<link href="<?php echo STATICS_PATH;?>css/cw404.css" type="text/css" rel="stylesheet" />
<!--躯干部分-->
<div class="w_public Min_c">
	<div class="tips_bg">
        <div class="tip">
        	<div class="tip_img"></div>
            <div class="tip_word"><?php echo $message; ?></div>
            <!--<div class="tip_word"><?php echo '该课程还没有视频呢，教务部门正在玩命地录课，请耐心等待。如有疑问，请尽快联系客服！400-088-3376'; ?></div>-->
                <ul style="text-align:center;padding-top:15px;line-height:20px;">
					<?php 
					if(empty($link)){
					?>
						<li class="notice">
							<a href="javascript:history.back();" >[点这里返回上一页]</a>
						</li>
					<?php } ?>
                    	<li class="notice">
							<?php if(!empty($link)){?>
							<?php echo $time; ?>秒后自动跳转，<?php echo anchor($link, '不想等待请点击'); ?>
						 <script type="text/javascript">
							setTimeout(function() {
								window.location = "<?php echo $link; ?>";
							}, <?php echo ($time * 1000); ?>);
						</script>
                    <?php }?>
					   </li>
                </ul>
            <!--<div class="tip_word">该课程还没有视频呢，教务部门正在玩命地录课，请耐心等待。如有疑问，请尽快联系客服！400-088-3376</div>-->
        </div>
    </div>
    <div class="tips_bot">
    	
    </div>
	<div class="w posit">
    		  <?php $this->_ci->load->view('admin/footer');?>  
    </div><!--w-->
</div><!--Min_c-->

<?php $this->_ci->load->view('admin/footer');?>  
