<?php

/*
  Template Name: page-api
 */

$arrangements = get_posts([
    'post_type' => 'arrangement',
    'order' => 'ASC'
]);

$arrangements_data = [];

foreach ($arrangements as $arrangement) {
    $arrangement_id = $arrangement->ID;
    $arrangement_name = get_field('arrangement_name', $arrangement_id);
    $arrangement_date = get_field('arrangement_date', $arrangement_id);
    $arrangements_data[] = ['id' => $arrangement_id, 'name' => $arrangement_name, 'date' => $arrangement_date];
}

header('Content-type:application/json;charset=utf-8');
echo json_encode($arrangements_data);
