<?php

/*
  Template Name: page-composer
 */

//require __DIR__ . '/vendor/autoload.php';
require 'vendor/autoload.php';

use Carbon\Carbon;

get_header();
?>

    <h1>Composer works:</h1>

    <p><?php printf("Nu: %s", Carbon::now()); ?></p>

    <p><?= date('Ymd', 1600255133) ?></p>

<?php
get_footer();
