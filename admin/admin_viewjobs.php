<?php

/* $Id: admin_viewjobs.php 7 2009-01-11 06:01:49Z john $ */

$page = "admin_viewjobs";
include "admin_header.php";

if(isset($_POST['s'])) { $s = $_POST['s']; } elseif(isset($_GET['s'])) { $s = $_GET['s']; } else { $s = "id"; }
if(isset($_POST['p'])) { $p = $_POST['p']; } elseif(isset($_GET['p'])) { $p = $_GET['p']; } else { $p = 1; }
if(isset($_POST['f_title'])) { $f_title = $_POST['f_title']; } elseif(isset($_GET['f_title'])) { $f_title = $_GET['f_title']; } else { $f_title = ""; }
if(isset($_POST['f_owner'])) { $f_owner = $_POST['f_owner']; } elseif(isset($_GET['f_owner'])) { $f_owner = $_GET['f_owner']; } else { $f_owner = ""; }
if(isset($_POST['task'])) { $task = $_POST['task']; } elseif(isset($_GET['task'])) { $task = $_GET['task']; } else { $task = "main"; }
if(isset($_POST['job_id'])) { $job_id = $_POST['job_id']; } elseif(isset($_GET['job_id'])) { $job_id = $_GET['job_id']; } else { $job_id = 0; }
if(isset($_POST['delete_jobs'])) { $delete_jobs = $_POST['delete_jobs']; } elseif(isset($_GET['delete_jobs'])) { $delete_jobs = $_GET['delete_jobs']; } else { $search = NULL; }

// VALIDATE job ENTRY ID OR SET TASK TO MAIN
if($task == "confirm" OR $task == "deleteentry") {
  if($database->database_num_rows($database->database_query("SELECT job_id FROM se_jobs WHERE job_id='$job_id'")) != 1) { $task = "main"; }
}


// CREATE job OBJECT
$entries_per_page = 100;
$job = new se_job();


// DELETE SINGLE ENTRY
if($task == "deleteentry") {
  if($database->database_num_rows($database->database_query("SELECT job_id FROM se_jobs WHERE job_id='$job_id'")) == 1) {
    $job->job_delete($job_id);
  }
}







// SET job ENTRY SORT-BY VARIABLES FOR HEADING LINKS
$i = "id";   // job_ID
$t = "t";    // job_TITLE
$o = "o";    // OWNER OF ENTRY
$v = "v";    // VIEWS OF ENTRY
$d = "d";    // DATE OF ENTRY

// SET SORT VARIABLE FOR DATABASE QUERY
if($s == "i") {
  $sort = "se_jobs.job_id";
  $i = "id";
} elseif($s == "id") {
  $sort = "se_jobs.job_id DESC";
  $i = "i";
} elseif($s == "t") {
  $sort = "se_jobs.job_title";
  $t = "td";
} elseif($s == "td") {
  $sort = "se_jobs.job_title DESC";
  $t = "t";
} elseif($s == "o") {
  $sort = "se_users.user_username";
  $o = "od";
} elseif($s == "od") {
  $sort = "se_users.user_username DESC";
  $o = "o";
} elseif($s == "v") {
  $sort = "se_jobs.job_views";
  $v = "vd";
} elseif($s == "vd") {
  $sort = "se_jobs.job_views DESC";
  $v = "v";
} elseif($s == "d") {
  $sort = "se_jobs.job_date";
  $d = "dd";
} elseif($s == "dd") {
  $sort = "se_jobs.job_date DESC";
  $d = "d";
} else {
  $sort = "se_jobs.job_id DESC";
  $i = "i";
}




// ADD CRITERIA FOR FILTER
$where = "";
if($f_owner != "") { $where .= "se_users.user_username LIKE '%$f_owner%'"; }
if($f_owner != "" & $f_title != "") { $where .= " AND"; }
if($f_title != "") { $where .= " se_jobs.job_title LIKE '%$f_title%'"; }
if($where != "") { $where = "(".$where.")"; }


// DELETE NECESSARY ENTRIES
if( $task=="deleteentries" && !empty($delete_jobs) )
{
  $job->job_delete($delete_jobs);
}


// GET TOTAL ENTRIES/MAKE ENTRY PAGES/GET ENTRY ARRAY
$total_jobs = $job->job_total($where);

$page_vars = make_page($total_jobs, $entries_per_page, $p);
$page_array = Array();
for($x=0;$x<=$page_vars[2]-1;$x++) {
  if($x+1 == $page_vars[1]) { $link = "1"; } else { $link = "0"; }
  $page_array[$x] = Array('page' => $x+1,
			  'link' => $link);
}

$jobs = $job->job_list($page_vars[0], $entries_per_page, $sort, $where);


// ASSIGN VARIABLES AND SHOW VIEW ENTRIES PAGE
$smarty->assign('total_jobs', $total_jobs);
$smarty->assign_by_ref('jobs', $jobs);

$smarty->assign('pages', $page_array);
$smarty->assign('f_title', $f_title);
$smarty->assign('f_owner', $f_owner);
$smarty->assign('i', $i);
$smarty->assign('t', $t);
$smarty->assign('o', $o);
$smarty->assign('v', $v);
$smarty->assign('d', $d);
$smarty->assign('p', $page_vars[1]);
$smarty->assign('s', $s);

include "admin_footer.php";
?>