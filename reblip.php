<?php
/*
Plugin Name: reBlip
Plugin URI: http://blipy.pl/wp_plugin
Description: plugin umozliwiajacy latwe blipowanie o postach.
Version: 0.2
Author: tentam
Author URI: http://tentam.tychtam.pl
*/
/*  Copyright 2009  tentam  (email : tentam z tychtam.pl)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

function add_reblip($text){
  global $wp_query;
  $post = $wp_query->post;
  $permalink = get_permalink($post->ID);
  $title = get_the_title($post->ID);
  $fg_val = get_option( 'reblip_fg' );
  $bg_val = get_option( 'reblip_bg' );
  $reblip_fb = get_option( 'reblip_fb' );
  
  if ($fg_val == ""){
  	$fg_val = 'ffffff';
  }
  if ($bg_val == ""){
  	$bg_val = '000000';
  }
  if($reblip_fb != "new"){
  	$url = 'http://blipy.pl/js/blipy.js';
  }else{
   $url = 'http://blipy.pl/js/blipy1.js';
  }

  $text = "<div id=\"reblip\" style=\"padding: 5px;\"><script type=\"text/javascript\">" .
          "url = '" . $permalink . "';" . 
          "title = '" . $title . "';" .
          "fg = '" . $fg_val . "';" .
          "bg = '" . $bg_val . "';" .
          "</script>" .
          "<script src=\"" . $url . "\"></script></div>" . $text;
  return $text;
}

add_filter( 'the_content', 'add_reblip');

add_action('admin_menu', 'reblip_menu');

function reblip_menu() {
  $mypage = add_submenu_page('plugins.php','reblip configuration','Konfiguracja reblip','administrator','reblip conf','reblip_options');
  add_action( "admin_print_scripts-$mypage", 'reblip_admin_head' );
}

function reblip_admin_head() {
   wp_enqueue_script('jscolor', '/wp-content/plugins/reblip/jscolor/jscolor.js');
   wp_enqueue_script('reblip', '/wp-content/plugins/reblip/reblip.js');
    
}



function reblip_options() {
    // variables for the field and option names 
    $reblip_fg = 'reblip_fg';
    $reblip_bg = 'reblip_bg';
    $data_reblip_fg = 'reblip_fg';
    $data_reblip_bg = 'reblip_bg';
    $hidden_field_name = 'reblip_hidden';
    $reblip_fb = 'reblip_fb';


    // Read in existing option value from database
    $fg_val = get_option( $reblip_fg );
    $bg_val = get_option( $reblip_bg );
    $fb_val = get_option( $reblip_fb );

    if ($fg_val == ""){
    	$fg_val = 'ffffff';
    }
	 if ($bg_val == ""){
    	$bg_val = '000000';
    }

    // See if the user has posted us some information
    // If they did, this hidden field will be set to 'Y'
    if( $_POST[ $hidden_field_name ] == 'Y' ) {
        // Read their posted value
        $fg_val = $_POST[ $data_reblip_fg ];
		  $bg_val = $_POST[ $data_reblip_bg ];
		  $fb_val = $_POST[ $reblip_fb ];

        // Save the posted value in the database
        update_option( $reblip_fg, $fg_val );
        update_option( $reblip_bg, $bg_val );
        update_option( $reblip_fb, $fb_val );

        // Put an options updated message on the screen

?>
<div class="updated"><p><strong><?php _e('Zapisano konfigurację.', 'reblip_trans_domain' ); ?></strong></p></div>
<?php
	 }
	 // Now display the options editing screen

    echo '<div class="wrap">';

    // header

    echo "<h2>" . __( 'Reblip Options', 'reblip_trans_domain' ) . "</h2>";

    // options form
    
    ?>

<form name="form1" method="post" action="">
<input type="radio" name="reblip_fb" value="old" <?php if($fb_val != "new"){ ?>checked="checked"<?php }; ?>> Stary przycisk:<img src="http://blipy.pl/reblip_plugin/example.jpg"/><br />
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
<br/><br/>
<table>
<tr><td><input type="radio" name="reblip_fb" value="new" <?php if($fb_val=="new"){ ?>checked="checked"<?php };?>>Nowy przycisk:</td>
<td>
<p><?php _e("Kolor napisu reblip:", 'reblip_trans_domain' ); ?><br /> 
<input id="reblip_bbg" onChange="changeColor1()" type="text" class="color" name="<?php echo $data_reblip_fg; ?>" value="<?php echo $fg_val; ?>" size="20">
</p>
<p><?php _e("Kolor tła:", 'reblip_trans_domain' ); ?><br /> 
<input id="reblip_ffg" onChange="changeColor2()" type="text" class="color" name="<?php echo $data_reblip_bg; ?>" value="<?php echo $bg_val; ?>" size="20">
</p><hr />
</td>
<td>
<div style="margin-left: 100px;"><div class="reblip2" style="float: left;">
<a id="c1" href="#" title="Pokaż blipnięcia" target="_blank" style="color: #<?php echo $bg_val; ?>; background-color: #<?php echo $fg_val; ?>; display:block; padding: 2px; text-align:center; text-decoration:none;">0</a>
</div><div class="reblip2" style="float: left;">
<a href="#" target="_blank" rel="nofollow" style="color: #<?php echo $fg_val; ?>; background-color: #<?php echo $bg_val; ?>;  display:block; padding: 2px; text-align:center; text-decoration:none;" id="c2">reblip</a></div></div>
</td>
</tr>
<tr><td>
<p class="submit">
<input type="submit" name="Submit" value="<?php _e('Zapisz', 'reblip_trans_domain' ) ?>" />
</p>
</td>
</tr>
</table>
</form>
</div>

<?php
 
}


?>
