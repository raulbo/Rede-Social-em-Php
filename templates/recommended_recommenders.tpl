{include file='header.tpl'}


<table class='tabs' cellpadding='0' cellspacing='0'>
<tr>
<td class='tab0'>&nbsp;</td>
<td class='tab2' NOWRAP><a href='user_recommended_settings.php'>{lang_print id=11140112}</a></td>
<td class='tab'>&nbsp;</td>
<td class='tab2' NOWRAP><a href='recommended_top_recommendees.php'>{lang_print id=11140110}</a></td>
<td class='tab'>&nbsp;</td>
<td class='tab2' NOWRAP><a href='recommended_top_recommenders.php'>{lang_print id=11140111}</a></td>
<td class='tab3'>&nbsp;</td>
</tr>
</table>

<img src='./images/icons/recommended_vote48.gif' border='0' class='icon_big'>
<div class='page_header'>{lang_print id=11140701} <a href="{$url->url_create('profile', $owner->user_info.user_username)}">{$owner->user_displayname}</a></div>
<div>{lang_print id=11140702}
{lang_print id=11140704} <a href="recommended_recommendees.php?user={$owner->user_info.user_username}">{$owner->user_displayname}{lang_print id=11140705}</a>
</div>
<br><br>

{if $total_entries == 0}

  <table cellpadding='0' cellspacing='0'>
  <tr>
  <td class='error'><img src='./images/error.gif' border='0' class='icon'>{lang_print id=11140703}</td>
  </tr>
  </table>

{else}

  {* DISPLAY PAGINATION MENU IF APPLICABLE *}
  {if $maxpage > 1}
    <div class='center'>
    {if $p != 1}<a href='recommended_recommenders.php?user={$owner->user_info.user_username}&p={math equation='p-1' p=$p}'>&#171; {lang_print id=11140706}</a>{else}<font class='disabled'>&#171; {lang_print id=11140706}</font>{/if}
    {if $p_start == $p_end}
      &nbsp;|&nbsp; {lang_print id=11140707} {$p_start} {lang_print id=11140708} {$total_entries} &nbsp;|&nbsp; 
    {else}
      &nbsp;|&nbsp; {lang_print id=11140709} {$p_start}-{$p_end} {lang_print id=11140708} {$total_entries} &nbsp;|&nbsp; 
    {/if}
    {if $p != $maxpage}<a href='recommended_recommenders.php?user={$owner->user_info.user_username}&p={math equation='p+1' p=$p}'>{lang_print id=11140710} &#187;</a>{else}<font class='disabled'>{lang_print id=11140710} &#187;</font>{/if}
    </div>
    <br>
  {/if}

  {foreach from=$recommended_recommenders item=recommended}
    <table cellpadding='0' cellspacing='0' class='recommended_entry'>
      <tr>
        <td class='recommended_photo'><a href='{$url->url_create('profile',$recommended->rc_user->user_username)}'><img src='{$recommended->rc_user->se_user->user_photo('./images/nophoto.gif')}' class='photo' width='{$misc->photo_size($recommended->rc_user->se_user->user_photo('./images/nophoto.gif'),'90','90','w')}' border='0'></a></td>
        <td class='recommended_infos'><a href='{$url->url_create('profile',$recommended->rc_user->user_username)}'><b>{$recommended->rc_user->user_displayname}</b></a>
          <br>{$datetime->cdate("`$setting.setting_dateformat`", $datetime->timezone("`$recommended->vote_date`", $global_timezone))}
          <div class='recommended_comment'>{$recommended->vote_comment|nl2br}</div>
        </tr>
      </tr>
    </table>
  {/foreach}
  
  {* DISPLAY PAGINATION MENU IF APPLICABLE *}
  {if $maxpage > 1}
    <div class='center'>
    {if $p != 1}<a href='recommended_recommenders.php?user={$owner->user_info.user_username}&p={math equation='p-1' p=$p}'>&#171; {lang_print id=11140706}</a>{else}<font class='disabled'>&#171; {lang_print id=11140706}</font>{/if}
    {if $p_start == $p_end}
      &nbsp;|&nbsp; {lang_print id=11140707} {$p_start} {lang_print id=11140708} {$total_entries} &nbsp;|&nbsp; 
    {else}
      &nbsp;|&nbsp; {lang_print id=11140709} {$p_start}-{$p_end} {lang_print id=11140708} {$total_entries} &nbsp;|&nbsp; 
    {/if}
    {if $p != $maxpage}<a href='recommended_recommenders.php?user={$owner->user_info.user_username}&p={math equation='p+1' p=$p}'>{lang_print id=11140710} &#187;</a>{else}<font class='disabled'>{lang_print id=11140710} &#187;</font>{/if}
    </div>
    <br>
  {/if}  
  
{/if}

{include file='footer.tpl'}
