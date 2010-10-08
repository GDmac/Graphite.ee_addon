<?php
/*
=====================================================
 ExpressionEngine - by EllisLabs
-----------------------------------------------------
 http://www.experssionengine.com/
=====================================================  
This plugin was created by Joel Bradbury
- hello@joelbradbury.net
- http://joelbradbury.net    
 This software is licensed under a GNU license.
 - http://www.gnu.org/copyleft/gpl.html
=====================================================
 File: pi.jb_graphite.php
-----------------------------------------------------
 Purpose: Turn the template debug output into a useful graph
=====================================================
*/

$plugin_info = array(
						'pi_name'			=> 'JB Graphite',
						'pi_version'		=> '1.1',
						'pi_author'			=> 'Joel Bradbury',
						'pi_author_url'	=> 'http://joelbradbury.com/',
						'pi_description'	=> 'Turn the template debug output into a useful graph',
						'pi_usage'			=> jb_graphite::usage()
					);


class Jb_graphite {
 
// ----------------------------------------
//  Plugin Usage
// ----------------------------------------

// This function describes how the plugin is used.

function usage()
{
   ob_start(); 

?> 
WARNING
================================================================
Only use this plugin if you know what you're doing. 
To install this plugin you will have to make a change to a core ExpressionEngine file.
It would be nice if this wasn't necessary, but as the code runs so late (after all the core processing has taken placed) it is unavoidable.

INSTALLATION
================================================================

1. Open the core EE file found at [[ /system/expressionengine/controllers/ee.php ]] 
(be careful, there be dragons here)

2. Jump to around line 80, and look for the following line : 
--------------------------------------------------------  
	if ($this->session->userdata['group_id'] == 1)
	{
--------------------------------------------------------  

3. Immediately after this (inside the conditional) paste :
--------------------------------------------------------  
$output .= $this->_make_chart();	 
--------------------------------------------------------
  
4. Jump down to the bottom of the file and just before the final closing '}', paste the following function
--------------------------------------------------------  
function _make_chart(){

	$data = base64_encode(serialize($this->TMPL->log));       
	$filename = implode('-',$this->uri->segments).'_'.time();  

	//save to a dump file  		               
  $cache_path = APPPATH.'cache/graphite_cache/';                                          	
  $cache_file = $cache_path . $filename;         

  if ( ! @is_dir($cache_path))
  {
    if ( ! @mkdir($cache_path, 0777))
    {       
      return FALSE;
    }
  @chmod($cache_dir, 0777);            
  }	

  if ( ! $fp = @fopen($cache_file, 'wb'))
  {
    return FALSE;
  }      
  flock($fp, LOCK_EX);
  fwrite($fp, $data);
  flock($fp, LOCK_UN);
  fclose($fp);
  @chmod($cache_file, 0777);    
         
	$theme_folder_url = $this->config->item('theme_folder_url');
	if (substr($theme_folder_url, -1) != '/') $theme_folder_url .= '/';
  $theme_folder_url = $theme_folder_url.'third_party/jb_graphite/';


  $f = base64_encode(serialize($cache_file));
  $t = base64_encode(serialize($theme_folder_url));
  
	return "<iframe height='500px' width='98%' src='".$theme_folder_url."graphite.php?t=".$t."&f=".$f."'></iframe>";                                                                                                                    

}
--------------------------------------------------------  

5. Load, review, tune. 
If all has gone well, move to the front end of your site and reload the page. Immediatly before the template debuging output you should see the same information in graph form. 
It should look something like this : http://joelbradbury.net/code/grahite_1.jpg

TROUBLESHOOTING
================================================================
If you're having problems with this, maybe this plugin isn't for you. You need to know what you're doing to use it. 
I can offer help to get it working, just email me at hello@joelbradbury.net, but I take no responsibility for screwing up your ExpressionEngine core.

Remember, back things up before dicking around with the core EE files. 

<?php  
$buffer = ob_get_contents();
	
ob_end_clean(); 

return $buffer;
}
// END


}
// END CLASS


?>
