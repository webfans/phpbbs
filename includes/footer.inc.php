<?php
if (!defined('IN_TG')){
    echo 'Access denied';
}
$end_time=runtime();
//mysql_close();
?>
<div id="footer">
    <p>本程序执行耗时为:<?php echo $end_time-START_TIME;?></p>
    <p>版权所有，盗版必究</p>
    <p>本程序由<span>我爱你心心理咨询论坛</span>提供&copy;www.psy520.cn</p>
</div>

