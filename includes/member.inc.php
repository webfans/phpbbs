<?php
//防止恶意调用
if (!defined('IN_TG')){
    exit('非法调用');
}
?>
<h2>会员中心</h2>
<dl>
    <dt>账户管理</dt>
    <dd><a href="member.php">人个信息</a> </dd>
    <dd><a href="member_modify.php">修改资料</a> </dd>
</dl>
<dl>
    <dt>其它管理</dt>
    <dd><a href="member_message.php">短信查询</a> </dd>
    <dd><a href="member_friend.php">好友设置</a> </dd>
    <dd><a href="member_flower.php">查询花朵</a> </dd>
    <dd><a href="###">人个相册</a> </dd>
</dl>
