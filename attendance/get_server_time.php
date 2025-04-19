<?php
// Set the correct timezone
date_default_timezone_set('Asia/Manila');

// Set the content type to plain text
header('Content-Type: text/plain');

// Get and return the current server time
echo date('H:i:s');
?> 