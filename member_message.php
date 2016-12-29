<?php
if(!isset($_SESSION))
{
    session_start();
}
//error输出
error_reporting(E_ALL);
ini_set('display_errors', '1');
//指定一个常量 用来授权能不能调用文件
define('IN_TG',true);
//定义一个常量 用来指定本页的内容
define('SCRIPT','member_message');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php';
//判断是否登陆
if (!isset($_COOKIE['username'])){
    alert_back('please login,then try again');
}
#批量删除#
if (@$_GET['action']=='delete'&&isset($_POST['id_chkbox'])){
//print_r($_POST['id_chkbox']);
    $_clean=array();
    $_clear['id_chkbox']=mysql_string(implode(',',$_POST['id_chkbox']));
    #!当你进行危险操作时（比如删除）之前，最好还要对唯一标识符进行验证!
    if (!!$_rows=fetch_array("select u_uniqid from bbs_user where u_username='{$_COOKIE['username']}'")) {
        //为了防止Cookie伪造，还要比对一下唯一标识符uniqid
        safe_uniquid($_rows['u_uniqid'], $_COOKIE['uniqid']);
        //开始批量删除
        query("delete from bbs_message where m_id in({$_clear['id_chkbox']})");
        if (affetched_rows()){
            close();
            session_d();
            echo '恭喜你，删除成功';
            //location('删除成功','member_message.php');
        }else{
            close();
            session_d();
            alert_back('删除失败');
        }
    }else{
        alert_back('非法登录');
    }
}
//分页模块
global $_pagenum,$_pagesize;
$sql="select m_id from bbs_message where m_touser='{$_COOKIE['username']}'";
paging_fault_tolerant($sql,4);

//从数据库读取数据
$sql="select m_id,m_fromuser,m_content,m_date,m_state from bbs_message where m_touser='{$_COOKIE['username']}' ORDER BY m_date DESC LIMIT $_pagenum,$_pagesize";
//取得 数据集
$result=query($sql);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
    <!--点击验证码局部刷新js-->
    <script type="text/javascript" src="js/vcode.js"></script>
    <!--客户端验证表单，减少服务器端验证负担-->
    <script type="text/javascript" src="js/member_message.js"></script>
    <?php require ROOT_PATH.'includes/title.inc.php'?>
    <title>会员管理中心-收件箱</title>
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
        <h2>会员短信管理中心</h2>
        <form method="post" action="?action=delete">
            <table cellspacing="1">
            <tr>
                <th>发件人</th>
                <th>短信内容</th>
                <th>时间</th>
                <th>状态</th>
                <th>操作</th>
            </tr>
            <?php
            while(!!$rows=fetch_array_list($result)) {
                $_html = array();
                $_html['id'] = $rows['m_id'];
                $_html['fromuser'] = $rows['m_fromuser'];
                $_html['content'] = $rows['m_content'];
                $_html['date'] = $rows['m_date'];
                $_html = html_spec($_html);
               if (empty($rows['m_state'])){//等价于 $rows['m_state']==0
                   //未读状态图标
                   $_html['state']='<img src="images/noread.gif" alt="未读" title="未读">';
                   //未读状态 将内容加粗
                   $_html['content_state']='<strong>'.summary($_html['content']).'</strong>';
               }else{
                   //已读状态图标
                   $_html['state']='<img src="images/read.gif" alt="已读" title="已读">';
                   //已读正常显示
                   $_html['content_state']=summary($_html['content']);
               }

                ?>
                <tr>
                    <td><?php echo $_html['fromuser']?></td>
                    <td><a href="member_message_detail.php?id=<?php echo $_html['id'];?>" title="<?php echo $_html['content']?>"><?php echo $_html['content_state']?></a></td>
                    <td><?php echo $_html['date']?></td>
                    <td><?php echo $_html['state']?></td>
                    <!--注意下面的复选框的命名必须加上[]这样以数组形式，才能批删除-->
                    <td><input type="checkbox" name="id_chkbox[]" value="<?php echo $_html['id'];?>"/> </td>
                </tr>
            <?php
            }
            ?>
            <tr>
                <td colspan="5">
                    <lable for="all">
                        全选<input type="checkbox" name="checkall" id="checkall">
                        <input type="submit" name="delall" value="批量删除">
                    </lable>
                </td>
            </tr>
          </table>
        </form>

        <?php
        //销毁数据集
        mysql_free_result($result);
        //调用分页函数
        paging(1);
        paging('num');
        ?>

    </div>

</div>

<?php
//尾部文件
require ROOT_PATH.'includes/footer.inc.php';
?>

</body>
</html>