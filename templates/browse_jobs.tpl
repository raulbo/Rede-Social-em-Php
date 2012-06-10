{include file='header.tpl'}

{* $Id: browse_jobs.tpl 7 2009-01-11 06:01:49Z john $ *}

<div class='page_header'>
  {if empty($jobcat)}
    {lang_print id=6400124}
  {else}
    <a href='browse_jobs.php'>{lang_print id=6400124}</a> >
    {if empty($jobsubcat)}
      {lang_print id=$jobcat.jobcat_title}
    {else}
      <a href='browse_jobs.php?v={$v}&s={$s}&jobcat_id={$jobcat.jobcat_id}'>{lang_print id=$jobcat.jobcat_title}</a> >
      {lang_print id=$jobsubcat.jobcat_title}
    {/if}
  {/if}
</div>


<form action='browse_jobs.php' method='post' name="seBrowseJobs">
<input type='hidden' name='task' value='dosearch' />
<input type='hidden' name='jobcat_id' value='{$jobcat_id|default:0}' />
<input type='hidden' name='p' value='{$p|default:1}' />

<table cellpadding='0' cellspacing='0' width='100%' style='margin-top: 10px;'>
<tr>
<td style='width: 200px; vertical-align: top;'>
  
  <div style='padding: 10px; background: #F2F2F2; border: 1px solid #BBBBBB; font-weight: bold;'>
      
    <table cellpadding='0' cellspacing='0' width='100%'>
      <tr>
        <td style='text-align: left; padding-top: 5px;' nowrap='nowrap'>
          <input type="text" name="job_search" value="{$job_search}" class="text" style="width: 120px;">&nbsp;
        </td>
        <td style='text-align: left; padding-top: 5px;'>
          <input type="submit" value="{lang_print id=646}" class="button" />
        </td>
      </tr>
    </table>

    <table cellpadding='0' cellspacing='0' width='100%' style='margin-top: 10px;'>
      <tr>
        <td align='right'>{lang_print id=6400125}&nbsp;</td>
        <td>
          <select class='job_small' name='v' onchange="document.seBrowseJobs.submit();">
            <option value='0'{if $v == "0"} SELECTED{/if}>{lang_print id=6400127}</option>
            {if $user->user_exists}<option value='1'{if $v == "1"} SELECTED{/if}>{lang_print id=6400128}</option>{/if}
          </select>
        </td>
      </tr>
      <tr>
        <td align='right'>{lang_print id=6400126}&nbsp;</td>
        <td>
          <select class='job_small' name='s' onchange="document.seBrowseJobs.submit();">
            <option value='job_date DESC'{if $s == "job_date DESC"} SELECTED{/if}>{lang_print id=6400129}</option>
            <option value='job_dateupdated DESC'{if $s == "job_dateupdated DESC"} SELECTED{/if}>{lang_print id=6400130}</option>
            <option value='job_views DESC'{if $s == "job_views DESC"} SELECTED{/if}>{lang_print id=6400131}</option>
            <option value='total_comments DESC'{if $s == "total_comments DESC"} SELECTED{/if}>{lang_print id=6400132}</option>
          </select>
        </td>
      </tr>
    </table>

  </div>

  {* CATEGORY JAVASCRIPT *}
  {literal}
  <script type="text/javascript">
  <!-- 

  // ADD ABILITY TO MINIMIZE/MAXIMIZE CATS
  var cat_minimized = new Hash.Cookie('cat_cookie', {duration: 3600});
  var cat_list = new Hash();
  //-->
  </script>
  {/literal}
  
  
  <div style='margin-top: 10px; padding: 5px; background: #F2F2F2; border: 1px solid #BBBBBB; margin: 10px 0px 10px 0px; font-weight: bold;'>
    
    <div style='padding: 5px 8px 5px 8px; border: 1px solid #DDDDDD; background: #FFFFFF;'>
      <a href='browse_jobs.php?s={$s}&v={$v}'>{lang_print id=6400133}</a>
    </div>
    
    {section name=cat_loop loop=$cats}
      
      {* CATEGORY JAVASCRIPT *}
      <script type="text/javascript">
        <!-- 
        cat_list.set({$cats[cat_loop].cat_id}, {ldelim}{rdelim});
        //-->
      </script>
      
      <div style='padding: 5px 8px 5px 5px; border: 1px solid #DDDDDD; border-top: none; background: #FFFFFF;'>
        <img id='icon_{$cats[cat_loop].cat_id}' src='./images/icons/{if $cats[cat_loop].subcats|@count > 0 && $cats[cat_loop].subcats != ""}plus16{else}minus16_disabled{/if}.gif' {if $cats[cat_loop].subcats|@count > 0 && $cats[cat_loop].subcats != ""}style='cursor: pointer;' onClick="if($('subcats_{$cats[cat_loop].cat_id}').style.display == 'none') {literal}{{/literal} $('subcats_{$cats[cat_loop].cat_id}').style.display = ''; this.src='./images/icons/minus16.gif'; cat_minimized.set({$cats[cat_loop].cat_id}, 1); {literal}} else {{/literal} $('subcats_{$cats[cat_loop].cat_id}').style.display = 'none'; this.src='./images/icons/plus16.gif'; cat_minimized.set({$cats[cat_loop].cat_id}, 0); {literal}}{/literal}"{/if} border='0' class='icon' /><a href='browse_jobs.php?s={$s}&v={$v}&jobcat_id={$cats[cat_loop].cat_id}'>{lang_print id=$cats[cat_loop].cat_title}</a>
        <div id='subcats_{$cats[cat_loop].cat_id}' style='display: none;'>
          {section name=subcat_loop loop=$cats[cat_loop].subcats}
            <div style='font-weight: normal;'><img src='./images/trans.gif' border='0' class='icon' style='width: 16px;'><a href='browse_jobs.php?s={$s}&v={$v}&jobcat_id={$cats[cat_loop].subcats[subcat_loop].subcat_id}'>{lang_print id=$cats[cat_loop].subcats[subcat_loop].subcat_title}</a></div>
          {/section}
        </div>
      </div>
    {/section}
    
    {literal}
    <script type="text/javascript">
    <!-- 
      window.addEvent('domready', function()
      {
        cat_list.each(function(catObject, catID)
        {
          if( !cat_minimized.get(catID) ) return;
          $('subcats_'+catID).style.display = '';
          $('icon_'+catID).src = './images/icons/minus16.gif';
        });
      });
    //-->
    </script>
    {/literal}
  </div>
  
  {if !empty($fields)}
  
  <div class='header'>{lang_print id=1089}</div>
  <div class='browse_fields'>
    
    {section name=field_loop loop=$fields}
    
    <div style='font-weight: bold; margin-top: 5px;'>{lang_print id=$fields[field_loop].field_title}</div>
    
      {* TEXT FIELD *}
      {if $fields[field_loop].field_type == 1 || $fields[field_loop].field_type == 2}
        
        {* RANGED SEARCH *}
        {if $fields[field_loop].field_search == 2}
          <input type='text' class='text' size='5' name='field_{$fields[field_loop].field_id}_min' value='{$fields[field_loop].field_value_min}' maxlength='64' />
          - 
          <input type='text' class='text' size='5' name='field_{$fields[field_loop].field_id}_max' value='{$fields[field_loop].field_value_max}' maxlength='64' />	  
        
        {* EXACT VALUE SEARCH *}
        {else}
          <input type='text' class='text' size='15' name='field_{$fields[field_loop].field_id}' value='{$fields[field_loop].field_value}' maxlength='64' />
        {/if}
        
        
      {* SELECT BOX *}
      {elseif $fields[field_loop].field_type == 3}
        <div>
          <select name='field_{$fields[field_loop].field_id}' id='field_{$fields[field_loop].field_id}' onchange="ShowHideDeps('{$fields[field_loop].field_id}', this.value);" style='{$fields[field_loop].field_style}'>
            <option value='-1'></option>
            {* LOOP THROUGH FIELD OPTIONS *}
            {section name=option_loop loop=$fields[field_loop].field_options}
              <option id='op' value='{$fields[field_loop].field_options[option_loop].value}'{if $fields[field_loop].field_options[option_loop].value == $fields[field_loop].field_value} SELECTED{/if}>{lang_print id=$fields[field_loop].field_options[option_loop].label}</option>
            {/section}
          </select>
        </div>
        
        
      {* RADIO BUTTONS *}
      {elseif $fields[field_loop].field_type == 4}
        
        {* LOOP THROUGH FIELD OPTIONS *}
        <div id='field_options_{$fields[field_loop].field_id}'>
        {section name=option_loop loop=$fields[field_loop].field_options}
          <div>
            <input type='radio' class='radio' onclick="ShowHideDeps('{$fields[field_loop].field_id}', '{$fields[field_loop].field_options[option_loop].value}');" style='{$fields[field_loop].field_style}' name='field_{$fields[field_loop].field_id}' id='label_{$fields[field_loop].field_id}_{$fields[field_loop].field_options[option_loop].value}' value='{$fields[field_loop].field_options[option_loop].value}'{if $fields[field_loop].field_options[option_loop].value == $fields[field_loop].field_value} CHECKED{/if}>
            <label for='label_{$fields[field_loop].field_id}_{$fields[field_loop].field_options[option_loop].value}'>{lang_print id=$fields[field_loop].field_options[option_loop].label}</label>
          </div>
          
        {/section}
        </div>
        
        
      {* DATE FIELD *}
      {elseif $fields[field_loop].field_type == 5}
        <div>
          <select name='field_{$fields[field_loop].field_id}_1' style='{$fields[field_loop].field_style}'>
          {section name=date1 loop=$fields[field_loop].date_array1}
            <option value='{$fields[field_loop].date_array1[date1].value}'{$fields[field_loop].date_array1[date1].selected}>{if $smarty.section.date1.first}[ {lang_print id=$fields[field_loop].date_array1[date1].name} ]{else}{$fields[field_loop].date_array1[date1].name}{/if}</option>
          {/section}
          </select>
          
          <select name='field_{$fields[field_loop].field_id}_2' style='{$fields[field_loop].field_style}'>
          {section name=date2 loop=$fields[field_loop].date_array2}
            <option value='{$fields[field_loop].date_array2[date2].value}'{$fields[field_loop].date_array2[date2].selected}>{if $smarty.section.date2.first}[ {lang_print id=$fields[field_loop].date_array2[date2].name} ]{else}{$fields[field_loop].date_array2[date2].name}{/if}</option>
          {/section}
          </select>
          
          <select name='field_{$fields[field_loop].field_id}_3' style='{$fields[field_loop].field_style}'>
          {section name=date3 loop=$fields[field_loop].date_array3}
            <option value='{$fields[field_loop].date_array3[date3].value}'{$fields[field_loop].date_array3[date3].selected}>{if $smarty.section.date3.first}[ {lang_print id=$fields[field_loop].date_array3[date3].name} ]{else}{$fields[field_loop].date_array3[date3].name}{/if}</option>
          {/section}
          </select>
        </div>
        
        
      {* CHECKBOXES *}
      {elseif $fields[field_loop].field_type == 6}
        
        {* LOOP THROUGH FIELD OPTIONS *}
        <div id='field_options_{$fields[field_loop].field_id}'>
        {section name=option_loop loop=$fields[field_loop].field_options}
          <div>
            <input type='checkbox' onclick="ShowHideDeps('{$fields[field_loop].field_id}', '{$fields[field_loop].field_options[option_loop].value}', '{$fields[field_loop].field_type}');" style='{$fields[field_loop].field_style}' name='field_{$fields[field_loop].field_id}[]' id='label_{$fields[field_loop].field_id}_{$fields[field_loop].field_options[option_loop].value}' value='{$fields[field_loop].field_options[option_loop].value}'{if $fields[field_loop].field_options[option_loop].value|in_array:$fields[field_loop].field_value} CHECKED{/if}>
            <label for='label_{$fields[field_loop].field_id}_{$fields[field_loop].field_options[option_loop].value}'>{lang_print id=$fields[field_loop].field_options[option_loop].label}</label>
          </div>
          
        {/section}
        </div>
        
      {/if}
    
    {/section}
    
    {* SHOW SUBMIT BUTTON *}
    <div>
      <div style='padding-top: 10px; padding-bottom: 5px;'>
        <input type='submit' class='button' value='{lang_print id=1090}' />&nbsp;&nbsp;
      </div>
    </div>
  {/if}


</td>
<td style='vertical-align: top; padding-left: 10px;'>

  {* NO jobS AT ALL *}
  {if !$jobs|@count}
    <br />
    <table cellpadding='0' cellspacing='0' align='center'>
      <tr>
        <td class='result'>
          <img src='./images/icons/bulb16.gif' border='0' class='icon' />
          {lang_print id=6400134}
        </td>
      </tr>
    </table>
  {/if}

  {* DISPLAY PAGINATION MENU IF APPLICABLE *}
  {if $maxpage > 1}
    <div class='job_pages_top'>
      {if $p != 1}
        <a href='javascript:void(0);' onclick='document.seBrowseJobs.p.value={math equation="p-1" p=$p};document.seBrowseJobs.submit();'>&#171; {lang_print id=182}</a>
      {else}
        &#171; {lang_print id=182}
      {/if}
      &nbsp;|&nbsp;&nbsp;
      {if $p_start == $p_end}
        <b>{lang_sprintf id=184 1=$p_start 2=$total_jobs}</b>
      {else}
        <b>{lang_sprintf id=185 1=$p_start 2=$p_end 3=$total_jobs}</b>
      {/if}
      &nbsp;&nbsp;|&nbsp;
      {if $p != $maxpage}
        <a href='javascript:void(0);' onclick='document.seBrowseJobs.p.value={math equation="p+1" p=$p};document.seBrowseJobs.submit();'>{lang_print id=183} &#187;</a>
      {else}
        {lang_print id=183} &#187;
      {/if}
    </div>
  {/if}

  {section name=job_loop loop=$jobs}
    <div style='padding: 10px; border: 1px solid #CCCCCC; margin-bottom: 10px;'>
      <table cellpadding='0' cellspacing='0'>
        <tr>
          <td>
            <a href='{$url->url_create("job", $jobs[job_loop].job_author->user_info.user_username, $jobs[job_loop].job->job_info.job_id)}'>
              <img src='{$jobs[job_loop].job->job_photo("./images/nophoto.gif", TRUE)}' border='0' width='60' height='60' />
            </a>
          </td>
          <td style='vertical-align: top; padding-left: 10px;'>
            <div style='font-weight: bold; font-size: 10pt;'>
              <a href='{$url->url_create("job", $jobs[job_loop].job_author->user_info.user_username, $jobs[job_loop].job->job_info.job_id)}'>
                {$jobs[job_loop].job->job_info.job_title}
              </a>
            </div>
            <div style='color: #777777; font-size: 7pt; margin-bottom: 5px;'>
              {assign var='job_datecreated' value=$datetime->time_since($jobs[job_loop].job->job_info.job_date)}
              {capture assign="created"}{lang_sprintf id=$job_datecreated[0] 1=$job_datecreated[1]}{/capture}
              {assign var='job_dateupdated' value=$datetime->time_since($jobs[job_loop].job->job_info.job_dateupdated)}
              {capture assign="updated"}{lang_sprintf id=$job_dateupdated[0] 1=$job_dateupdated[1]}{/capture}
              
              {lang_sprintf id=6400072 1=$jobs[job_loop].job->job_info.job_views}
              - {lang_sprintf id=507 1=$jobs[job_loop].job->job_info.total_comments}
              - {lang_sprintf id=6400135 1=$created}
              {if $jobs[job_loop].job->job_info.job_dateupdated && $created!=$updated}
                - {lang_sprintf id=6400136 1=$updated}
              {/if}
            </div>
            <div>
              {$jobs[job_loop].job->job_info.job_desc|truncate:300:"...":true}
            </div>
          </td>
        </tr>
      </table>
    </div>
  {/section}

  {* DISPLAY PAGINATION MENU IF APPLICABLE *}
  {if $maxpage > 1}
    <div class='job_pages_bottom'>
      {if $p != 1}
        <a href='javascript:void(0);' onclick='document.seBrowseJobs.p.value={math equation="p-1" p=$p};document.seBrowseJobs.submit();'>&#171; {lang_print id=182}</a>
      {else}
        &#171; {lang_print id=182}
      {/if}
      &nbsp;|&nbsp;&nbsp;
      {if $p_start == $p_end}
        <b>{lang_sprintf id=184 1=$p_start 2=$total_jobs}</b>
      {else}
        <b>{lang_sprintf id=185 1=$p_start 2=$p_end 3=$total_jobs}</b>
      {/if}
      &nbsp;&nbsp;|&nbsp;
      {if $p != $maxpage}
        <a href='javascript:void(0);' onclick='document.seBrowseJobs.p.value={math equation="p+1" p=$p};document.seBrowseJobs.submit();'>{lang_print id=183} &#187;</a>
      {else}
        {lang_print id=183} &#187;
      {/if}
    </div>
  {/if}

</td>
</tr>
</table>

</form>

{include file='footer.tpl'}