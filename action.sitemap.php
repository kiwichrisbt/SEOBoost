<?php
#---------------------------------------------------------------------------------------------------
# Module: SEOBoost
# Author: Chris Taylor
# Copyright: (C) 2019 Chris Taylor, chris@binnovative.co.uk
# Module's homepage is: http://dev.cmsmadesimple.org/projects/seoboost
# Licence: GNU General Public License version 3
#          see /SEOBoost/lang/LICENCE.txt or <http://www.gnu.org/licenses/>
#---------------------------------------------------------------------------------------------------

if( !defined('CMS_VERSION') ) exit;


if ( \CMSMS\HookManager::in_hook('Core::ContentPostRender')==false ) {
   // sitemap called but default content page would be rendered
   // to avoid needing a 'blank' page & template, add a hook for ContentPostRender that will
   // replace the 'default' html with 'sitemap' output

   // add Hook for ContentPostRender - but only for a 'sitemap' page
   \CMSMS\HookManager::add_hook('Core::ContentPostRender','SEOBoost::OutputSitemap');
   return;

}



// load all settings from db
// $requestURI = trim($_SERVER["REQUEST_URI"], '/');
$basename = basename($_SERVER["REQUEST_URI"]);
$query = new SEOBoostSitemapQuery;
$res = $query->GetMatches();

$sitemapName = '';
if ($res) {
   foreach ($res as $sitemap) {
      if ($basename==$sitemap->url && $sitemap->active) {
         $sitemapName = $sitemap->name;
         $sitemapModule = $sitemap->module;
         $sitemapURL = $sitemap->url;
         if (substr($sitemapModule, 0, 4)=='LISE')
            $sitemapName = 'lise';
      }
   }
}
$smarty->assignGlobal('sitemap_type', $sitemapName);  // can then be read by all templates

try {
   // then get the sitemap template
   switch ($sitemapName) {
      case 'index':
      case 'pages':
      case 'news':
      case 'products':
      case 'lise':
         $templateType = 'SEOBoost::Sitemap';
         break;

      case 'robots.txt':
         $templateType = 'SEOBoost::Robots_txt';
         break;

      case 'rss':
         $templateType = 'SEOBoost::RSS';
         break;

      default:
         // incorrect sitemap url - 404 error (from CMSMS index.php)
         throw new CmsError404Exception('sitemap name not recognised');
   }

   $tpl = CmsLayoutTemplate::load_dflt_by_type( $templateType );
   if( !is_object($tpl) ) {
      audit('', $this->GetName(), $this->Lang('template_no_default') );
      throw new CmsError404Exception( $this->Lang('template_no_default') );
   }
   $template = $tpl->get_name();

   // Then retrieve & display the template
   // Note: DoAction - would not work for Products - but would for News & Navigator
   // instead used slightly nasty $smarty->display('eval:'...) ... instead of:
   //    $mod = cms_utils::get_module('Products');
   //    $sitemap_params = $params;
   //    $sitemap_params['summarytemplate'] = $template;
   //    $mod->DoAction('default', '1', $sitemap_params, $sitemapPageId);
   switch ($sitemapName) {
      case 'index':
         CMSApp::get_instance()->set_content_type('text/xml');
         $tpl = $smarty->CreateTemplate( $this->GetTemplateResource($template), null, null, $smarty );
         $tpl->assign('items', $res);
         $tpl->display();
         break;

      case 'pages':
         CMSApp::get_instance()->set_content_type('text/xml');
         $smarty_data = "{Navigator template='".$template."'}";
         $smarty->display('eval:'.$smarty_data);
         break;

      case 'news':
         CMSApp::get_instance()->set_content_type('text/xml');
         $smarty_data = "{News summarytemplate='".$template."'}";
         $smarty->display('eval:'.$smarty_data);
         break;

      case 'products':
         CMSApp::get_instance()->set_content_type('text/xml');
         $smarty_data = "{Products summarytemplate='".$template."'}";
         $smarty->display('eval:'.$smarty_data);
         break;

      case 'lise':
         // needed to call LISE Instance from with the template as LISE doesn't use external templates
         CMSApp::get_instance()->set_content_type('text/xml');
         $smarty->assignGlobal('LISEModuleName', $sitemapModule);
         $tpl = $smarty->CreateTemplate( $this->GetTemplateResource($template), null, null, $smarty );
         $tpl->assign('items', $res);
         $tpl->display();
         break;

      case 'robots.txt':
         CMSApp::get_instance()->set_content_type('text/plain');
         $tpl = $smarty->CreateTemplate( $this->GetTemplateResource($template), null, null, $smarty );
         $tpl->display();
         break;

      case 'rss':
         CMSApp::get_instance()->set_content_type('text/xml');
         $smarty_data = "{News summarytemplate='".$template."'}";
         $smarty->display('eval:'.$smarty_data);
         break;

      default:
         // incorrect sitemap url - 404 error (from CMSMS index.php)
         throw new CmsError404Exception('sitemap name not recognised');

   } /* switch ($sitemapName) */

} catch (CmsError404Exception $e) {
   $page = 'error404';
   $showtemplate = true;
   unset($_REQUEST['mact']);
   unset($_REQUEST['module']);
   unset($_REQUEST['action']);
   $handlers = ob_list_handlers();
   for ($cnt = 0; $cnt < sizeof($handlers); $cnt++) { ob_end_clean(); }
   // specified page not found, load the 404 error page
   $contentops = \ContentOperations::get_instance();
   $contentobj = $contentops->LoadContentFromAlias('error404',true);
   if( is_object($contentobj) ) { // we have a 404 error page
      redirect( $contentobj->GetURL() );
      header("HTTP/1.0 404 Not Found");
      header("Status: 404 Not Found");

   } else {// no 404 error page
      @ob_end_clean();
      header("HTTP/1.0 404 Not Found");
      header("Status: 404 Not Found");
      echo '<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
         <html><head>
         <title>404 Not Found</title>
         </head><body>
         <h1>Not Found</h1>
         <p>The requested URL was not found on this server.</p>
         </body></html>';
      exit();
   }

} catch (Exception $e) {
   echo $smarty->errorConsole($e);
   exit();

}


