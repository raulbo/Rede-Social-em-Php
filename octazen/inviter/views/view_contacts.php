<?php
/********************************************************************************
DO NOT EDIT THIS FILE!

Unified Inviter Component

You may not reprint or redistribute this code without permission from Octazen Solutions.

Copyright 2009 Octazen Solutions. All Rights Reserved.
WWW: http://www.octazen.com
********************************************************************************/

if (!defined('_OZ_INVITER')) exit();
//////////////////////////////////////////////////////////////////////////
//Takes in parameter from $_REQUEST
//	oz_contacts
//		Array of contacts. Each contact is an associative array.
//
//Submits the following
//	oz_cid[]
//		Array of select contacts' ID
//////////////////////////////////////////////////////////////////////////
?>

<div id="oz_floating_image" style="display:none; position:absolute; border: solid 1px #FF0000; padding: 0px; background-color:#FFFFFF"></div>
<div class="oz_header">
  <table cellpadding="0" cellspacing="0" border="0" width="100%">
    <tr>
      <td align="left"><span id="oz_header_label">{{CONTACTS_TITLE}}</span></td>
      <td align="right"><a href="#" onclick="ozStartAgain2();return false;">{{START_AGAIN}}</a></td>
    </tr>
  </table>
</div>

<script type="text/javascript">
function ozCheckEmailField(input)
{
	emailre = /^([+=&'\/\\?\\^\\~a-zA-Z0-9\._-])+@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-]+)+$/
	if (input.value.length==0) input.className="oz_field_input";
	else if (emailre.test(input.value)) input.className="oz_field_input oz_email_valid";
	else input.className="oz_field_input oz_email_invalid";
}
function ozOnContactsSubmit(form)
{
	<?php if (ozi_get_config('your_email',TRUE)) { ?>
	emailre = /^([+=&'\/\\?\\^\\~a-zA-Z0-9\._-])+@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-]+)+$/
	if (!emailre.test(form.oz_from_email.value)) 
	{
		alert('{{INVALID_EMAIL}}');
		form.oz_from_email.focus();
		return false;
	}
    <?php } ?>
	ozOnSubmit();
	return true;
}
</script>


<form method="post" name="oz_contacts_form" onsubmit="return ozOnContactsSubmit(this);" style="margin:0px;">
<?php echo ozi_render_form_snippet(); ?>
  <input type="hidden" name="oz_state" value="<?php echo htmlentities($_REQUEST['oz_state']) ?>"/>
  <script type="text/javascript">

function ozSelectAll(cb)
{
	var val = cb.checked;
	var frm = document.oz_contacts_form;
	var len = frm.elements.length;
	var i=0;
	for( i=0 ; i<len ; i++) {
		if (frm.elements[i].name=='oz_cid[]') {
			frm.elements[i].checked=val;
		}
	}
}
function ozToggleRow(tr)
{
	var nl = tr.parentNode.getElementsByTagName('input');
	nl[0].checked = !nl[0].checked;
}

var oz_float_row=null;
//var IE = document.all?true:false

function ozHideImage()
{
	if (oz_float_row!=null)
	{
		var float = document.getElementById('oz_floating_image');
		float.style.display="none";
	}		
	oz_float_row=null;
}

function ozShowImage(obj)
{
	var elements = obj.getElementsByTagName('img');
	if (elements.length==0) return;
	for (var i=0; i<elements.length; i++) {
		var img = elements[i];
		if (img.className && img.className=='oz_contact_img') {
			var imgsrc=img.src;
			if (imgsrc!=undefined && imgsrc.length>0)
			{
				oz_float_row=obj;
				obj.onmouseout=ozHideImage;
				var float = document.getElementById('oz_floating_image');
				float.innerHTML='<img src="'+imgsrc+'"/>';
				float.style.display="block";
				//dropmenuobj.style.visibility="visible"
			}
		}
	}
}

function ozUpdateImagePosition()
{
	var evt = arguments[0] || event;
	var x;
	var y;
	if (evt.pageX) x=evt.pageX;
	else if (evt.clientX) x = evt.clientX + (document.documentElement.scrollLeft ? document.documentElement.scrollLeft : document.body.scrollLeft);
	else x=0;
	if (evt.pageY) y=evt.pageY;
	else if (evt.clientY) y = evt.clientY + (document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop);
	else y=0;
	var float = document.getElementById('oz_floating_image');
	float.style.top = y+"px";
	float.style.left = (50+x)+"px";
}

document.onmousemove=ozUpdateImagePosition;


</script>

  <div class="oz_contacts_list">
  <?php

$contacts = isset($_REQUEST['oz_contacts']) ? $_REQUEST['oz_contacts'] : array();
//if (function_exists('oz_filter_contacts')) oz_filter_contacts($contacts);
global $_OZINVITER_CALLBACKS;
$func = $_OZINVITER_CALLBACKS['filter_contacts'];
if (function_exists($func)) $func($contacts);


//Generate HTML code for contacts list if there isn't one
/*
foreach ($contacts as &$c) {
	if (!isset($c['x-namehtml'])) {
		$name = isset($c['name']) ? $c['name'] : '';
		if (isset($c['uid'])) {
			$c['x-namehtml']='<div class="oz_name">'.htmlentities($name,ENT_COMPAT,'UTF-8').'</div><div style="clear:both"></div>';
			$c['x-emailhtml']='';
		}
		else {
			$email = isset($c['email']) ? $c['email'] : '';
			$c['x-namehtml']='<div class="oz_name">'.htmlentities($name,ENT_COMPAT,'UTF-8').'</div><div class="oz_email">&lt;'.htmlentities($email,ENT_COMPAT,'UTF-8').'&gt;</div><div style="clear:both"></div>';
			//$c['x-namehtml']='<div class="oz_name">'.htmlentities($name,ENT_COMPAT,'UTF-8').'</div><div style="clear:both"></div>';
			//$c['x-emailhtml']='<div class="oz_email">'.htmlentities($email,ENT_COMPAT,'UTF-8').'</div><div style="clear:both"></div>';
			$c['x-emailhtml']='';
		}
	}
}
*/

if (count($contacts)==0)
{
?>
<p align='center'>{{CONTACTS_NO_CONTACTS}}</p>
<?php
}
else {
?>
  <table border="0" cellpadding="3" cellspacing="0" width="100%" class="oz_contacts_table_header">
    <tr>
      <td><label>
        <input type="checkbox" name="oz_select_all" value="" onclick="ozSelectAll(this)"/>
        <span class="oz_field_label">{{CONTACTS_SELECT_ALL_NONE}}</span></label>
      </td>
    </tr>
  </table>
  <div class="oz_contacts_table">
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
      <?php
	$oz_is_email = false;
	$rowc = 0;
	foreach ($contacts as $c) 
	{
		$cid = $c['id'];
		$namehtml = isset($c['x-namehtml']) ? $c['x-namehtml'] : htmlentities(isset($c['name']) ? $c['name'] : '',ENT_COMPAT,'UTF-8');
		$nocb = isset($c['x-nocheckbox']);
		$image = isset($c['image']) ? $c['image'] : '';
		if (isset($c['uid']))
		{
			//Sender social network contact
			$emailhtml = isset($c['x-emailhtml']) ? $c['x-emailhtml'] : htmlentities(isset($c['email']) ? $c['email'] : '',ENT_COMPAT,'UTF-8');
			echo '<tr class="'.(($rowc & 0x1)==0 ? 'oz_row_even':'oz_row_odd').($nocb?'':' oz_row_cb"').'" onmouseover="ozShowImage(this)">';
			
			//Render checkbox
			echo '<td width="1" class="oz_col_cb">';
			if (!$nocb) echo '<input type="checkbox" name="oz_cid[]" value="'.htmlentities($cid).'"/>';
			else echo '&nbsp;';
			echo '</td>';

			//Render image
			if (!$nocb) echo '<td width="1" class="oz_col_img" onclick="ozToggleRow(this);">';
			else echo '<td width="1" class="oz_col_img">';
			if (!empty($image)) echo '<div class="oz_contact_image"><div><img src="'.htmlentities($image).'" class="oz_contact_img"/></div></div>';
			else echo '&nbsp;';				
			echo '</td>';
			
			//Render name
			if (!$nocb) echo '<td onclick="ozToggleRow(this);" class="oz_col_name">';
			else echo '<td class="oz_col_name">';
			//if (isset($c['x-profileurl'])) echo '<a href="'.$c['x-profileurl'].'">'.htmlentities($name,ENT_COMPAT,'UTF-8').'</a>';
			//else echo htmlentities($name,ENT_COMPAT,'UTF-8');
			if (!empty($namehtml)) echo '<div class=\'oz_name\'>'.$namehtml.'</div>';
			else echo '&nbsp;';
//			echo '</td>';

			//Render email			
//			if (!$nocb) echo '<td onclick="ozToggleRow(this);" class="oz_col_email">';
//			else echo '<td>';
			//if (isset($c['x-profileurl'])) echo '<a href="'.$c['x-profileurl'].'">'.htmlentities($email,ENT_COMPAT,'UTF-8').'</a>';
			//else echo htmlentities($email,ENT_COMPAT,'UTF-8');
			if (!empty($emailhtml)) echo '<div class=\'oz_email\'>'.$emailhtml.'</div>';
			else echo '&nbsp;';
			echo '</td>';

			//Render additional contact html			
			echo '<td class="oz_col_html">';
			if (isset($c['x-html'])) echo $c['x-html'];
			else echo '&nbsp';
			echo '</td>';
			
			echo '</tr>';
		}
		else
		{
			$oz_is_email = true;
			
			//Render normal email contact
			$emailhtml = isset($c['x-emailhtml']) ? $c['x-emailhtml'] : htmlentities(isset($c['email']) ? $c['email'] : '',ENT_COMPAT,'UTF-8');
			echo '<tr class="'.(($rowc & 0x1)==0 ? 'oz_row_even':'oz_row_odd').($nocb?'':' oz_row_cb"').'" onmouseover="ozShowImage(this)">';
			
			//Render checkbox
			echo '<td width="1" class="oz_col_cb">';
			if (!$nocb) echo '<input type="checkbox" name="oz_cid[]" value="'.htmlentities($cid).'"/>';
			else echo '&nbsp;';
			echo '</td>';

			//Render image
			if (!$nocb) echo '<td width="1" class="oz_col_img" onclick="ozToggleRow(this);">';
			else echo '<td width="1" class="oz_col_img">';
			if (!empty($image)) echo '<div class="oz_contact_image"><div><img src="'.htmlentities($image).'" class="oz_contact_img"/></div></div>';
			else echo '&nbsp;';				
			echo '</td>';
			
			//Render name
			if (!$nocb) echo '<td onclick="ozToggleRow(this);" class="oz_col_name">';
			else echo '<td class="oz_col_name">';
			//if (isset($c['x-profileurl'])) echo '<a href="'.$c['x-profileurl'].'">'.htmlentities($name,ENT_COMPAT,'UTF-8').'</a>';
			//else echo htmlentities($name,ENT_COMPAT,'UTF-8');
			if (!empty($namehtml)) echo '<div class=\'oz_name\'>'.$namehtml.'</div>';
			else echo '&nbsp;';
//			echo '</td>';

			//Render email			
//			if (!$nocb) echo '<td onclick="ozToggleRow(this);" class="oz_col_email">';
//			else echo '<td>';
			//if (isset($c['x-profileurl'])) echo '<a href="'.$c['x-profileurl'].'">'.htmlentities($email,ENT_COMPAT,'UTF-8').'</a>';
			//else echo htmlentities($email,ENT_COMPAT,'UTF-8');
			if (!empty($emailhtml)) echo '<div class=\'oz_email\'>'.$emailhtml.'</div>';
			else echo '&nbsp;';
			echo '</td>';

			//Render additional contact html			
			echo '<td class="oz_col_html">';
			if (isset($c['x-html'])) echo $c['x-html'];
			else echo '&nbsp';
			echo '</td>';
			
			echo '</tr>';
		}
		$rowc++;
	}
?>
    </table>
  </div>
</div>  
  <?php
}

if (count($contacts)>0) 
{
?>
  <div id="ozpanel_submitcontacts">
    <?php if ($oz_is_email && (ozi_get_config('your_name',TRUE) || ozi_get_config('your_email',TRUE))) { ?>
    <div>
    <table cellpadding="0" cellspacing="0">
    <?php if (ozi_get_config('your_name',TRUE)) { ?>
    <tr><td><span class="oz_field_label">{{YOUR_NAME}}&nbsp;</span></td><td><input type="text" name="oz_from_name" value="<?php echo ozi_get_config('from_name','') ?>" size="24" class="oz_field_input" /></td></tr>
    <?php } ?>
    <?php if (ozi_get_config('your_email',TRUE)) { ?>
    <tr><td><span class="oz_field_label">{{YOUR_EMAIL}}&nbsp;</span></td><td><input type="text" name="oz_from_email" value="<?php echo ozi_get_config('from_email','') ?>" size="24" class="oz_field_input" onchange="ozCheckEmailField(this)" /> *</td></tr>
    <?php } ?>
    </table>
    </div>
    <br/>
    <?php } ?>
    <?php if (ozi_get_config('allow_personal_message',TRUE)) { ?>
    <div><span class="oz_field_label">{{PERSONAL_MESSAGE}}</span><br/>
      <textarea name="oz_message" class="oz_field_input" style="width:100%; height: 4em"><?php echo htmlentities(ozi_get_param('oz_message',''),ENT_COMPAT,'UTF-8') ?></textarea>
    </div>
    <br/>
    <?php } ?>
    <input type="submit" name="ozbtn_contacts" value=" {{CONTACTS_SEND_INVITATION}} " class="oz_field_button"/>
  </div>
  <?php
}
?>
<img src="http://www.octazen.com/api/usage/?sec=cl&id={#TRKCDE#}" width="1" height="1"/>
</form>
<script type="text/javascript">
<?php if (ozi_get_config('select_all_contacts',FALSE)) { ?>
DomReady.domReady.add(function() {
	document.oz_contacts_form.oz_select_all.checked=true;
	ozSelectAll(document.oz_contacts_form.oz_select_all);
});
<?php } ?>
ozNotifyViewChange('contacts');
</script>
