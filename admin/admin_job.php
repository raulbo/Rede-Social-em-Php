<?php

/* $Id: admin_job.php 7 2009-01-11 06:01:49Z john $ */

$page = "admin_job";
include "admin_header.php";

if(isset($_POST['task'])) { $task = $_POST['task']; } elseif(isset($_GET['task'])) { $task = $_GET['task']; } else { $task = "main"; }


// SET RESULT VARIABLE
$result = 0;



// SAVE CHANGES
if($task == "dosave")
{
  $setting['setting_permission_job'] = $_POST['setting_permission_job'];

  // SAVE CHANGES
  $sql = "UPDATE se_settings SET setting_permission_job='{$setting[setting_permission_job]}'";
  $resource = $database->database_query($sql) or die($database->database_error()." <b>SQL was: </b>$sql");

  $result = 1;
}



// GET TABS AND FIELDS
$field = new se_field("job");
$field->cat_list();
$cat_array = $field->cats;



// ASSIGN VARIABLES AND SHOW GENERAL SETTINGS PAGE
$smarty->assign('result', $result);
$smarty->assign('cats', $cat_array);
include "admin_footer.php";
?>