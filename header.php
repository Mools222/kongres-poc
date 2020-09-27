<?php
//acf_form_head();
$stylesheet_directory_uri = get_stylesheet_directory_uri();
?>

<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <title>JCI Kongres</title>
    <link rel="icon" href="<?= $stylesheet_directory_uri ?>/assets/img/favicon.ico"/>

    <!-- PWA related elements -->
    <link rel="manifest" href="<?= $stylesheet_directory_uri ?>/manifest.php">
    <meta name="theme-color" content="#2b2247">
    <link rel="apple-touch-icon" sizes="180x180" href="<?= $stylesheet_directory_uri ?>/assets/img/apple-touch-icon.png">

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>