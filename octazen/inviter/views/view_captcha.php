<?php
/********************************************************************************
DO NOT EDIT THIS FILE!

Unified Inviter Component

You may not reprint or redistribute this code without permission from Octazen Solutions.

Copyright 2009 Octazen Solutions. All Rights Reserved.
WWW: http://www.octazen.com
********************************************************************************/

//////////////////////////////////////////////////////////////////////////
//Takes in parameter from $_POST
//	oz_state
//	oz_captcha_image
//		Url of the captcha image
//
//Submits the following
//	oz_state
//	oz_captcha_answer
//	oz_captcha_submit
//////////////////////////////////////////////////////////////////////////
if (!defined('_OZ_INVITER')) exit();
?>
<div class="oz_header">
  <table cellpadding="0" cellspacing="0" border="0" width="100%">
    <tr>
      <td align="left"><span id="oz_header_label">{{CAPTCHA_TITLE}}</span></td>
      <td align="right"><a href="#" onclick="ozStartAgain2();return false;">{{START_AGAIN}}</a></td>
    </tr>
  </table>
</div>
<div id="ozpanel_captcha">
  <form method="post" name="oz_captcha_form" onsubmit="return ozOnSubmit();">
<?php echo ozi_render_form_snippet(); ?>
    <input type="hidden" name="oz_state" value="<?php echo htmlentities($_REQUEST['oz_state']) ?>"/>
    <div align="center">
      <table  border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td align="center"><img src="<?php echo $_REQUEST['oz_captcha_image'] ?>" border="1"/></td>
        </tr>
        <tr>
          <td align="center"><?php
$c = isset($_REQUEST['oz_captcha_remaining_count']) ? $_REQUEST['oz_captcha_remaining_count'] : 0;
if ($c>0) echo oz_text('CAPTCHA_REMAINING').$c.'<br/>';
?>
            {{CAPTCHA_PLEASETYPE}}.
            <input type="text" name="oz_captcha_answer" value="" size="10" class="oz_field_input"/></td>
        </tr>
        <tr>
          <td align="center">&nbsp;</td>
        </tr>
        <tr>
          <td align="center"><input type="submit" name="ozbtn_captcha" value="{{SUBMIT}}" class="oz_field_button"/>
            &nbsp;
            <input type="submit" name="ozbtn_cancel_captcha" value="{{CANCEL}}" class="oz_field_button"/></td>
        </tr>
      </table>
    </div>
  </form>
</div>
<script type="text/javascript">
DomReady.domReady.add(function() {
	document.oz_captcha_form.oz_captcha_answer.focus();
});
ozNotifyViewChange('captcha');
</script>
