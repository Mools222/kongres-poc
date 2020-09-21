<?php
/*
  Template Name: page-sse
 */
?>

<h1>SSE</h1>

<ul id="list"></ul>

<script>
    window.addEventListener('load', function (event) {
        const evtSource = new EventSource("<?= get_site_url(); ?>/sse-server");

        const eventList = document.getElementById("list");

        evtSource.onmessage = function (event) {
            const newElement = document.createElement("li");

            newElement.innerHTML = "message: " + event.data;
            eventList.appendChild(newElement);
        };

        evtSource.addEventListener("ping", function (event) {
            const newElement = document.createElement("li");
            const time = JSON.parse(event.data).time;
            newElement.innerHTML = "ping at " + time;
            eventList.appendChild(newElement);
        });
    });
</script>