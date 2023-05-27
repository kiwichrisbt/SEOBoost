<?php
#---------------------------------------------------------------------------------------------------
# Module: SEOBoost
# Author: Chris Taylor
# Copyright: (C) 2019 Chris Taylor, chris@binnovative.co.uk
# Module's homepage is: http://dev.cmsmadesimple.org/projects/seoboost
# Licence: GNU General Public License version 3
#          see /SEOBoost/lang/LICENCE.txt or <http://www.gnu.org/licenses/>
#---------------------------------------------------------------------------------------------------

$lang['friendlyname'] = 'SEOBoost';
$lang['admindescription'] = 'Provides the functions to Search Engine Optimise your website.';
$lang['need_permission'] = 'You need permission to use this module';

# Install / Uninstall
$lang['install_msg'] = "You have successfully installed the 'SEOBoost' module.";
$lang['install_BIExtensions_error'] = "Module 'BIExtensions' needs to be uninstalled before installing 'SEOBoost'";
$lang['ask_uninstall'] = 'Are you sure you want to uninstall the SEOBoost module?';


# General
$lang['submit'] = 'Submit';
$lang['cancel'] = 'Cancel';
$lang['settings_saved'] = 'Your %s have been saved.';


# Company Info Tab
$lang['company_info'] = 'Company Info';
$lang['company_info_tab_saved'] = 'Company Info Tab settings saved';
$lang['title_phoneNumber'] = 'Phone Number';
$lang['title_mobileNumber'] = 'Mobile Number';
$lang['title_email'] = 'Email Address';
$lang['title_fax'] = 'Fax Number';
$lang['title_fullBusinessName'] = 'Full Business Name';
$lang['title_street1'] = 'Street Address 1';
$lang['title_street2'] = 'Street Address 2';
$lang['title_city'] = 'City or Town';
$lang['title_region'] = 'Region or County';
$lang['title_postcode'] = 'Postcode';
$lang['title_country'] = 'Country';
$lang['title_businessType'] = 'Business Type';
$lang['title_latitude_longitude'] = 'Latitude Longitude';
$lang['title_copyright_start'] = 'Copyright Start Year';
$lang['title_ga_code'] = 'Google Analytics Account Code';
$lang['info_phoneNumber'] = 'use {$pageVars.phoneNumberLink} for a click-to-call link, or {$pageVars.phoneNumber} for the raw phone number';
$lang['info_mobileNumber'] = 'use {$pageVars.mobileNumberLink} for a click-to-call link, or {$pageVars.mobileNumber} for the raw phone number';
$lang['info_email'] = 'use {$pageVars.emailLink} for an obfusicated (javascript) email link, or {$pageVars.email} for the raw email address';
$lang['info_fax'] = 'use {$pageVars.Fax_Number}';
$lang['info_fullBusinessName'] = "Full Legal Business Name";
$lang['info_street1'] = "[street1]";
$lang['info_street2'] = "[street2]";
$lang['info_city'] = "[addressLocality]";
$lang['info_region'] = "[addressRegion]";
$lang['info_postcode'] = "[postalCode]";
$lang['info_country'] = "[addressCountry] e.g. 'United Kingdom'";
$lang['info_businessType'] = "see schema.org i.e. LocalBusiness/Electrician/Plumber/...";
$lang['info_latitude_longitude'] = "Latitude,Longitude (comma separated)";
$lang['info_copyright_start'] = "for footer copyright label - End Year automatically updates";
$lang['info_ga_code'] = "get from the websites Google Analytics account ";


# Social Tab
$lang['social'] = 'Social';
$lang['social_tab_saved'] = "Social Tab settings saved";
$lang['title_twitter'] = 'Twitter';
$lang['info_twitter'] = "Twitter name (e.g. YourCompanyName)";
$lang['title_facebook'] = 'Facebook';
$lang['info_facebook'] = "Facebook Name (e.g. YourCompanyName)";
$lang['title_linkedin'] = 'LinkedIn';
$lang['info_linkedin'] = "LinkedIn name (e.g. company/b-innovative-ltd)";
$lang['title_instagram'] = 'Instagram';
$lang['info_instagram'] = "Instagram name (e.g. YourCompanyName)";


# Sitemaps Tab
$lang['sitemaps'] = 'Sitemaps';
$lang['sitemaps_tab_saved'] = "Sitemaps Tab settings saved";
$lang['title_sitemap'] = 'Sitemap';
$lang['title_sitemap_module'] = 'Module';
$lang['title_sitemap_url'] = 'URL';
$lang['title_active'] = 'Active';
$lang['title_generated_sitemaps'] = 'Generated XML Sitemaps';
$lang['title_sitemap_errors'] = 'The following errors have been found that with stop the sitemaps and other pages working fully.';
$lang['info_lise_text'] = 'Any LISE Instances will automatically be included above. Manually select to have a sitemap generated.';
$lang['title_generated_special'] = 'Generated Special Pages';
$lang['title_page'] = 'Page';


# Options Tab
$lang['tab_options'] = 'Options';
$lang['title_customModuleName'] = 'Custom Module Name';
$lang['title_adminSection'] = 'Module Admin Section';
$lang['title_useSearchable'] = 'Use Searchable';
$lang['info_useSearchable'] = "Use page setting 'This page is searchable' to determine if pages are set to 'robots no-index', or not";


# Templates
$lang['type_SEOBoost'] = 'SEOBoost'; // both used by Design Manager for translations
$lang['type_Sitemap'] = 'Sitemap';
$lang['type_Robots_txt'] = 'Robots.txt';
$lang['type_Metadata'] = 'Metadata';
$lang['type_RSS'] = 'RSS';
$lang['template_no_default'] = 'No default template found';








###    ###   #########   ###        #########
###    ###   #########   ###        #########
###    ###   ###         ###        ###   ###
##########   #########   ###        #########
##########   #########   ###        #########
###    ###   ###         ###        ###
###    ###   #########   #########  ###
###    ###   #########   #########  ###

$lang['help'] = <<<'EOD'

<h3 name="">What does this do?</h3>
<p>'SEOBoost' provides the functionality to improve the Search Engine Optimisation your website.</p><br>
<p>Why this module: We believe that SEO is a fundamental part of any website. This module is designed to make this as easy as possible. So that time spent on SEO can be as value adding as possible. Plus we like to make life simpler for ourselves - e.g. by automatically generating sitemaps, robots.txt &amp; RSS feeds, etc.</p><br>
<p>This is a beta version with many additional features planned. Any released version should be suitable to use on a production site, (I will be). Please feel free to send me any feedback, comments or requests. Contact details below.</p>
<br>


<h3>Metadata</h3>
<p><strong>To use: </strong> add {SEOBoost action=metadata} immediately after the opening <_head_> tag. Default metatags output include base (optional), geographic, Schema.org, Open Graph, Twitter Card, favicon, apple-touch-icon, news rss feed (if enabled).</p>
<p>This will ouput a set of metatags from the 'SEOBoost_metadata' default template. The template can be edited as you desire.</p>
<p>Parameters:</p>
<ul>
   <li>'showbase' (true/false) - if set to false, the base tag will not be output.</li>
</ul>
<br>



<h3>Sitemaps</h3>
<p>If enabled this module will automatically add dynamic sitemaps for all supported modules including: core Pages, News, Products and LISE instances (opt-in). See the SEOBoost > Sitemaps tab for details. A sitemap index is output at /sitemap.xml that directs Search Engines to each individual sitemap. The 'sitemap.xml' url should be submitted to Google, Bing, etc.</p>
<p>The Sitemaps tab sitemap urls can be clicked on to view the live sitemap.</p>
<p>If static pages or CMS pages also exist for the same urls the dynamic sitemaps will not work correctly. A warning will be displayed.</p>
<p>A default template 'SEOBoost_sitemap' has been created and can be edited as you wish.</p>
<br>



<h3>Robots.txt &amp; News RSS feed</h3>
<p>Dynamic robots.txt and a News RSS feed will also generated. Can be deactivated on the SEOBoost > Sitemaps tab. These use the same mechanisms as the sitemaps.</p>
<p>Default templates 'SEOBoost_robots_txt' & 'SEOBoost_rss' hve been created and can be edited as you wish.</p>
<br>



<h3>googleAnalytics</h3>
<p>Outputs Google Analytics tracking code, using the Universal Analytics, version of GA.</p>
<p>In the SEOBoost > Company Info tab - add 'Google Analytics Account Code' into the provided field.</p>
<p><strong>To use: </strong> add {googleAnalytics} tag just before the end <_/body_> tag.</p>
<br>



<h3>socialLinks</h3>
<p>Outputs a list of links to related Social Media pages based on setting in the SEOBoost > Social tab. Including: Facebook, Twitter and LinkedIn pages. This template includes metadata.</p>
<p><strong>To use: </strong> add {socialLinks}' - a list of links to Social Media pages based on setting in 'Website Settings' *</p>
<p>The template 'socialLinks_template.tpl' can be safely customised by placing a customised copy in 'assets/module_custom/SEOBoost/templates'.</p>
<br>



<h3>socialShares</h3>
<p>Outputs a list of social sharing buttons including: LinkedIn, Twitter, Facebook, Email.</p>
<p>CSS and icon graphics will need to be included.</p>
<p><strong>To use: </strong> add {socialShares} into a template of your choice.</p>
<p>The template 'socialShares_template.tpl' can be safely customised by placing a customised copy in 'assets/module_custom/SEOBoost/templates'.</p>
<br>



<h3>pageVars</h3>
<p>The global smarty variable $pageVars is automatically available to all templates. This includes all the details from the SEOBoost > Company Info tab, plus some useful extra fields.</p>
<p>To see all included fields use {$pageVars|print_r}</p>
<p>The $pageVars variables are then available to be changed, extra fields added or used by any templates. e.g. a Products detail template may wish to change the {$pageVars.pageTitle} {$pageVars.pageDescription} and {$pageVars.keyword1} fields (and more). These fields can then be used in the head metadata on the page.<br>
<p>The following useful fields have been added in addition to the Company Info details. Some are only created if the relevant fields are set in the Company Info tab. Some fields include metadata - see (*) below:</p>
<ul>
   <li>'pageTitle' - the page title</li>
   <li>'pageDescription' - the page description</li>
   <li>'siteName' - site name i.e. {sitename}</li>
   <li>'keyword1' - the page title prior to a '|' character if used</li>
   <li>'keyword2' - the page title after the '|' character if used, otherwise the entire page title</li>
   <li>'homePageKeywords' - keywords for the home page (to use in links/alt tags back to home page)</li>
   <li>'phoneNumberLink' - the phoneNumber, but wrapped in a clickable link *</li>
   <li>'mobileNumberLink' - as mobileNumber, but wrapped in a clickable link *</li>
   <li>'emailLink' - obfuscated email (javascript), wrapped in a clickable link *</li>
   <li>'copyright' - copyright year(s). e.g. '2010-2019' or just the current year if not set.</li>
   <li>'typeMicrodata' - businessType with microdata *</li>
   <li>'fullBusinessNameMicrodata' - fullBusinessName with microdata *</li>
   <li>'addressMicrodata' - full address, including business name with microdata *</li>
   <li>'latitude' - just the latitude</li>
   <li>'longitude' - just the longitude</li>
</ul>
<br>



<h3>Support</h3>
<p>As per the GPL licence, this software is provided as is. Please read the text of the license for the full disclaimer.
The module author is not obligated to provide support for this code. However you might get support through the following:</p>
<ul>
   <li>For support, first <strong>search</strong> the <a href="//forum.cmsmadesimple.org" target="_blank">CMS Made Simple Forum</a>, for issues with the module similar to those you are finding.</li>
   <li>Then, if necessary, open a <strong>new forum topic</strong> to request help, with a thorough description of your issue, and steps to reproduce it.</li>
   <li>Contact me via the CMS Made Simple Slack channel @KiwiChris</li>
   <li>If you find a bug you can <a href="http://dev.cmsmadesimple.org/bug/list/1422" target="_blank">submit a Bug Report</a>.</li>
   <li>For any good ideas you can <a href="http://dev.cmsmadesimple.org/feature_request/list/1422" target="_blank">submit a Feature Request</a>.</li>
   <li>If you found the Module useful - shout out to me on Twitter <a href="//twitter.com/KiwiChrisBT">@KiwiChrisBT</a></li>
</ul><br>


<h3>Copyright &amp; Licence</h3>
<p>Copyright © 2019, Chris Taylor <chris at binnovative dot co dot uk>. All Rights Are Reserved.</p><br>
<p>This module has been released under the GNU Public License v3. However, as a special exception to the GPL, this software is distributed as an addon module to CMS Made Simple. You may only use this software when there is a clear and obvious indication in the admin section that the site was built with CMS Made Simple!</p><br>
<br>
EOD;








#########  ###    ###  ##########  ###    ###  #########  ########  ###       #########  #########
#########  ###    ###  ##########  ####   ###  #########  ########  ###       #########  #########
###        ###    ###  ###    ###  #####  ###  ###        ###       ###       ###   ###  ###
###        ##########  ##########  ### ## ###  ###        ########  ###       ###   ###  ###
###        ##########  ##########  ###  #####  ###   ###  ########  ###       ###   ###  ###   ###
###        ###    ###  ###    ###  ###   ####  ###   ###  ###       ###       ###   ###  ###   ###
#########  ###    ###  ###    ###  ###    ###  #########  ########  ######### #########  #########
#########  ###    ###  ###    ###  ###    ###  #########  ########  ######### #########  #########

$lang['changelog'] = <<<'EOD'

<h3>Version 1.0.3 - 06Feb23</h3>
<ul>
   <li>actual bug fix for saving instagram setting after upgrade</li>
</ul>
<br>

<h3>Version 1.0.2 - 01Feb23</h3>
<ul>
   <li>bug fix for saving instagram setting after upgrade</li>
</ul>
<br>

<h3>Version 1.0.1 - 29Nov22</h3>
<ul>
   <li>thought it was about time I remove the beta tag as I've been using this on production sites for 3 years!</li>
   <li>correctly set $pageVars.pageImage - not previously set</li>
   <li>remove {pageVars} depreciated tag warning from admin log - it may need to be specifically called occasionally</li>
</ul>
<br>


<h3>Version 0.9.3beta - 24Jan22 - ??? not yet publically released</h3>
<ul>
   <li>Add Instagram into Social tab</li>
</ul>
<br>

<h3>Version 0.9.2beta - 17Jun19</h3>
<ul>
   <li>extra Admin table styling</li>
   <li>min CMSMS version 2.2 - as Hooks are required</li>
   <li>bug fixes in Help</li>
   <li>bug fix for previous compatibility with googleAnalytics tag parameters</li>
</ul>
<br>

<h3>Version 0.9beta - 14Mar19</h3>
<ul>
   <li>add {SEOBoost action=metadata} functionality</li>
   <li>remove requirement for sitemap blank page & template & function correctly</li>
   <li>bug fix Module Admin Section selection</li>
   <li>bug fixes Social tab - saving blank values & LinkedIn output</li>
   <li>bug fixes for googleAnalytics - including update to template</li>
   <li>bug fixes Company Info tab - saving blank values</li>
   <li>bug fix Options tab - Custom Module name cannot be blank</li>
</ul>
<br>

<h3>Version 0.8.3beta - 20Feb19</h3>
<ul>
   <li>bug fix for save Options tab</li>
</ul>
<br>

<h3>Version 0.8.2beta - 17Feb19</h3>
<ul>
   <li>bug fix for install routine - in some cases</li>
</ul>
<br>

<h3>Version 0.8.1beta - 16Feb19</h3>
<ul>
   <li>fixed install bug for sitemap routes</li>
</ul>
<br>

<h3>Version 0.8beta - 16Feb19</h3>
<ul>
   <li>first release for testing</li>
</ul>
<br>

EOD;


