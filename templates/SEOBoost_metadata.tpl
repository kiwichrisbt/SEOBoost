{* SEOBoost_metadata.tpl - v1.1 - 29Aug23

    - v1.1 - 29Aug23 - updated for geo_region
    - v1.0 - 13Mar19 - initial SEOBoost version

   Displays the metadata for this page, mainly using the $pageVars array

***************************************************************************************************}
   <meta charset="utf-8">
{if $showbase}
   <base href="{root_url}/">
{/if}
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <title>{$pageVars.pageTitle}</title>
{if !empty($pageVars.pageDescription)}
   <meta name="description" content="{$pageVars.pageDescription}"/>
{/if}
{if !empty($pageVars.address)}
   <meta name="geo.placename" content="{$pageVars.address}"/>
{/if}
{if !empty($pageVars.geo_region)}
   <meta name="geo.region" content="{$pageVars.geo_region}"/>
{/if}
{if !empty($pageVars.latitude) && !empty($pageVars.longitude)}
   <meta name="geo.position" content="{$pageVars.latitude};{$pageVars.longitude}"/>
   <meta name="ICBM" content="{$pageVars.latitude}, {$pageVars.longitude}"/>
{/if}
{if !empty($pageVars.fullBusinessName)}
   <meta name="author" content="{$pageVars.fullBusinessName}"/>
{/if}
   <meta name="generator" content="CMS Made Simple - Copyright (c) 2019 CMS Made Simple Foundation. All rights reserved."/>
   <!-- Schema.org markup for Google+ -->
   <meta itemprop="name" content="{$pageVars.pageTitle}">
{if !empty($pageVars.pageDescription)}
   <meta itemprop="description" content="{$pageVars.pageDescription}"/>
{/if}
{if isset($pageVars.pageImage)}
   <meta itemprop="image" content="{$pageVars.pageImage}">
{/if}
   <!-- Open Graph data -->
   <meta property="og:site_name" content="{$pageVars.siteName}"/>
   <meta property="og:title" content="{$pageVars.pageTitle}"/>
{if !empty($pageVars.pageDescription)}
   <meta property="og:description" content="{$pageVars.pageDescription}"/>
{/if}
   <meta property="og:type" content="website"/>
{if isset($pageVars.pageImage)}
   <meta property="og:image" content="{$pageVars.pageImage}"/>
{/if}
   <meta property="og:url" content="{if isset($pageVars.canonical)}{$pageVars.canonical}{else}{cms_selflink href=$page_id}{/if}"/>
{if !empty($pageVars.price)}
   <meta property="og:price:amount" content="{$pageVars.price}"/>
   <meta property="og:price:currency" content="GBP"/>
{/if}
   <!-- Twitter Card data -->
{if empty($pageVars.pageImage)}
   <meta name="twitter:card" content="summary">
{else}
   <meta name="twitter:card" content="summary_large_image">
   <meta name="twitter:image:src" content="{$pageVars.pageImage}">
{/if}
{if !empty($pageVars.twitter)}
   <meta name="twitter:site" content="@{$pageVars.twitter}">
   <meta name="twitter:creator" content="@{$pageVars.twitter}">
{/if}
   <meta name="twitter:title" content="{$pageVars.pageTitle}">
{if !empty($pageVars.pageDescription)}
   <meta name="twitter:description" content="{$pageVars.pageDescription}">
{/if}
{if !empty($pageVars.price)}
   <meta name="twitter:data1" content="£{$pageVars.price}">
   <meta name="twitter:label1" content="Price">
{/if}
{if isset($pageVars.searchable) && $pageVars.searchable=='0'}
   <meta name="robots" content="noindex">
{/if}
   <link rel="shortcut icon" href="favicon.ico"/>
   <link rel="apple-touch-icon" href="apple-touch-icon.png">
{if !empty($pageVars.canonical)}
   <link rel="canonical" href="{$pageVars.canonical}"/>
{/if}
{if !empty($rss_url)}
   <link rel="alternate" type="application/rss+xml" title="{$pageVars.fullBusinessName} News" href="{$rss_url}"/>
{/if}
{if isset($pageVars.googleFontUrl)}
   <link rel="stylesheet" type="text/css" href="{$pageVars.googleFontUrl}">
{/if}