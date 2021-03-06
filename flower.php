<?php
session_start();
//指定一个常量 用来授权能不能调用文件
define('IN_TG',true);
//定义一个常量 用来指定本页的内容
define('SCRIPT','flower');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php';
require dirname(__FILE__) . '/includes/check.func.php';
//判断是否登录
if (!isset($_COOKIE['username'])){
    alert_back_close('请登录后尝试');
}
//送花
if ($_GET['action']=='send'){
    //验证吗检验
    check_vcode($_POST['vcode'],$_SESSION['vcode']);
    if (!!$rows=fetch_array("select u_uniqid from bbs_user where u_username='{$_COOKIE['username']}'")){
        //为了防止Cookie伪造，还要比对一下唯一标识符uniqid
        safe_uniqid($rows['u_uniqid'],$_COOKIE['uniqid']);
        $_clean=array();
        $_clean['touser']=$_POST['touser'];
        $_clean['fromuser']=$_COOKIE['username'];
        $_clean['flowernum']=$_POST['flower_num'];
        $_clean['content']=check_content($_POST['contents'],5,200);
        //一次性转义数据
        $_clean=mysql_string($_clean);
        //开始将数据写入表
        query("insert into bbs.bbs_flower ( 
                                            fl_touser, 
                                            fl_fromuser, 
                                            fl_num,
                                            fl_content,
                                            fl_date
                                            ) 
                                    VALUES (
                                            '{$_clean['touser']}',  
                                            '{$_clean['fromuser']}',  
                                            '{$_clean['flowernum']}',    
                                            '{$_clean['content']}',
                                            NOW()
                                            )
              ");
        //新增消息成功
        if (affetched_rows()==1){
            //清空session,腾出内存
             //session_d();
            close();
            alert_back_close('送花成功');
        }
        else{
             //session_d();
            close();
            alert_back('送花失败');
        }

    }else{
        alert_back_close('唯一标识符异常');
    }
}
//获取数据
if (isset($_GET['id'])){
    $sql="select u_username from bbs_user where u_id='{$_GET['id']}' limit 1";;
    $rows=fetch_array($sql);
    //如果有数据
    if (!!$rows){
        $_html=array();
        $_html['touser']=$rows['u_username'];
        $_html=html_spec($_html);
    }else{
        alert_back_close('不存在此用户');
    }
}else{
    alert_back_close('非法操作');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
    <?php require ROOT_PATH.'includes/title.inc.php'?>
    <script type="text/javascript" src="js/vcode.js"></script>
    <script type="text/javascript" src="js/message.js"></script>
    <!--<title>送花</title>-->
</head>
<body>
<div id="flower">
    <h2>送花</h2>
    <form method="post" action="?action=send">
        <input type="hidden" name="touser" value="<?php echo $_html['touser'] ?>"/>
        <dl>
            <dd>
                <!--用来显示，上边的隐藏字段用来传递数据-->
                <input type="text" readonly="readonly" class="text" value="<?php echo 'TO:'.$_html['touser'];?>">
                <select name="flower_num">
                   <?php
                   foreach (range(1,50) as $num){
                       echo '<option value="'.$num.'">x'.$num.'朵</option>';
                   }
                   ?>
                </select>
            </dd>
            <dd><textarea name="contents">我灰常的欣赏你啦，给你送花哦...</textarea></dd>
            <dd>验 证 码：<input type="text" name="vcode" class="text yzm" value=""> <img src="vcode.php" id="vcode"/> </dd>
            <dd><input type="submit" name="submit" class="submit" value="给他送花"> </dd>
        </dl>
    </form>
</div>
</body>
</html>