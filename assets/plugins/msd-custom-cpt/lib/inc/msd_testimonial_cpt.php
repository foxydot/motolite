<?php 
if (!class_exists('MSDTestimonialCPT')) {
	class MSDTestimonialCPT {
		//Properties
		var $cpt = 'testimonial';
		//Methods
	    /**
	    * PHP 4 Compatible Constructor
	    */
		public function MSDTestimonialCPT(){$this->__construct();}
	
		/**
		 * PHP 5 Constructor
		 */
		function __construct(){
			global $current_screen;
        	//"Constants" setup
        	$this->plugin_url = plugin_dir_url('msd-custom-cpt/msd-custom-cpt.php');
        	$this->plugin_path = plugin_dir_path('msd-custom-cpt/msd-custom-cpt.php');
			//Actions            
			add_action( 'init', array(&$this,'register_taxonomy_testimonial_type') );
            add_action( 'init', array(&$this,'register_cpt_testimonial') );
            add_action( 'init', array( &$this, 'add_metaboxes' ) );
            
            if(class_exists('MSD_Widget_Random_Testimonial')){
                add_action('widgets_init',array('MSD_Widget_Random_Testimonial','init'),10);
            }
			add_action('admin_head', array(&$this,'plugin_header'));
			add_action('admin_print_scripts', array(&$this,'add_admin_scripts') );
			add_action('admin_print_styles', array(&$this,'add_admin_styles') );
			// important: note the priority of 99, the js needs to be placed after tinymce loads
			add_action('admin_print_footer_scripts',array(&$this,'print_footer_scripts'),99);
			
			//Filters
			//add_filter( 'pre_get_posts', array(&$this,'custom_query') );
            add_shortcode('testimonial',array(&$this,'testimonial_shortcode_handler'));
            add_shortcode('testimonials',array(&$this,'testimonial_shortcode_handler'));
		}
		

        function register_taxonomy_testimonial_type(){
            
            $labels = array( 
                'name' => _x( 'Testimonial types', 'testimonial-types' ),
                'singular_name' => _x( 'Testimonial type', 'testimonial-types' ),
                'search_items' => _x( 'Search testimonial types', 'testimonial-types' ),
                'popular_items' => _x( 'Popular testimonial types', 'testimonial-types' ),
                'all_items' => _x( 'All testimonial types', 'testimonial-types' ),
                'parent_item' => _x( 'Parent testimonial type', 'testimonial-types' ),
                'parent_item_colon' => _x( 'Parent testimonial type:', 'testimonial-types' ),
                'edit_item' => _x( 'Edit testimonial type', 'testimonial-types' ),
                'update_item' => _x( 'Update testimonial type', 'testimonial-types' ),
                'add_new_item' => _x( 'Add new testimonial type', 'testimonial-types' ),
                'new_item_name' => _x( 'New testimonial type name', 'testimonial-types' ),
                'separate_items_with_commas' => _x( 'Separate testimonial types with commas', 'testimonial-types' ),
                'add_or_remove_items' => _x( 'Add or remove testimonial types', 'testimonial-types' ),
                'choose_from_most_used' => _x( 'Choose from the most used testimonial types', 'testimonial-types' ),
                'menu_name' => _x( 'Testimonial types', 'testimonial-types' ),
            );
        
            $args = array( 
                'labels' => $labels,
                'public' => true,
                'show_in_nav_menus' => true,
                'show_ui' => true,
                'show_tagcloud' => false,
                'hierarchical' => true, //we want a "category" style taxonomy, but may have to restrict selection via a dropdown or something.
        
                'rewrite' => array('slug'=>'testimonial-type','with_front'=>false),
                'query_var' => true
            );
        
            register_taxonomy( 'testimonial_type', array($this->cpt), $args );
        }

        
		function register_cpt_testimonial() {
		
		    $labels = array( 
		        'name' => _x( 'Testimonials', 'testimonial' ),
		        'singular_name' => _x( 'Testimonial', 'testimonial' ),
		        'add_new' => _x( 'Add New', 'testimonial' ),
		        'add_new_item' => _x( 'Add New Testimonial', 'testimonial' ),
		        'edit_item' => _x( 'Edit Testimonial', 'testimonial' ),
		        'new_item' => _x( 'New Testimonial', 'testimonial' ),
		        'view_item' => _x( 'View Testimonial', 'testimonial' ),
		        'search_items' => _x( 'Search Testimonial', 'testimonial' ),
		        'not_found' => _x( 'No testimonial found', 'testimonial' ),
		        'not_found_in_trash' => _x( 'No testimonial found in Trash', 'testimonial' ),
		        'parent_item_colon' => _x( 'Parent Testimonial:', 'testimonial' ),
		        'menu_name' => _x( 'Testimonial', 'testimonial' ),
		    );
		
		    $args = array( 
		        'labels' => $labels,
		        'hierarchical' => false,
		        'description' => 'Testimonial',
		        'supports' => array('title', 'author' ,'genesis-cpt-archives-settings'),
		        'taxonomies' => array('testimonial_type'),
		        'public' => true,
		        'show_ui' => true,
		        'show_in_menu' => true,
		        'menu_position' => 20,
		        
		        'show_in_nav_menus' => true,
		        'publicly_queryable' => true,
		        'exclude_from_search' => true,
		        'has_archive' => true,
		        'query_var' => true,
		        'can_export' => true,
		        'rewrite' => array('slug'=>'testimonial','with_front'=>false),
		        'capability_type' => 'post'
		    );
		
		    register_post_type( $this->cpt, $args );
        
		}
		
		function plugin_header() {
			global $post_type;
		}
		 
		function add_admin_scripts() {
			global $current_screen;
			if($current_screen->post_type == $this->cpt){
                wp_enqueue_script('jquery-ui-core');
                wp_enqueue_script('jquery-ui-datepicker');
                wp_enqueue_script('jquery-timepicker',plugin_dir_url(dirname(__FILE__)).'js/jquery.timepicker.min.js',array('jquery'));
                wp_enqueue_script('media-upload');
                wp_enqueue_script('thickbox');
			}
		}

        function add_admin_styles() {
            global $current_screen;
            if($current_screen->post_type == $this->cpt){
                wp_enqueue_style('thickbox');
                wp_enqueue_style('jquery-ui-style','http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/themes/smoothness/jquery-ui.min.css');
                wp_enqueue_style('custom_meta_css',plugin_dir_url(dirname(__FILE__)).'css/meta.css');
            }
        }   
			
		function print_footer_scripts()
		{
			global $current_screen;
			if($current_screen->post_type == $this->cpt){
				?><script type="text/javascript">
                    jQuery(function($){
                        $( ".datepicker" ).datepicker({
                        onSelect : function(dateText, inst)
                        {
                            var epoch = $.datepicker.formatDate('@', $(this).datepicker('getDate')) / 1000;
                            $('.datestamp').val(epoch);
                        }
                        });
                        $('.timepicker').timepicker({ 'scrollDefaultNow': true });
                        $("#postdivrich").after($("#_testimonial_info_metabox"));
                    });
                 </script><?php
			}
		}
		

		function custom_query( $query ) {
			if(!is_admin()){
				if($query->is_main_query() && $query->is_search){
					$searchterm = $query->query_vars['s'];
					// we have to remove the "s" parameter from the query, because it will prtestimonial the posts from being found
					$query->query_vars['s'] = "";
					
					if ($searchterm != "") {
						$query->set('meta_value',$searchterm);
						$query->set('meta_compare','LIKE');
					};
					$query->set( 'post_type', array('post','page',$this->cpt) );
				}
			}
		}	
        
        function testimonial_shortcode_handler($atts){
            extract( shortcode_atts( array(
                'rows' => 1,
                'columns' => 1,
                'link' => false,
                'length' => false,
                'slideshow' => false,
                'terms' => false,
            ), $atts ) );
            global $testimonial_info;
            $args = array(
                'post_type' => $this->cpt,
                'orderby' => rand
            );
            $args['posts_per_page'] = $slideshow?10:$rows * $columns;
            $terms = $terms?explode(',',$terms):$slideshow?array('featured'):false;
            if($terms){
                $args['tax_query'] = array(
                    array(
                        'taxonomy' => 'testimonial_type',
                        'field' => 'slug',
                        'terms' => $terms
                    ),
                );
            }
            
            
            $testimonials = get_posts($args);
            $testimonial_array = array();
            $ret = false;
            foreach($testimonials AS $testimonial){
                $testimonial_info->the_meta($testimonial->ID);
                $badge = has_term( 'law-enforcement', 'testimonial_type', $testimonial->ID )?' <i class="badge-icon"></i>':'';
                $quote = apply_filters('the_content',$testimonial_info->get_the_value('quote'));
                if($length){
                    $quote = self::msd_trim_quote($quote,$length,get_the_permalink($testimonial->ID));
                }
                $name = $testimonial_info->get_the_value('attribution')!=''?'<span class="name">'.$testimonial_info->get_the_value('attribution').',</span> ':'';
                $position = $testimonial_info->get_the_value('position')!=''?'<span class="position">'.$testimonial_info->get_the_value('position').',</span> ':'';
                $organization = $testimonial_info->get_the_value('organization')!=''?'<span class="organization">'.$testimonial_info->get_the_value('organization').'</span> ':'';
                $location = $testimonial_info->get_the_value('location')!=''?'<span class="location">'.$testimonial_info->get_the_value('location').'</span> ':'';
                $bootstrap = $slideshow?'':'col-md-'. 12/$columns .' col-xs-12 ';
                $testimonial_array[] .= '<div class="'.$bootstrap.'item-wrapper">
                <div class="quote">'.$quote.'</div>
                <div class="attribution">'.$name.$position.$organization.$location.$badge.'</div>
                </div>';
            }
            if($slideshow){
                foreach($testimonial_array AS $k=>$t){
                    $active = $k=='0'?' active':'';
                    $ret .= '<div class="item'.$active.'">'.$t.'</div>';
                }
                $controls = '
                <div class="testimonial-title skewit"><div class="unskewit">Testimonial</div></div>
  <div class="control-wrapper skewit"><div class="unskewit">
  <a class="left carousel-control" href="#testimonial-carousel" role="button" data-slide="prev">
    <span class="fa fa-chevron-left" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="right carousel-control" href="#testimonial-carousel" role="button" data-slide="next">
    <span class="fa fa-chevron-right" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
  </div></div>';
                $ret = sprintf('<div id="testimonial-carousel" class="carousel slide" data-ride="carousel">'.$controls.'<div class="carousel-inner" role="listbox">%s</div></div>',$ret);
            } else {
                $ret .= implode('',$testimonial_array);
            }
            if($link){
                $link_text = is_string($link)?$link:'Read More Testimonials';
                $ret .= '<div class="col-md-'. 12/$columns .' col-xs-12 link-wrapper"><a href="'.get_post_type_archive_link($this->cpt).'">'.$link_text.'</a></div>';
            }
            $ret = '<div class="msdlab_testimonial_gallery">'.$ret.'</div>';
            
            return $ret;
        } 

        function add_metaboxes(){
                global $post,$wpalchemy_media_access,$testimonial_info;
                $testimonial_info = new WPAlchemy_MetaBox(array
                    (
                        'id' => '_testimonial_info',
                        'title' => 'Testimonial Info',
                        'types' => array('testimonial'),
                        'context' => 'normal',
                        'priority' => 'high',
                        'template' => WP_PLUGIN_DIR.'/'.plugin_dir_path('msd-custom-cpt/msd-custom-cpt.php').'lib/template/testimonial-information.php',
                        'autosave' => TRUE,
                        'mode' => WPALCHEMY_MODE_EXTRACT, // defaults to WPALCHEMY_MODE_ARRAY
                        'prefix' => '_testimonial_' // defaults to NULL
                    ));
            }
            
            
    function msd_trim_quote($text, $length = 35) {
        $raw_excerpt = $text;
        if ( '' == $text ) {
            $text = get_the_content('');
        }
            $text = strip_shortcodes( $text );
            $text = preg_replace("/<img[^>]+\>/i", "", $text); 
            $text = apply_filters('the_content', $text);
            $text = str_replace(']]>', ']]&gt;', $text);
            $text = strip_tags($text);
            $excerpt_length = apply_filters('excerpt_length', $length);
            $words = preg_split("/[\n\r\t ]+/", $text, $excerpt_length + 1, PREG_SPLIT_NO_EMPTY);
            if ( count($words) > $excerpt_length ) {
                array_pop($words);
                $text = implode(' ', $words);
                $text = $text . ' <a href="'.get_post_type_archive_link( $this->cpt ).'">Read More ></a>';
            } else {
                $text = implode(' ', $words);
            }
    
        
        return apply_filters('wp_trim_excerpt', $text, $raw_excerpt);
        //return $text;
    }
  } //End Class
} //End if class exists statement

class MSD_Widget_Random_Testimonial extends WP_Widget {
    function __construct() {
        $widget_ops = array('classname' => 'widget_random_testimonial', 'description' => __('Displays a random testimonial.'));
        parent::__construct('widget_random_testimonial', __('Random Testimonial'), $widget_ops, $control_ops);
    }
    function widget( $args, $instance ) {
        $cpt = new MSDTestimonialCPT();
        extract($args);
        $title = apply_filters( 'widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
        $linktext = apply_filters( 'widget_title', empty($instance['linktext']) ? 'Read More' : $instance['linktext'], $instance, $this->id_base);
        echo $before_widget; 
        if ( !empty( $title ) ) { echo $before_title . $title . $after_title; } 
        print '<div class="wrap">';
        print $cpt->testimonial_shortcode_handler(array('link'=>$linktext,'length'=>30)); 
        print '
        <div class="clearfix"></div>
        </div>';
        echo $after_widget;
    }
    
    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['linktext'] = strip_tags($new_instance['linktext']);
        
        return $instance;
    }
    function form( $instance ) {
        $instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
        $title = strip_tags($instance['title']);
        
?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
        <p><label for="<?php echo $this->get_field_id('linktext'); ?>"><?php _e('Link Text:'); ?></label><input class="widefat" id="<?php echo $this->get_field_id('linktext'); ?>" name="<?php echo $this->get_field_name('linktext'); ?>" type="text" value="<?php echo esc_attr($linktext); ?>" /></p>
<?php
    }
    function init() {
        if ( !is_blog_installed() )
            return;
        register_widget('MSD_Widget_Random_Testimonial');
    }  
}