<?php

function curPageURL() {
 $pageURL = 'http';
 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return $pageURL;
}

class WidgetNetglobers extends WP_Widget {
   
    function WidgetNetglobers() {
        parent::WP_Widget(false, $name = 'Widget Netglobers');
    }
	
    function widget($args, $instance) {
      
      extract( $args );
	  $lang = get_option('netglobers_lang');
	  if($lang=="en")
		$lang = "com";
	  
	  
      echo '<div id="netglobersBox">';
	  ?>
					   
	  <div id="netglobersHeader"><p id="pHeader"><?php echo get_option('netglobers_titleWidget'); ?></p></div>
      <div id="tooltip">
				<p>o&nbsp;&nbsp;&nbsp;<a class="tooltipLink" href="http://www.netglobers.com/node.php?userid=<?php echo $_SESSION['netglobersUserID']; ?>&reset=true" target="_blank" >My travel reports</a></p>
				<p>o&nbsp;&nbsp;&nbsp;<a class="tooltipLink" href="http://www.netglobers.com/travel-reports/" target="_blank" >Traveling soon ?</a></p>
				<p>o&nbsp;&nbsp;&nbsp;<a class="tooltipLink" href="http://www.netglobers.com/widget-netglobers-wordpress.html" target="_blank" >Get this widget</a></p>
				<br/>
				<p><a class="tooltipLink" href="http://www.netglobers.com" target="_blank" >Visit us at NetGlobers</a></p>	
	  </div>
      <div id="contentMap"></div>
      <div id="netglobersFooter">
            <a id="liFacebook" href="http://www.facebook.com/sharer.php?u=http://www.netglobers.com/&t=" target="blank" rel="nofollow"></a>
            <a id="liTwitter" href="http://twitter.com/home?status=Check my cool NetGlobers travel globe. http://www.netglobers.com/widget-netglobers-wordpress.html" rel="nofollow" title="Share on Twitter" target="_blank"></a>
            <a id="liMore" class="togglerTooltip" href="#tooltip"></a>
            <p id="txtMore" class="togglerTooltip" title="More">More</p>
            <a id="liNetglobers" href="http://www.netglobers.com" target="_blank" ><img src="<?php echo WP_PLUGIN_URL.'/netglobers-widget/includes/css/images/logoNetglobers.gif'; ?>" /></a>
      </div>
	  <script type="text/javascript" charset="utf-8">
			//      Variable du domaine, passe à Xiti
				var xtdomain='netglobers.com';
				<!--
				xtnv = document;        //parent.document or top.document or document         
				xtsd = "http://logi103";
				xtsite = "417968";
				xtn2 = "";        // level 2 site 
				xtpage = "widget_globe";        //page name (with the use of :: to create chapters)
				xtdi = "";        //implication degree
            //-->
      </script>
      <script type="text/javascript" src="http://www.netglobers.com/media/script/xtcore.js"></script>
      <noscript>
			<div id="xiti-logo-noscript">
				<img width="1" height="1" src="http://logi103.xiti.com/hit.xiti?s=417968&amp;s2=&amp;p=&amp;di=&amp;" alt="XiTi" />
			</div>
	  </noscript>
<?php
      echo '</div>';

    }

}
global $myWidget;
$myWidget = new WidgetNetglobers();

?>