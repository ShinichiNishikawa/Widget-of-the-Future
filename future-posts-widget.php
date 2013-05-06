<?php
/*
Plugin Name: Widget of the Future
Plugin URI: 
Description: This plugin adds a widget to list future posts in your sidebar. You can choose how many posts to display.
Author: Shinichi Nishikawa
Version: 0.1
Author URI: http://en.nskw-style.com/
*/

add_action(
	'widgets_init',
	create_function('', 'return register_widget("WidgetFuturePosts");')
);
 
class WidgetFuturePosts extends WP_Widget {
 
function __construct() {
	$widget_ops = array('description' => 'Displays future posts to your sidebar.');
	$control_ops = array();
	parent::__construct(
		false,
		'Future Posts Widget',
		$widget_ops,
		$control_ops
	);
}
 
public function form($par) {

	// Title
	$title = (isset($par['title']) && $par['title']) ? $par['title'] : '';
	$id = $this->get_field_id('title');
	$name = $this->get_field_name('title');
	echo 'Title: <br />';
	echo '<input type="text" id="'.$id.'" name="'.$name.'" value="';
	echo trim( htmlentities( $title, ENT_QUOTES, 'UTF-8' ) );
	echo '" />';
	echo '<br />';
	 
	// input howmany posts to display. default:5
	$count = (isset($par['pcount']) && $par['pcount']) ? $par['pcount'] : 5;
	$id = $this->get_field_id('pcount');
	$name = $this->get_field_name('pcount');
	echo 'Count: <br />';
	echo '<input type="text" id="'.$id.'" name="'.$name.'" value="';
	echo trim( htmlentities( $count, ENT_QUOTES, 'UTF-8'));
	echo '" />';
	echo '<br />Default: 5';
}
 
public function update($new_instance, $old_instance) {
	return $new_instance;
}
 
public function widget($args, $par) {
	$count = (isset($par['pcount']) && $par['pcount']) ? (int)$par['pcount'] : 5;
	echo $args['before_widget'];
	echo $args['before_title'];
	echo trim(htmlentities($par['title'], ENT_QUOTES, 'UTF-8'));
	echo $args['after_title'];


	$post_staus = array( 'future' );

	$args2 = array(
		'post_type' => 'post',
		'post_status' => $post_staus,
		'posts_per_page' => $count,
		'orderby' => 'date',
		'order' => 'ASC'
	);
	$my_query = new WP_Query( $args2 );
	
	echo '<ul>';
	while ( $my_query->have_posts() ):
	$my_query->the_post();
	?>
	
		<li><span class="futuredate"><?php the_date(); ?>: </span><?php the_title(); ?></li>
	
	<?php
	endwhile;
	echo '</ul>';
	echo $args['after_widget'];
	wp_reset_postdata();
}

}