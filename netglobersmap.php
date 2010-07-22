<?php
/*
Plugin Name: NetGlobers Widget
Plugin URI: http://www.netglobers.com/widget-netglobers-wordpress.html
Description: All your trips in a smart widget, brought to you by NetGlobers - Europ Assistance, to share your travel tips with the world
Author: NetGlobers
Version: 1.0
Author URI: http://www.netglobers.com
*/

class Netglobersmap {

    var $langs = array('fr'=>'French','en'=>'English','de'=>'German','es'=>'Spanish','it'=>'Italian');
    var $colorFields = array("colorFlash"=>"Background color","colorFleches"=>"Arrows color","colorBordure"=>"Border color","colorPolice"=>"Font color","colorFooter"=>"Header and footer color");
    var $minFlashWidth = 250;
    var $maxFlashWidth = 450;
    
    var $defaultFields = array('netglobers_titleWidget'=>'My NetGlobe','netglobers_widthFlash'=>300,'netglobers_lang'=>'fr','netglobers_colorFlash'=>'#FFFFFF','netglobers_colorFleches'=>'#000000','netglobers_colorBordure'=>'#000000','netglobers_colorFooter'=>'#000000','netglobers_colorPolice'=>'#BBBBBB');
    var $ws = "http://www.netglobers.com/media/custom/getUserIdByMail.php";
    function Netglobersmap()
    {
        add_action("wp_head", array(&$this, 'netglobersmap_head'));
		add_action("init", array(&$this, 'netglobersmap_init'));
        add_action('widgets_init', array(&$this,'fct_widgets_init') );
        add_action('admin_menu', array(&$this,'netglobers_add_pages'));

        foreach($this->defaultFields AS $k=>$v)
        {
            if(!get_option($k))
                add_option($k,$v);
        }
    }

    function netglobers_add_pages ()
    {
        add_menu_page('Netglobers', 'Netglobers', 'administrator','netglobers-top-level-handle',  array(&$this,'netglobers_admin_page'));

    }

    function netglobers_admin_page()
    {

?>
<script type="text/javascript">
	var pluginUrl = "<?php echo WP_PLUGIN_URL.'/netglobers-widget/'; ?>";
</script>

<div class="wrap" id="formNetglobersAdmin">
    <h2>Netglobers Administration</h2>
    <ul class="messages">
        <li class="notice-msg">
            <ul>
				<li><?php echo 'A NetGlobers account is required to activate the widget : enter the email address you are using for your NetGlobers account'; ?></li>
                <li><?php echo 'Flash size should be between '.$this->minFlashWidth.'px and '.$this->maxFlashWidth. 'px'; ?></li>
            </ul>
        </li>
    </ul>

<?php

    if(!empty($_POST)) {
        $validate = $this->postAction();
        echo "<ul class='messages'>";
        if(is_array($validate)): // Si il y a des erreurs    
            echo "<li class='error-msg'>";
            echo "<ul>";
            foreach($validate AS $error):
                echo "<li>".$error."</li>";
            endforeach;
            echo "</ul>";
            echo "</li>";
        else:
            echo "<li class='success-msg'>";
            echo "<ul>";
            echo "<li>Parameters have been saved<li>";
            echo "</ul>";
            echo "</li>";
        endif;
        echo "</ul>";
    }

    $userMail = get_option("netglobers_userMail");
    $width = get_option("netglobers_widthFlash");
    $langOpt = get_option("netglobers_lang");
    $colorFlash = get_option("netglobers_colorFlash");
    $colorFleches = get_option("netglobers_colorFleches");
	$titleWidget = get_option("netglobers_titleWidget");
    $colorBordure = get_option("netglobers_colorBordure");
    $colorPolice = get_option("netglobers_colorPolice");
    $colorFooter = get_option("netglobers_colorFooter");

?>
    <form method="POST" action="#">
		
        <h3>Flash parameters</h3>

        <label for="userMail">User email: </label>
        <input type="text" name="userMail" id="userMail" value="<?php echo $userMail; ?>" />
		<span id="iconValidUserMail"></span>
		<span id="validUserMail"></span>
        <br/>

        <label for="widthFlash">Flash size (pixels): </label>
        <input type="text" name="widthFlash" id="widthFlash" value="<?php echo $width; ?>" />

        <br/>

        <label for="lang">Language: </label>
        <select name="lang" id="lang">
<?php
        foreach($this->langs AS $code=>$lang):
            $selected = ($code == $langOpt) ? 'SELECTED="SELECTED"' : '';
?>
            <option <?php echo $selected; ?> value="<?php echo $code; ?>"><?php echo $lang; ?></option>
<?php   endforeach; ?>
        </select>
         <br/>

         <label for="colorFlash">Background color: </label>
         <div id="colorSelectorFlash" class="colorSelector"><div></div></div>
         <input type="hidden" id="colorFlash" name="colorFlash" value="<?php echo $colorFlash; ?>" />

         <br/>

         <label for="colorFleches">Arrows color: </label>
         <div id="colorSelectorFleches" class="colorSelector"><div></div></div>
         <input type="hidden" id="colorFleches" name="colorFleches" value="<?php echo $colorFleches; ?>" />

         <br/>

         <h3>Widget parameters</h3>
		 
		<label for="titleWidget">Widget title: </label>
        <input type="text" name="titleWidget" id="titleWidget" value="<?php echo $titleWidget; ?>" />
		
		<br/>

         <label for="colorBordure">Border color: </label>
         <div id="colorSelectorBordure" class="colorSelector"><div></div></div>
         <input type="hidden" id="colorBordure" name="colorBordure" value="<?php echo $colorBordure; ?>" />

         <br/>

         <label for="colorPolice">Font color: </label>
         <div id="colorSelectorPolice" class="colorSelector"><div></div></div>
         <input type="hidden" id="colorPolice" name="colorPolice" value="<?php echo $colorPolice; ?>"  />

         <br/>

         <label for="colorFooter">Header and footer colors: </label>
         <div id="colorSelectorFooter" class="colorSelector"><div></div></div>
         <input type="hidden" id="colorFooter" name="colorFooter" value="<?php echo $colorFooter; ?>"  />

         <br/>

        <input type="submit" name="submit" value="Save" />
    </form>
</div>
<?php
    }

    function postAction()
    {
        $errors = array();
        
         if(empty($_POST['userMail']))
             $errors [] = "Please enter an email address";
         else if(!$this->is_valid_email($_POST['userMail']))
             $errors [] = "The email address format is not valid";
	     else
		 {
			$xml = $this->userIdWebService($_POST['userMail']);
			$result = $xml->result;
			if($result == "KO")
				$errors [] = "Please enter an email address associated to a NetGlobers account";
		 }
			 
		 if(empty($_POST['titleWidget']))
             $errors [] = "Please enter a widget title";

         foreach($this->colorFields AS $k=>$v)
         {
            if( (empty($_POST[$k])) || (!$this->is_valid_color($_POST[$k])) )
                $errors [] = "The color '".$v."' is not valid";
         }

         if( (empty($_POST['lang'])) || (!array_key_exists($_POST['lang'],$this->langs)) )
                 $errors [] = "Please select a language";

         if( (empty($_POST['widthFlash'])) || ($_POST['widthFlash'] < $this->minFlashWidth) || ($_POST['widthFlash'] > $this->maxFlashWidth) )
                $errors [] = "The flash size is not valid";

        if(!empty($errors))
            return $errors;

        update_option('netglobers_userMail',$_POST['userMail']);
		update_option('netglobers_titleWidget',$_POST['titleWidget']);

        $change = (!empty($_POST['widthFlash'])) ? update_option('netglobers_widthFlash',$_POST['widthFlash']) : update_option('netglobers_widthFlash',str_replace('#','0x',$this->defaultFields['netglobers_widthFlash']));
        $change = (!empty($_POST['lang'])) ? update_option('netglobers_lang',$_POST['lang']) : update_option('netglobers_lang',str_replace('#','0x',$this->defaultFields['netglobers_lang']));
        $change = (!empty($_POST['colorFlash'])) ? update_option('netglobers_colorFlash',$_POST['colorFlash']) : update_option('netglobers_colorFlash',str_replace('#','0x',$this->defaultFields['netglobers_colorFlash']));
        $change = (!empty($_POST['colorFleches'])) ? update_option('netglobers_colorFleches',$_POST['colorFleches']) : update_option('netglobers_colorFleches',str_replace('#','0x',$this->defaultFields['netglobers_colorFleches']));
        $change = (!empty($_POST['colorBordure'])) ? update_option('netglobers_colorBordure',$_POST['colorBordure']) : update_option('netglobers_colorBordure',str_replace('#','0x',$this->defaultFields['netglobers_colorBordure']));
        $change = (!empty($_POST['colorPolice'])) ? update_option('netglobers_colorPolice',$_POST['colorPolice']) : update_option('netglobers_colorPolice',str_replace('#','0x',$this->defaultFields['netglobers_colorPolice']));
        $change = (!empty($_POST['colorFooter'])) ? update_option('netglobers_colorFooter',$_POST['colorFooter']) : update_option('netglobers_colorFooter',str_replace('#','0x',$this->defaultFields['netglobers_colorFooter']));

        return true;
    }

    function netglobersmap_init()
    {
        wp_enqueue_style("netglobers",WP_PLUGIN_URL.'/netglobers-widget/includes/css/netglobers.css');
        if( is_admin()) {
			wp_enqueue_style("colorpicker",WP_PLUGIN_URL.'/netglobers-widget/includes/css/colorpicker.css');
            wp_enqueue_script("custom_colorpicker",WP_PLUGIN_URL.'/netglobers-widget/includes/js/custom_colorpicker.js');
            wp_enqueue_script("netglobers_admin",WP_PLUGIN_URL.'/netglobers-widget/includes/js/netglobers_admin.js',array('colorpicker'));
        }
        else
        {
            wp_enqueue_script("swfobject",'swfobject.js');
            wp_enqueue_script("netglobersmap",WP_PLUGIN_URL.'/netglobers-widget/includes/js/netglobersmap.js',array('jquery'));
        }
    }
	
	/* Appel pour récupérer l'ID à partir du Mail */
	function userIdWebService($email)
	{
		if(!function_exists("simplexml_load_file")){
            require_once(dirname(__FILE__).'/includes/simplexml/simplexml.class.php');

            function simplexml_load_file($file){
                $sx = new simplexml;
                return $sx->xml_load_file($file);
            }
        }

        $xml = simplexml_load_file($this->ws.'?mail='.$email);
		
		return $xml;
	}

	function netglobersmap_head()
	{
			$xml = $this->userIdWebService(get_option('netglobers_userMail'));
		
            $userID = $xml->id;
            if(is_array($userID))
               $userID = $userID[0];

			$_SESSION['netglobersUserID'] = $userID;
			
            ?>
             <script type="text/javascript">
                var flashvars = {
                      lang: "<?php echo get_option("netglobers_lang"); ?>",
                      iduser :  "<?php echo $userID; ?>",
                      url : "http://www.netglobers.com/ifctrade/flash/",
                      flashsize : <?php echo get_option('netglobers_widthFlash'); ?>,
                      bgcolor : "<?php echo str_replace('#','0x',get_option('netglobers_colorFlash')); ?>",
                      flechescolor : "<?php echo str_replace('#','0x',get_option('netglobers_colorFleches')); ?>",
					  urlphp : "http://www.netglobers.com/media/custom/ws.php"
                 };

                 var params = {
					  bgcolor: "<?php echo get_option('netglobers_colorFlash'); ?>",
					  wmode: "transparent"
                 };
                 
                 var borderColor =  "<?php echo get_option('netglobers_colorBordure'); ?>";
                 var fontColor =  "<?php echo get_option('netglobers_colorPolice'); ?>";
                 var footerColor =  "<?php echo get_option('netglobers_colorFooter'); ?>";
                 var urlFlash = "http://www.netglobers.com/ifctrade/flash/Travel_map.swf";

             </script>
        <?php
	}

    function fct_widgets_init()
    {
        require_once(dirname(__FILE__).'/widget_netglobersmap.php');
        register_widget('WidgetNetglobers');
    }

    function is_valid_email($email) {
          $result = true;
          if(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", $email)) {
            $result = false;
          }
          return $result;
    }

    function is_valid_color($color) {

          $result = true;
          if (!eregi ("^\#[A-Fa-f0-9]{6}$" , $color ) )
            $result = false;
          
          return $result;
    }

}

global $netglobersmap;
$netglobersmap = new Netglobersmap();


?>