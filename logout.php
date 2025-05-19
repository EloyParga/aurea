<?php
session_start();
session_unset();
session_destroy();
header("Location: /aurea/index.php");
exit;