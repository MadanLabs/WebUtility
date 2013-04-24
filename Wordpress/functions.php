<?php

// Registrazione Sidebar
register_sidebar( array(
	'name' => __( 'Sidebar principale'),
	'id' => 'sidebar-1',
	'before_widget' => '',
	'after_widget' => "",
	'before_title' => '',
	'after_title' => '',
));


// Creazione Widget
class banner1 extends WP_Widget {
	function __construct() {
		parent::WP_Widget('Banner1', 'Banner #1', array('description' => 'Primo banner'));
	}

	function widget($args, $instance) {
		//estraggo variabili da args:
		extract($args);
		$str = "<div id='sponsor1' class='sponsor'><a href='";
		$str .= $instance['link'];
		$str .= "'>";
		if(!empty($instance['img'])) {
			$str .= "<img width='230' height='130' src='";
			$str .= get_site_url();
			$str .= "/wp-content/themes/atletico_luinese/images/";
			$str .= $instance['img'];
			$str .= "' />";
		}
		$str .= "</a></div>";
		echo $str;
	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['img'] = strip_tags($new_instance['img']);
		$instance['link'] = strip_tags($new_instance['link']);
		return $instance;
	}

	function form($instance) {
	if ( $instance ) {
		//codifico html
		$img = esc_attr($instance['img']);
		$link = esc_attr($instance['link']);
	}
	// Campi per modificare
	?>
	<label for="<?php echo $this -> get_field_id('link');?>"><?php _e('Link:'); ?></label>
	<input id="<?php echo $this -> get_field_id('link');?>" class="widefat" type="text" name="<?php echo $this -> get_field_name('link');?>" value="<?php echo $link; ?>" 
	<label for="<?php echo $this -> get_field_id('img');?>"><?php _e('Immagine:'); ?></label>
	<input id="<?php echo $this -> get_field_id('img');?>" class="widefat" type="text" name="<?php echo $this -> get_field_name('img');?>" value="<?php echo $img;?>" />
	<?php }
}
?>