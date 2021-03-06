<?php
//error输出
error_reporting(E_ALL);
ini_set('display_errors', '1');
//指定一个常量 用来授权能不能调用文件
define('IN_TG',true);
//定义一个常量 用来指定本页的内容
define('SCRIPT','blog');
//
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php';
###分页模块#############################################################
/*limit n,m //n是从第几条数据开始读起，默认是0；m是读取的条数
 * 假设$page_size=10 每页显示10条数据; $pagenum=($GET['page']-1)*$pagesize
 * page=1 说明是第1页数据 表示1-10条数据 LIMIT 0,10; $page_num=0;
 * page=2 说明是第2页数据 表示11-20条数据 LIMIT 10,10; $page_num=10;
 * page=3 说明是第3页数据 表示21-30条数据 LIMIT 20,10; $page_num=20;
 *  */
//分布模块 分页容错处理
global $_pagenum,$_pagesize;
global $sys;
$sql="select u_id from bbs_user";
//$sys['sys_blog']为后台系统设置指定的，每页的列表数
paging_fault_tolerant($sql,$sys['blog']);

//从数据库读取数据
$sql="select u_id,u_username,u_sex,u_face from bbs_user ORDER BY u_regtime DESC LIMIT $_pagenum,$_pagesize";
//取得 数据集
$result=query($sql);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
    <script type="text/javascript" src="js/blog.js"></script>
    <?php require ROOT_PATH.'includes/title.inc.php'?>
    <!--<title>博友页面</title>-->
</head>
<body>
<?php
//使用硬路径引入速度比相对路径快很多
require ROOT_PATH.'includes/header.inc.php';
?>

<div id="blog">
    <h2>博友</h2>
    <!--while(!!rows=fetch_array($sql))将导致死循环，一直取出数据库第一条数据-->
    <!--while(!!rows=mysql_fetch_array($result)),是没问题的-->
    <!--我们必须是每次重新读取结果集，而不是每次重新执行一次SQL,而上边的fetch_array($sql)就是重复执行SQl导致死循环-->
    <?php
        $_html=array();
        while(!!$rows=fetch_array_list($result)){

            $_html['id']=$rows['u_id'];
            $_html['username']=$rows['u_username'];
            $_html['face']=$rows['u_face'];
            $_html['sex']=$rows['u_sex'];
            $_html=html_spec($_html);
    ?>
    <dl>
        <dd class="user"><?php echo $_html['username'];?></dd>
        <dt><img src="<?php echo $_html['face'];?>" alt="root"/></dt>
        <dd class="message"><a name="message" title="<?php echo $_html['id']?>">发消息</a></dd>
        <dd class="friend"><a name="friend" title="<?php echo $_html['id']?>">加好友</a></dd>
        <dd class="guest">写留言</dd>
        <dd class="flower"><a name="flower" title="<?php echo $_html['id']?>">给他送花</a></dd>
    </dl>
    <?php } ?>

    <?php
    //销毁数据集
    mysql_free_result($result);
    //调用分页函数
    paging(1);
    paging(2);
    ?>

</div>


<?php
require ROOT_PATH.'includes/footer.inc.php';
?>
</body>
</html>