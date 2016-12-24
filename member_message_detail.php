<?php
session_start();
//error输出
error_reporting(E_ALL);
ini_set('display_errors', '1');
//指定一个常量 用来授权能不能调用文件
define('IN_TG',true);
//定义一个常量 用来指定本页的内容
define('SCRIPT','member_message_detail');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php';
//判断是否登陆
if (!isset($_COOKIE['username'])){
    alert_back('please login,then try again');
}
if (isset($_GET['id'])){
    $rows=fetch_array("select m_fromuser,m_content,m_date from bbs_message where m_id='{$_GET['id']}'");
    if (!!$rows){
        $_html=array();
        $_html['fromuser']=$rows['m_fromuser'];
        $_html['content']=$rows['m_content'];
        $_html['date']=$rows['m_date'];
        $_html=html_spec($_html);
    }else{
        alert_back('此短信不存在');
    }
}else{
    alert_back('非法登录');
}



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
    <!--点击验证码局部刷新js-->
    <script type="text/javascript" src="js/vcode.js"></script>
    <!--客户端验证表单，减少服务器端验证负担-->
    <script type="text/javascript" src="js/member_modify.js"></script>
    <?php require ROOT_PATH.'includes/title.inc.php'?>
    <title>短信详情</title>
</head>
<body>
<?php
//使用硬路径引入速度比相对路径快很多 头部文件
require ROOT_PATH.'includes/header.inc.php';
?>
<div id="member">
    <div id="member_sidebar">
        <?php require ROOT_PATH."includes/member.inc.php";?>
    </div>
    <div id="member_main">
        <h2>短信详情</h2>
            <dl>
                <dd>发件人:<?php echo $_html['fromuser']?></dd>
                <dd>短信内容:<?php echo $_html['content']?></dd>
                <dd>发送时间:<?php echo $_html['date']?></dd>
                <dd class="btn">
                    <input type="button" value="返回列表" onclick="javascript:history.back()">
                    <input type="button" value="删除短信" onclick="javascript:history.back()">
                </dd>
            </dl>

    </div>

</div>

<?php
//尾部文件
require ROOT_PATH.'includes/footer.inc.php';
?>

</body>
</html>