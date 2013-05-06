<div class="tab_container">
  <table class="tablesorter" cellspacing="0"> 
      <tr>
        <td colspan="2">
           <hr>
        </td>
    </tr>
     <tr>
        <td colspan="2">
           <strong> Keywords</strong>
        </td>
    </tr>
     <tr>
        <td colspan="2">
           <hr>
        </td>
    </tr>
    <?php 
    foreach($content as $k=>$v){?>
     <tr>
        <td>
            <a href="index.php/mintrackdetail/?campaign=<?php echo $_GET['campaign']?>&sdate=<?php echo $_GET['sdate']?>&edate=<?php echo $_GET['edate']?>&keyword=<?php echo $k?>"><?php echo $k?></a>
        </td>
        <td><?php echo $v['count']?></td>
    </tr>
    <?php }
   
    ?>
	</table>
</div>