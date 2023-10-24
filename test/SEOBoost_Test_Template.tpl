{* SEOBoost_Test_Template - v1.3 - 14Mar19

   - v1.3 - 14Mar19 - added metadata
   - v1.2 - 14Feb19 - added notes for 1st beta release
   - v1.0 - 08Feb19 -

   Template stored in SEOBoost/test/SEOBoost_Test_Template.tpl
      - copy template into a core:page template & create 'SEOBoost Test' page

********************************************************************************************}{strip}
{content oneline=1 label="SEOBoost TEST Template" assign=content1}


{cgjs_require lib='jquery'}
{cgjs_require jsfile='assets/js/bootstrap.bundle.min.js'}
{cgjs_require lib='form'}
{cgjs_require jsfile='assets/js/slick.min.js'}
{cgjs_require jsfile='assets/js/main.js'}


{/strip}<!DOCTYPE html>
<html lang="en">
<head>
   {SEOBoost action=metadata}
   <!-- nominify -->
   {cssMinifier css="{cms_stylesheet nolinks=true}"}
   <style>{literal}.pre {font-family:monospace; white-space:pre-wrap; word-wrap:break-word;
      background-color:#DDD; padding:1em;}{/literal}</style>
</head>

<body class="page-{$page_alias}">

{global_content name='header'}

<div class="main">
   <div class="container">
      <div class="row">
         <div class="col-12">

<h1>SEOBoost Test Page - Frontend Tests</h1>


<h2>Test 1: googleAnalytics</h2>
<div class="pre">Basic {ldelim}googleAnalytics{rdelim} output:
      To view: use F12 Developer Tools and review code just above end < /body> tag.
{googleAnalytics debug=1}
</div>
<br>


<h2>Test 2: pageVars</h2>
<p>$pageVars are:</p>
<div class="pre">{$pageVars|print_r}</div>
<br>


<h2>Test 3: socialLinks</h2>
<p>{ldelim}socialLinks{rdelim} outputs:</p>
<div class="pre">{socialLinks}</div>
<br>


<h2>Test 4: socialShares</h2>
<p>{ldelim}socialShares{rdelim} outputs:</p>
<div class="pre">{socialShares}</div>
<br>


<h2>Test 5: Dynamic Sitemaps, robots.txt & RSS</h2>
<p>Simply view the SEOBoost > Sitemaps page:</p>
<ol>
   <li>check that all sitemaps etc can be activated/deactivated</li>
   <li>click on the sitemap urls to check output - when active & not</li>
   <li>check that 'Default Page to use for Sitemaps' can be changed & sitemap output</li>
   <li>check Uninstall - that 'SEOBoost blank page' & 'SEOBoost_blank' template are removed</li>
</ol>
<br>


<h2>Test 6: metadata</h2>
<p>{ldelim}SEOBoost action=metadata{rdelim} outputs:</p>
<div class="pre">{SEOBoost|replace:'<':'< '|replace:'>':' >' action=metadata}</div>
<br>



<br><br><p>That's it. All Tests done :)</p>

         </div>
      </div>
   </div>
</div><!-- main-->


{global_content name='footer'}

{googleFontUrl urlonly=1 assign=googleFontUrl}
{if !empty($googleFontUrl)}
   <link rel="stylesheet" type="text/css" href="{$googleFontUrl}">
{/if}
{cgjs_render addkey='19Oct18'}
{googleAnalytics}

</body>
</html>