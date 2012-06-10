<?
$page = "user_kiss_incoming";
include "header.php";

if(isset($_POST['task'])) { $task = $_POST['task']; } elseif(isset($_GET['task'])) { $task = $_GET['task']; } else { $task = "main"; }
if(isset($_POST['p'])) { $p = $_POST['p']; } elseif(isset($_GET['p'])) { $p = $_GET['p']; } else { $p = 1; }

//VARS
$user_kiss = new se_kiss();
$owner_kiss = new se_kiss();

// GET TOTAL kiss
$total_kiss = $user_kiss->kiss_total_incoming($user->user_info[user_id]);

// MAKE kiss PAGES
$kiss_per_page = 10;
$page_vars = make_page($total_kiss, $kiss_per_page, $p);

// GET kiss ARRAY
$kiss = $user_kiss->user_kiss_list_incoming($user->user_info[user_id],$page_vars[0], $kiss_per_page);

// ENSURE kiss ARE ENABLED
//if(kiss_enabled() == 0) { header("Location: ".$url->url_create('profile', $user->user_info[user_username])); exit(); }

// ENSURE kiss ARE ENABLED FOR THIS USER
//if($user->level_info[level_kiss_allow] == 0) { header("Location: ".$url->url_create('profile', $user->user_info[user_username])); exit(); }

// DISPLAY ERROR PAGE IF USER IS NOT ALLOWED TO kiss
if( !$user->level_info['level_kiss_allow'] )
{
  $page = "error";
  $smarty->assign('error_header', $kiss[1]);
  $smarty->assign('error_message', $kiss[4]);
  $smarty->assign('error_submit', $kiss[3]);
  include "footer.php";
}

// CONFIRM kiss - REMOVE NOTIFICATION
if($task == "confirm") {

$kiss_owner_id = $user->user_info[user_id];
$kiss = "action_sendkiss.gif";
$kiss_query = $database->database_query("SELECT * FROM se_notifytypes WHERE notifytype_name = 'kiss' ");
$kiss_array = Array();
	while($item = $database->database_fetch_assoc($kiss_query)) {
	$id = $item[notifytype_id];

$database->database_query("DELETE FROM se_notifys WHERE notify_user_id='$kiss_owner_id' AND notify_notifytype_id='$id'");
	}
}

// ASSIGN VARIABLES AND INCLUDE FOOTER
$smarty->assign('kiss', $kiss);
$smarty->assign('total_kiss', $total_kiss);
$smarty->assign('p', $page_vars[1]);
$smarty->assign('maxpage', $page_vars[2]);
$smarty->assign('p_start', $page_vars[0]+1);
$smarty->assign('p_end', $page_vars[0]+count($kiss));

include "footer.php";
?>