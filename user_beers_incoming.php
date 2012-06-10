<?
$page = "user_beers_incoming";
include "header.php";

if(isset($_POST['task'])) { $task = $_POST['task']; } elseif(isset($_GET['task'])) { $task = $_GET['task']; } else { $task = "main"; }
if(isset($_POST['p'])) { $p = $_POST['p']; } elseif(isset($_GET['p'])) { $p = $_GET['p']; } else { $p = 1; }

//VARS
$user_beer = new se_beers();
$owner_beer = new se_beers();

// GET TOTAL beerS
$total_beers = $user_beer->beer_total_incoming($user->user_info[user_id]);

// MAKE beerS PAGES
$beers_per_page = 10;
$page_vars = make_page($total_beers, $beers_per_page, $p);

// GET beerS ARRAY
$beers = $user_beer->user_beer_list_incoming($user->user_info[user_id],$page_vars[0], $beers_per_page);

// ENSURE beerS ARE ENABLED
//if(beers_enabled() == 0) { header("Location: ".$url->url_create('profile', $user->user_info[user_username])); exit(); }

// ENSURE beerS ARE ENABLED FOR THIS USER
//if($user->level_info[level_beers_allow] == 0) { header("Location: ".$url->url_create('profile', $user->user_info[user_username])); exit(); }

// DISPLAY ERROR PAGE IF USER IS NOT ALLOWED TO beer
if( !$user->level_info['level_beers_allow'] )
{
  $page = "error";
  $smarty->assign('error_header', $beers[1]);
  $smarty->assign('error_message', $beers[4]);
  $smarty->assign('error_submit', $beers[3]);
  include "footer.php";
}

// CONFIRM beer - REMOVE NOTIFICATION
if($task == "confirm") {

$beer_owner_id = $user->user_info[user_id];
$beer = "action_sendbeer.gif";
$beer_query = $database->database_query("SELECT * FROM se_notifytypes WHERE notifytype_name = 'beer' ");
$beer_array = Array();
	while($item = $database->database_fetch_assoc($beer_query)) {
	$id = $item[notifytype_id];

$database->database_query("DELETE FROM se_notifys WHERE notify_user_id='$beer_owner_id' AND notify_notifytype_id='$id'");
	}
}

// ASSIGN VARIABLES AND INCLUDE FOOTER
$smarty->assign('beers', $beers);
$smarty->assign('total_beers', $total_beers);
$smarty->assign('p', $page_vars[1]);
$smarty->assign('maxpage', $page_vars[2]);
$smarty->assign('p_start', $page_vars[0]+1);
$smarty->assign('p_end', $page_vars[0]+count($beers));

include "footer.php";
?>