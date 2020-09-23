<?php

/*
  Template Name: page-vue
 */

get_header();

$is_logged_in = json_encode(is_user_logged_in());
?>

    <noscript>Slå venligst JavaScript til.</noscript>

    <div id="vue-div"></div>

<?php

get_footer();
