<?php
/*
  Template Name: page-cron
 */

$text = $_GET['text'];
//$time = $_GET['time'];

//wp_schedule_single_event($time, 'my_new_event', array($text));

//if (wp_next_scheduled('my_new_event', array($text))) {
//    wp_clear_scheduled_hook('my_new_event');
//}

wp_schedule_single_event(time() + 10, 'my_new_event', array($text . current_time('mysql'))); // Shows that multiple events can be added
//wp_schedule_single_event(time() + 10, 'my_new_event', array($text));
