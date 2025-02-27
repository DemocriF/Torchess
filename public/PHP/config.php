<?php
// config.php

// Retrieve the environment variable; default to 'development' if not set
$environment = getenv('APPLICATION_ENV') ?: 'production';

// Load the appropriate configuration file based on the environment
if ($environment === 'production') {
  require_once 'config.prod.php';
} else {
  require_once 'config.dev.php';
}
?>
