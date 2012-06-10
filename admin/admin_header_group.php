<?php

/* $Id: admin_header_group.php 10 2009-01-11 06:03:42Z john $ */

// ENSURE THIS IS BEING INCLUDED IN AN SE SCRIPT
defined('SE_PAGE') or exit();

// INCLUDE GROUPS CLASS FILE
include "../include/class_group.php";

// INCLUDE GROUPS FUNCTION FILE
include "../include/functions_group.php";


// SET USER DELETION HOOK
SE_Hook::register("se_user_delete", 'deleteuser_group');

SE_Hook::register("se_site_statistics", 'site_statistics_group');

?>