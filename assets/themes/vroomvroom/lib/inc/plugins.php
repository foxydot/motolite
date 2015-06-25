<?php
add_filter( 'gform_submit_button', 'msdlab_form_submit_button', 10, 2 );
function msdlab_form_submit_button($ret){
    $ret = sprintf('<span class="skewit button"><span class="unskewit">%s</span></span>',$ret);
    return $ret;
}
