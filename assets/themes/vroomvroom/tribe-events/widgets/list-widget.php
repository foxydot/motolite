<?php
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}

$events_label_plural = tribe_get_event_label_plural();

$events = tribe_get_list_widget_events();

// Check if any event posts are found.
if ( $events ) : ?>

    <ol class="hfeed vcalendar">
        <?php
        // Setup the post data for each event.
        foreach ( $events as $event ) :
            $start = tribe_get_start_date($event->ID,false,'m/d');
            $end = tribe_get_end_date($event->ID,false,'m/d');
            $duration = $start == $end?$start:$start.' - '.$end;
            $venue = tribe_get_venue($event->ID);
            $venue = $venue!=''?'- '.$venue:'';
            $description = msdlab_get_excerpt($event->ID);
            ?>
            <li class="tribe-events-list-widget-events <?php tribe_events_event_classes() ?>">
                <div class="date-label skewit">
                    <div class="date unskewit">
                        <?php print tribe_get_start_date($event->ID,false,'m/d/y'); ?>
                    </div>
                </div>
                <div class="description">
                    <?php print $description; ?>
                </div>
                <div class="meta">
                    <span class="duration"><?php print $duration; ?></span>
                    <span class="venue"><?php print $venue; ?></span>
                </div>
                                <!-- Event Title -->
                <div class="event-title summary">
                    <a href="<?php echo esc_url( tribe_get_event_link($event->ID) ); ?>" rel="bookmark"><?php print apply_filters('post_title',$event->post_title); ?></a>
                </div>
            </li>
        <?php
        endforeach;
        ?>
    </ol><!-- .hfeed -->

    <div class="tribe-events-widget-link">
        <a href="<?php echo esc_url( tribe_get_events_link() ); ?>" rel="bookmark"><?php printf( __( 'More %s', 'tribe-events-calendar' ), $events_label_plural ); ?> <i class="fa fa-angle-right"></i></a>
    </div>

<?php
// No events were found.
else : ?>
    <p><?php printf( __( 'There are no upcoming %s at this time.', 'tribe-events-calendar' ), strtolower( $events_label_plural ) ); ?></p>
<?php
endif;
