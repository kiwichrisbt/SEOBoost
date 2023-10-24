{* News RSS Feed - v2.2 - 13Feb19

   - v2.2 - 13Feb19 - tweaked for move into SEOBoost template
   - v2.1 - 14Nov16 - removed eval from summary - can break with smarty tags
   - v2.0 - 10Mar16 - converted from Feedmaker module to std News module template

***************************************************************************************************}<?xml version="1.0"?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
   <channel>
      <atom:link href="{root_url}/feeds-news.rss" rel="self" type="application/rss+xml" />
      <title>{sitename}</title>
      <link>{root_url}/feeds-news.rss</link>
      <description>{sitename} News</description>
      <copyright>Copyright {$pageVars.copyright}, {sitename}</copyright>
{if !empty($pageVars.email)}
      <managingEditor>{$pageVars.email}</managingEditor>
{/if}
      <generator>CMS Made Simple</generator>
{foreach $items as $entry}
      <item>
         <title>{$entry->title|escape}</title>
         <description>{strip}
            {if $entry->summary}
               {$entry->summary|strip_tags|escape|truncate:400}
            {else}
               {$entry->content|strip_tags|escape|truncate:400}
            {/if}
         {/strip}</description>
         <guid>{$entry->moreurl}</guid>
         <link>{$entry->moreurl}</link>
         <pubDate>{$entry->postdate|rfc_date}</pubDate>
      </item>
{/foreach}

   </channel>
</rss>