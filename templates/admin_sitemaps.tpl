{$startform}

{if !empty($sitemap_errors)}
<div class="pagewarning">
   <h3>{$mod->Lang('title_sitemap_errors')}</h3>
   <ul>
{foreach $sitemap_errors as $err}
      <li>{$err}</li>
{/foreach}
   </ul>
</div>
{/if}


<div class="pageoverflow">
   <p class="pagetext">{$mod->Lang('title_generated_sitemaps')}</p>
</div>
{if !empty($sitemaps)}
<table class="pagetable">
   <thead>
      <tr>
         <th>{$mod->Lang('title_sitemap')}</th>
         <th>{$mod->Lang('title_sitemap_module')}</th>
         <th>{$mod->Lang('title_sitemap_url')}</th>
         <th class="pageicon">{$mod->Lang('title_active')}&nbsp;&nbsp;</th>
      </tr>
   </thead>
   <tbody class="sortable-widget-list">
{foreach $sitemaps as $sitemap}
{if $sitemap->module!='other'}
      <tr data-id="{$sitemap->id}" class="row{if $sitemap@index is even}1{else}2{/if}">
         <td>{if $sitemap->active}<strong>{/if}{$sitemap->name}{if $sitemap->active}<strong>{/if}</td>
         <td>{$sitemap->module}</td>
         <td><a href="{root_url}/{$sitemap->url}" target="_blank">{$sitemap->url}</a></td>
         <td class="pagepos"><a class="active_sitemap" href="{cms_action_url action=admin_save_sitemaps toggle_active=1 sid=$sitemap->id}">{if $sitemap->active}{admin_icon icon='true.gif'}{else}{admin_icon icon='false.gif'}{/if}</a>
         </td>
      </tr>
{/if}
{/foreach}
   </tbody>
</table>
{/if}
<div class="pageoverflow">
   <p class="pageinput">{$mod->Lang('info_lise_text')}</p>
</div>
<br>



<div class="pageoverflow">
   <p class="pagetext">{$mod->Lang('title_generated_special')}</p>
</div>
{if !empty($sitemaps)}
<table class="pagetable">
   <thead>
      <tr>
         <th>{$mod->Lang('title_page')}</th>
         <th>{$mod->Lang('title_sitemap_url')}</th>
         <th class="pageicon">{$mod->Lang('title_active')}&nbsp;&nbsp;</th>
      </tr>
   </thead>
   <tbody class="sortable-widget-list">
{foreach $sitemaps as $sitemap}
{if $sitemap->module=='other'}
      <tr data-id="{$sitemap->id}" class="row{if $sitemap@index is even}1{else}2{/if}">
         <td>{if $sitemap->active}<strong>{/if}{$sitemap->name}{if $sitemap->active}<strong>{/if}</td>
         <td><a href="{root_url}/{$sitemap->url}" target="_blank">{$sitemap->url}</a></td>
         <td class="pagepos"><a class="active_sitemap" href="{cms_action_url action=admin_save_sitemaps toggle_active=1 sid=$sitemap->id}">{if $sitemap->active}{admin_icon icon='true.gif'}{else}{admin_icon icon='false.gif'}{/if}</a>
         </td>
      </tr>
{/if}
{/foreach}
   </tbody>
</table>
{/if}
<br>

{*
<div class="pageoverflow">
   <p class="pagetext">{$title_default_sitemap_page}:</p>
   <p class="pageinput">{$input_default_sitemap_page}<br>
      {$info_default_sitemap_page}</p>
</div>


<div class="pageoverflow">
   <p class="pagetext">&nbsp;</p>
   <p class="pageinput">
      <input type="submit" name="{$actionid}submit" value="{$mod->Lang('submit')}"/>
   </p>
</div>
*}
{$endform}