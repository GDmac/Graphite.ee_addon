<?php      
  $cache_file =  unserialize(base64_decode($_GET['f']));
  $theme_path = unserialize(base64_decode($_GET['t']));
       
	$logs = file_get_contents($cache_file);
	$logs = unserialize(base64_decode($logs)); 
                                          
  
	$legend = array();
	$points = array();
	$diff = array(); 
	$temp_t = '';  
	
	foreach ($logs as $val)
	{
			$val = str_replace(array("\t", ' ','&amp;nbsp;','&amp;','&nbsp;'), array('',' ',' ',' ',' ',), htmlentities($val, ENT_QUOTES));


		$x = explode(':', $val, 2);

		if (count($x) > 1)
		{
			$val = str_replace("&gt;","-",$x['0']).' : '.substr($x['1'],0,20);									
		}  
		$legend[] = substr($val,10);
		                                     
		
		if($temp_t!='') {      
			$diff[] = substr($val,1,8) - $temp_t;  
			$temp_t = substr($val,1,8);
		}else { 
			$diff[] = '0';
			$temp_t = substr($val,1,8);
		}     		
		
		$points[] = substr($val,1,8);       
	}                              

  $legend_th = ''; 
  $points_td = '';

  foreach($legend as $ledge){
  	$legend_th .= "<th>".$ledge."</th>";
  }	
  foreach($points as $point){
  	$points_td .= "<td>".$point."</td>";
  }
?>                                   
		                                                                                     
<script src="<?php echo $theme_path; ?>js/raphael-min.js"></script>
<script src="<?php echo $theme_path; ?>js/popup.js"></script>
<script src="<?php echo $theme_path; ?>js/jquery.js"></script>
<script src="<?php echo $theme_path; ?>js/analytics.js"></script>    
<div id="jb_graphite" style="width:100%;background-color : #000;">
	<h2 style="color:#fff; font-family:arial; margin-left:40px">Template Graph</h2>
    <table id="data">
        <tfoot> 
            <tr>        
              <?php echo $legend_th; ?>
            </tr>
        </tfoot>
        <tbody>
            <tr>          
			        <?php echo $points_td; ?> 
            </tr>
        </tbody>
    </table>
    <div id="holder"></div>                                                                         
</div> 
