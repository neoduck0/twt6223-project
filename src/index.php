<?php

declare(strict_types=1);

require_once "inc/helpers.php";

if (is_logged_in() === false) {
    header("Location: landing.php");
    exit();
}
?>
