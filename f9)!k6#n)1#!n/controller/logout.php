<?php

include('env.php');
session_start();
session_unset();
session_destroy();
header("Location: $app_url/login.php");
die();