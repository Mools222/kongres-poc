<?php
header('Content-Type: application/json');

$matches = [];
preg_match('/(.+)wp-content/', $_SERVER['PHP_SELF'], $matches);
$base_path = $matches[1];

$manifest = array (
    'name' => 'JCI Kongres App',
    'short_name' => 'JCI Kongres',
    'icons' =>
        array (
            0 =>
                array (
                    'src' => 'assets/img/android-chrome-192x192-maskable.png',
                    'sizes' => '192x192',
                    'type' => 'image/png',
                    'purpose' => 'any maskable',
                ),
            1 =>
                array (
                    'src' => 'assets/img/android-chrome-512x512.png',
                    'sizes' => '512x512',
                    'type' => 'image/png',
                ),
            2 =>
                array (
                    'src' => 'assets/img/favicon.ico',
                    'sizes' => '48x48',
                    'type' => 'image/x-icon',
                ),
        ),
    'start_url' => $base_path,
    'theme_color' => '#2b2247',
    'background_color' => '#448aff',
    'display' => 'standalone',
);

echo json_encode($manifest);