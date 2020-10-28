<?php
/*
  Template Name: page-seed
 */

//get_header();

set_time_limit(0);

include_once 'DatabaseSeeder.php';

$arrangement_id = 10;
$database_seeder = new DatabaseSeeder();
//$user_ids = $database_seeder->seed_users_randomly(5);
//$database_seeder->seed_events_randomly($arrangement_id, 5000, $user_ids);
?>

<?php
//get_footer();