<?php
/*
  Template Name: sse-server
 */

//date_default_timezone_set("America/New_York");
header("Cache-Control: no-cache");
header("Content-Type: text/event-stream");

$counter = rand(1, 10);
while (true) {
    // Every second, send a "ping" event.
    echo "event: ping\n";
    $curDate = date(DATE_ISO8601);
    echo 'data: {"time": "' . $curDate . '"}';
    echo "\n\n";

    // Send a simple message at random intervals.
    $counter--;
    if (!$counter) {
        echo 'data: This is a message at time ' . $curDate . "\n\n";
        $counter = rand(1, 10);
    }

    $ob_end_flush = ob_end_flush(); // [Only returns true the 1st time it's run]
    flush();

    echo 'data: ob_end_flush: ' . json_encode($ob_end_flush) . "\n\n";


    // Break the loop if the client aborted the connection (closed the page)
    if (connection_aborted())
        break;

    sleep(1);
}

