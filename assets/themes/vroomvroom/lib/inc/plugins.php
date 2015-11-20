<?php
//add_filter( 'gform_submit_button', 'msdlab_form_submit_button', 10, 2 );
function msdlab_form_submit_button($ret){
    $ret = str_replace(' button', ' ', $ret);
    $ret = sprintf('<span class="skewit button"><span class="unskewit">%s</span></span>',$ret);
    return $ret;
}

add_action('tribe_events_before_template','msdlab_upcoming_events_list_for_calendar_page');

function msdlab_upcoming_events_list_for_calendar_page(){
    print msdlab_upcoming_events_list();
}

add_shortcode('upcoming_events', 'msdlab_upcoming_events_list');
function msdlab_upcoming_events_list(){
    $events_label_plural = tribe_get_event_label_plural();

//$posts = tribe_get_list_widget_events();
$events= tribe_get_events(
            apply_filters(
                'tribe_events_list_args', array(
                    'eventDisplay'   => 'list',
                    'posts_per_page' => 3
                )
            )
        );

// Check if any event posts are found.
if ( $events ) :
    $ret = '
    <div class="tribe-events-list">
    <ol class="hfeed vcalendar row">
    ';
        // Setup the post data for each event.
        foreach ( $events as $event ) :
            $start = tribe_get_start_date($event->ID,false,'m/d');
            $end = tribe_get_end_date($event->ID,false,'m/d');
            $duration = $start == $end?$start:$start.' - '.$end;
            $venue = tribe_get_venue($event->ID);
            $venue = $venue!=''?'- '.$venue:'';
            $description = msdlab_get_excerpt($event->ID);
            $ret .= '
            <li class="tribe-events-list-widget-events col-md-4 col-sm-12 '.tribe_events_event_classes($post->ID,false).'">
                <div class="date-label skewit">
                    <div class="date unskewit">
                        '.tribe_get_start_date($event->ID,false,'m/d/y').'
                    </div>
                </div>
                <div class="description">
                    '.$description.'
                </div>
                <div class="meta">
                    <span class="duration">'.$duration.'</span>
                    <span class="venue">'.$venue.'</span>
                </div>
                <div class="event-title summary">
                    <a href="'.esc_url( tribe_get_event_link($event->ID) ).'" rel="bookmark">'.apply_filters('post_title',$event->post_title).'</a>
                </div>
            </li>';
        endforeach;
        $ret .= '
    </ol><!-- .hfeed -->
</div>
';
// No events were found.
else : 
    $ret = '<p>'.sprintf( __( 'There are no upcoming %s at this time.', 'tribe-events-calendar' ), strtolower( $events_label_plural ) ).'</p>';
endif;
return $ret;
}


add_filter('rtmedia_no_media_found_message_filter','msdlab_better_no_media_message');
function msdlab_better_no_media_message($message){
    $message = 'There are no photos or videos available in this section.';
    return $message;
}

add_filter('filter_allowed_sorting_columns','msdlab_sort_by_title_allowed');
function msdlab_sort_by_title_allowed($allowed){
    $allowed[] = 'media_title';
    return $allowed;
}
