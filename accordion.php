<?php
/*
Plugin Name: TCVN Accordion Widget
Plugin URI: http://VinaThemes.biz
Description: A good choice for showing serial content to bring more efficiency transferring information to readers.
Version: 1.0
Author: VinaThemes
Author URI: http://VinaThemes.biz
Author email: mr_hiennc@yahoo.com
Demo URI: http://VinaDemo.biz
Forum URI: http://VinaForum.biz
License: GPLv3+
*/

//Defined global variables
if(!defined('TCVN_ACCORDION_DIRECTORY')) 		define('TCVN_ACCORDION_DIRECTORY', dirname(__FILE__));
if(!defined('TCVN_ACCORDION_INC_DIRECTORY')) 	define('TCVN_ACCORDION_INC_DIRECTORY', TCVN_ACCORDION_DIRECTORY . '/includes');
if(!defined('TCVN_ACCORDION_URI')) 			define('TCVN_ACCORDION_URI', get_bloginfo('url') . '/wp-content/plugins/tcvn-accordion-widget');
if(!defined('TCVN_ACCORDION_INC_URI')) 		define('TCVN_ACCORDION_INC_URI', TCVN_ACCORDION_URI . '/includes');

//Include library
if(!defined('TCVN_FUNCTIONS')) {
    include_once TCVN_ACCORDION_INC_DIRECTORY . '/functions.php';
    define('TCVN_FUNCTIONS', 1);
}
if(!defined('TCVN_FIELDS')) {
    include_once TCVN_ACCORDION_INC_DIRECTORY . '/fields.php';
    define('TCVN_FIELDS', 1);
}

class Accordion_Widget extends WP_Widget 
{
	function Accordion_Widget()
	{
		$widget_ops = array(
			'classname' => 'accordion_widget',
			'description' => __('A good choice for showing serial content to bring more efficiency transferring information to readers.')
		);
		$this->WP_Widget('accordion_widget', __('TCVN Accordion Widget'), $widget_ops);
	}
	
	function form($instance)
	{
		$instance = wp_parse_args( 
			(array) $instance, 
			array( 
				'title' 			=> '',
				'categoryId' 		=> '',
				'noItem' 			=> '5',
				'ordering' 			=> 'id',
				'orderingDirection' => 'desc',
				'triggerEvent'		=> 'hover',
				'firstLoad'			=> 'hide',
				'mutilItems'		=> 'no',
				'thumbImage'		=> 'yes',
				'readmore'			=> 'yes',
				'comments'			=> 'yes',
			)
		);

		$title			= esc_attr($instance['title']);
		$categoryId		= esc_attr($instance['categoryId']);
		$noItem			= esc_attr($instance['noItem']);
		$ordering		= esc_attr($instance['ordering']);
		$orderingDirection = esc_attr($instance['orderingDirection']);
		
		$triggerEvent	= esc_attr($instance['triggerEvent']);
		$firstLoad		= esc_attr($instance['firstLoad']);
		$mutilItems		= esc_attr($instance['mutilItems']);
		
		$thumbImage		= esc_attr($instance['thumbImage']);
		$readmore		= esc_attr($instance['readmore']);
		$comments		= esc_attr($instance['comments']);
		?>
        <div id="tcvn-accordion" class="tcvn-plugins-container">
            <div id="tcvn-tabs-container">
                <ul id="tcvn-tabs">
                    <li class="active"><a href="#basic"><?php _e('Basic'); ?></a></li>
                    <li><a href="#display"><?php _e('Display'); ?></a></li>
                    <li><a href="#advanced"><?php _e('Advanced'); ?></a></li>
                </ul>
            </div>
            <div id="tcvn-elements-container">
                <!-- Basic Block -->
                <div id="basic" class="tcvn-telement" style="display: block;">
                    <p><?php echo eTextField($this, 'title', 'Title', $title); ?></p>
                    <p><?php echo eSelectOption($this, 'categoryId', 'Category', buildCategoriesList('Select all Categories.'), $categoryId); ?></p>
                    <p><?php echo eTextField($this, 'noItem', 'Number of Post', $noItem, 'Number of posts to show. Default is: 5.'); ?></p>
                	<p><?php echo eSelectOption($this, 'ordering', 'Post Field to Order By', 
						array('id'=>'ID', 'title'=>'Title', 'comment_count'=>'Comment Count', 'post_date'=>'Published Date'), $ordering); ?></p>
                    <p><?php echo eSelectOption($this, 'orderingDirection', 'Ordering Direction', 
						array('asc'=>'Ascending', 'desc'=>'Descending'), $orderingDirection, 
						'Select the direction you would like Articles to be ordered by.'); ?></p>
                </div>
                <!-- Display Block -->
                <div id="display" class="tcvn-telement">
                	<p><?php echo eSelectOption($this, 'triggerEvent', 'Trigger Event', 
						array('hover'=>'Hover', 'click'=>'Click'), $triggerEvent); ?></p>
                    <p><?php echo eSelectOption($this, 'firstLoad', 'First Load', 
						array('show'=>'Show all items', 'hide'=>'Hide all items', 'first'=>'Show the first item'), $firstLoad); ?></p>
                    <p><?php echo eSelectOption($this, 'mutilItems', 'Multi Items', 
						array('yes'=>'Allow display multi items', 'no'=>'One item display at a time'), $mutilItems); ?></p>
                </div>
                <!-- Advanced Block -->
                <div id="advanced" class="tcvn-telement">
                    <p><?php echo eSelectOption($this, 'thumbImage', 'Thumbnail Image', 
						array('yes'=>'Show thumbnail image', 'no'=>'Hide thumbnail image'), $triggerEvent); ?></p>
                    <p><?php echo eSelectOption($this, 'readmore', 'Readmore', 
						array('yes'=>'Show readmore button', 'no'=>'Hide readmore button'), $triggerEvent); ?></p>
                	<p><?php echo eSelectOption($this, 'comments', 'Comments', 
						array('yes'=>'Show comments', 'no'=>'Hide comments'), $triggerEvent); ?></p>
                </div>
            </div>
        </div>
		<script>
			jQuery(document).ready(function($){
				var prefix = '#tcvn-accordion ';
				$(prefix + "li").click(function() {
					$(prefix + "li").removeClass('active');
					$(this).addClass("active");
					$(prefix + ".tcvn-telement").hide();
					
					var selectedTab = $(this).find("a").attr("href");
					$(prefix + selectedTab).show();
					
					return false;
				});
			});
        </script>
		<?php
	}
	
	function update($new_instance, $old_instance) 
	{
		return $new_instance;
	}
	
	function widget($args, $instance) 
	{
		extract($args);
		
		$title 			= getConfigValue($instance, 'title',		'');
		$categoryId		= getConfigValue($instance, 'categoryId',	'');
		$noItem			= getConfigValue($instance, 'noItem',		'5');
		$ordering		= getConfigValue($instance, 'ordering',		'id');
		$orderingDirection = getConfigValue($instance, 'orderingDirection',	'desc');
		
		$triggerEvent	= getConfigValue($instance, 'triggerEvent',	'hover');
		$firstLoad		= getConfigValue($instance, 'firstLoad',	'hide');
		$mutilItems		= getConfigValue($instance, 'mutilItems',	'no');
		
		$thumbImage		= getConfigValue($instance, 'thumbImage',	'yes');
		$readmore		= getConfigValue($instance, 'readmore',		'yes');
		$comments		= getConfigValue($instance, 'comments',		'yes');
		
		$params = array(
			'numberposts' 	=> $noItem, 
			'category' 		=> $categoryId, 
			'orderby' 		=> $order,
			'order' 		=> $orderingDirection,
		);
		
		if($categoryId == '') {
			$params = array(
				'numberposts' 	=> $noItem, 
				'orderby' 		=> $order,
				'order' 		=> $orderingDirection,
			);
		}
		
		$posts = get_posts($params);
		
		echo $before_widget;
		
		if($title) echo $before_title . $title . $after_title;
		
		if(!empty($posts)) : 
		?>
        <div id="tcvn-accordion-widget">
        	<?php 
				foreach($posts as $post) : 
					$thumbnailId 	= get_post_thumbnail_id($post->ID);				
					$thumbnail 		= wp_get_attachment_image_src($thumbnailId , '70x45');	
					$altText 		= get_post_meta($thumbnailId , '_wp_attachment_image_alt', true);
					$commentsNum 	= get_comments_number($post->ID);
			?>
            <h3 id="tcvn-accordion-title<?php echo $post->ID; ?>" class="accordion-title">
            	<span><?php echo $post->post_title; ?></span>
            </h3>
            <div id="tcvn-accordion-desc<?php echo $post->ID; ?>" class="accordion-description">
            	<?php if(!empty($thumbnail) && $thumbImage == 'yes') : ?>
                <a href="<?php echo get_permalink($post->ID); ?>">
                    <?php echo '<img src="' . $thumbnail[0] . '" alt="'. $altText .'"/>'; ?>
                </a>
                <?php endif; ?>
				<!-- Content Block -->
				<?php echo $post->post_content; ?>
                
                <!-- Comment and Readmore Block -->
				<?php if($comments == 'yes' || $readmore == 'yes') : ?>
                <div class="post-extra">
                    <!-- show comment -->
                    <?php if($comments == 'yes') : ?>
                    <span class="comment">
                        <?php echo ($commentsNum > 1) ? $commentsNum . ' Comments' : $commentsNum . ' Comment'; ?>
                    </span>
                    <?php endif; ?>
                    <!-- show readmore -->
                    <?php if($readmore == 'yes') : ?>
                    <span class="readmore">
                        <a class="readmore" href="<?php echo get_permalink($post->ID); ?>"><?php _e('Readmore'); ?></a>
                    </span>
                    <?php endif; ?>
                    <div style="clear: both;"></div>
                </div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
        <script>
        jQuery(document).ready(function($) {
			var titles 	 = $('#tcvn-accordion-widget h3.accordion-title');
			var elements = $('#tcvn-accordion-widget div.accordion-description');
			
			<!-- First load: hide all items -->
			<?php echo ($firstLoad == 'hide') ? 'elements.hide();' : ''; ?>
			<!-- First load: show all items -->
			<?php echo ($firstLoad == 'show') ? 'titles.addClass("active"); elements.addClass("active");' : ''; ?>
			
			titles.each(function(index, item) {
				<!-- First load: show all the fisrt item -->
				<?php if($firstLoad == 'first') : ?>
				if(index == 0) {
					elements.hide();
					$(item).addClass('active');
					$(item).next().addClass('active').slideDown();
				}
				<?php endif; ?>
				
				<!-- Trigger Event -->
				<?php echo ($triggerEvent == 'hover') ? '$(item).hover(function() {' : '$(item).click(function() {'; ?>
					$this 	= $(this);
      				$target = $this.next();
					
					if(!$target.hasClass('active')) {
						<!-- Multi Items -->
						<?php if($mutilItems == 'no') : ?>
						titles.removeClass('active');
						elements.removeClass('active').slideUp();
						<?php endif; ?>
						
						$this.addClass('active');
						$target.addClass('active').slideDown();
					}
					<?php if($triggerEvent == 'click') : ?>
					else {
						$this.removeClass('active')
						$target.removeClass('active').slideUp();
					}
					<?php endif; ?>
				});
			});
		});
		</script>
		<?php
		endif;
		
		echo $after_widget;
	}
}

add_action('widgets_init', create_function('', 'return register_widget("Accordion_Widget");'));
wp_enqueue_style('tcvn-accordion-css', TCVN_ACCORDION_INC_URI . '/css/style.css', '', '1.0', 'screen' );
wp_enqueue_style('tcvn-accordion-admin-css', TCVN_ACCORDION_INC_URI . '/admin/css/style.css', '', '1.0', 'screen' );
wp_enqueue_script('tcvn-tooltips', TCVN_ACCORDION_INC_URI . '/admin/js/jquery.simpletip-1.3.1.js', 'jquery', '1.0', true);
?>