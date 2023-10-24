{* SEOBoost_sitemap.tpl - v1.0 - 12Feb19

   - v1.0 - 12Feb19 - initial SEOBoost version

   Generates dynamic sitemaps for multiple different modules: sitemap index, Navigator, News, Products, LISEInstances.
   - xml definition tag must be on the first line output

***************************************************************************************************}<?xml version="1.0" encoding="UTF-8"?>
{if $sitemap_type=='index'}
{***************************************************************************************************

   - a sitemap index file linking to sitemaps for supported Modules that are installed

***************************************************************************************************}
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
{foreach $items as $item}
{if $item->active && $item->name!='index' && $item->module!='other'}
   <sitemap><loc>{root_url}/{$item->url}</loc></sitemap>
{/if}
{/foreach}
</sitemapindex>



{elseif $sitemap_type=='pages'}
{***************************************************************************************************

   - a Navigator compatible template - to output all active pages

***************************************************************************************************}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
{function name=Nav_sitemap}
{foreach $data as $node}
{page_attr key=searchable page=$node->id assign=isSearchable}
{if $node->type=='content' && $isSearchable}
   <url>
      <loc>{$node->url}</loc>
      <lastmod>{$node->modified|date_format:'%Y-%m-%d'}</lastmod>
      <changefreq>{math now=$smarty.now modified=$node->modified equation='(now-modified)/86400' assign='days'}{if $days < 2}hourly{elseif $days < 14}daily{elseif $days < 61}weekly{elseif $days < 365}monthly{else}yearly{/if}</changefreq>
      <priority>{$level=$node->hierarchy|substr_count:'.'}{if $node->url|substr:0:-1 == {root_url}}1{elseif $level == '0'}0.8{elseif $level == '1'}0.6{elseif $level == '2'}0.4{else}0.2{/if}</priority>
   </url>
{/if}
{if isset($node->children)}{Nav_sitemap data=$node->children}{/if}
{/foreach}
{/function}
{if isset($nodes)}
{Nav_sitemap data=$nodes}
{/if}
</urlset>



{elseif $sitemap_type=='news'}
{***************************************************************************************************

   - a News compatible template - to output all active News pages

***************************************************************************************************}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
{foreach $items as $item}
   <url>
      <loc>{$item->moreurl}</loc>
      <lastmod>{$item->modified_date|date_format:'%Y-%m-%d'}</lastmod>
      <changefreq>{$days=({$smarty.now|date_format:"%s"}-{$item->modified_date|date_format:"%s"})/86400}{if $days < 2}hourly{elseif $days < 14}daily{elseif $days < 61}weekly{elseif $days < 365}monthly{else}yearly{/if}</changefreq>
      <priority>0.6</priority>
   </url>
{/foreach}
</urlset>



{elseif $sitemap_type=='products'}
{***************************************************************************************************

   - a Products compatible template - to output all active Product pages

***************************************************************************************************}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
{foreach $items as $item}
<url>
  <loc>{$item->detail_url}</loc>
  <lastmod>{$item->modified_date|date_format:'%Y-%m-%d'|default:'2017-01-01'}</lastmod>
</url>
{/foreach}
</urlset>



{elseif $sitemap_type='lise'}
{***************************************************************************************************

   - a LISE instance compatible template - to output all active pages from any LISE instance

   !!!call LISE from in here as it doesn't use external templates

***************************************************************************************************}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
{if isset($LISEModuleName)}
{capture 'tmp'}{cms_module module=$LISEModuleName}{/capture}
{foreach $items as $item}
<url>
   <loc>{$item->url}</loc>
   <lastmod>{$item->modified_time|date_format:'%Y-%m-%d'|default:'2017-01-01'}</lastmod>
</url>
{/foreach}
{/if}
</urlset>



{/if}