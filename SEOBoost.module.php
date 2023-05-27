<?php
#---------------------------------------------------------------------------------------------------
# Module: SEOBoost
# Author: Chris Taylor
# Copyright: (C) 2019 Chris Taylor, chris@binnovative.co.uk
# Module's homepage is: http://dev.cmsmadesimple.org/projects/seoboost
# Licence: GNU General Public License version 3
#          see /SEOBoost/lang/LICENCE.txt or <http://www.gnu.org/licenses/>
#---------------------------------------------------------------------------------------------------
# CMS - CMS Made Simple is (c) 2019 by CMS Made Simple Foundation (copyright@cmsmadesimple.org)
# Project's homepage is: http://www.cmsmadesimple.org
#---------------------------------------------------------------------------------------------------
# This program is free software; you can redistribute it and/or modify it under the terms of the
# GNU General Public License as published by the Free Software Foundation; either version 3
# of the License, or (at your option) any later version.
#
# This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
# without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
# See the GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License along with this program.
# If not, see <http://www.gnu.org/licenses/>.
#---------------------------------------------------------------------------------------------------


// put more complex functions in separate function.Name.php file - but require here:
$fn = cms_join_path(__DIR__,'function.googleAnalytics.php'); require_once($fn);
$fn = cms_join_path(__DIR__,'function.pageVars.php'); require_once($fn);
$fn = cms_join_path(__DIR__,'function.social.php'); require_once($fn);



class SEOBoost extends \CMSModule {

   const USE_PERM = 'use_seoboost';

   private static $_seoboostSitemap = 0;

   public function GetVersion() { return '1.1'; }
   public function GetFriendlyName() { return $this->GetPreference('customModuleName', 'SEOBoost'); }
   public function GetAdminDescription() { return $this->Lang('admindescription'); }
   public function IsPluginModule() { return TRUE; }
   public function HasAdmin() { return TRUE; }
   public function GetAdminSection() { return $this->GetPreference('adminSection', 'content'); }
   public function VisibleToAdminUser() { return ( $this->CheckPermission(self::USE_PERM) ); }
   public function GetHelp() { return $this->Lang('help'); }
   public function GetAuthor() { return 'Chris Taylor'; }
   public function GetAuthorEmail() { return 'chris@binnovative.co.uk'; }
   public function GetChangeLog() { return $this->Lang('changelog'); }
   public function MinimumCMSVersion() { return '2.2'; }
   public function InstallPostMessage() { return $this->Lang('install_msg'); }
   public function UninstallPreMessage() { return $this->Lang('ask_uninstall'); }



   public function __construct() {
      parent::__construct();

      $smarty = \CmsApp::get_instance()->GetSmarty();
      if( !$smarty ) return;

      // register functions - put more complex functions in separate function.Name.php file
      $smarty->register_function('googleAnalytics', 'googleAnalytics');
      $smarty->register_function('pageVars', 'pageVars');
      $smarty->register_function('socialLinks', 'socialLinks');
      $smarty->register_function('socialShares', 'socialShares');

   }



   public function InitializeFrontend() {
      $this->RegisterModulePlugin();

      $this->SetParameterType('debug', CLEAN_INT);
      $this->SetParameterType('type',CLEAN_STRING);

      // set Hook to generate $pageVars smarty var before each page rendered
      \CMSMS\HookManager::add_hook('Core::ContentPreRender','SEOBoost::SetPageVarsHook');

   }



   public static function OutputSitemap ($params) {
   //***********************************************************************************************
   //
   //***********************************************************************************************
      if ( !isset($params['content']) ) return;

      $params['content'] =  '';  // remove default content

      $mod = cms_utils::get_module('SEOBoost');
      if( is_object($mod) ) {
          $tmp = $mod->DoAction('sitemap', 'SEOBoost', $params);
      }
   }


   public static function SetPageVarsHook ($params) {
   //***********************************************************************************************
   //
   //***********************************************************************************************
      SetPageVars($params);
   }



   public function refresh_sitemaps() {
   //***********************************************************************************************
   //
   //***********************************************************************************************

      $supported_sitemaps = [ // name [module, url, default_active]
         'index' => ['SEOBoost', 'sitemap.xml', 1],
         'pages' => ['Navigator', 'sitemap-pages.xml', 1],
         'news' => ['News', 'sitemap-news.xml', 1],
         'products' => ['Products', 'sitemap-products.xml' , 1],
         'robots.txt' => ['other', 'robots.txt', 1],
         'rss' => ['other', 'feeds-news.rss', 1]
      ];
      // also add LISE instances
      $LISE = cms_utils::get_module('LISE');
      if ( is_object($LISE) ) $instances = $LISE->ListModules();
      if ( empty($instances) ) { // or LISE placeholder
         $supported_sitemaps['lise (no instances)'] = ['LISE', '', 0];
      } else {
         foreach ($instances as $instance) { // LISE Instances
            $name = strtolower(ltrim( $instance->module_name, 'LISE' ));
            $url = "sitemap-lise-$name.xml";
            $supported_sitemaps[$name] = [$instance->module_name, $url, 0];
         }
      }
      // get all current sitemaps and compared to $supported_sitemaps
      $query = new SEOBoostSitemapQuery;
      $sitemaps = $query->GetMatches();
      if (!empty($sitemaps)) {
         foreach ($sitemaps as $sitemap) {
            if ( array_key_exists($sitemap->name, $supported_sitemaps) ) {
               unset( $supported_sitemaps[$sitemap->name] ); // remove sitemap - don't add it again
               // set inactive if module not installed
               $moduleInstalled = is_object( cms_utils::get_module($sitemap->module) );
               if ( $sitemap->module!='other' && !$moduleInstalled && $sitemap->active ) {
                  $sitemap->toggle_active();
               }
               if ($sitemap->name=='rss' && is_object( !cms_utils::get_module('News')) && $sitemap->active) {
                  $sitemap->toggle_active();
               }

            } else {
               $sitemap->delete();   // delete unsupported sitemap (e.g. removed LISE Instance)
            }
         }
      }
      // add in all $supported_sitemaps not already in $sitemaps
      foreach ($supported_sitemaps as $name => $options) {
         $sitemap = new SEOBoostSitemap();
         $sitemap->name = $name;
         $sitemap->module = $options[0];
         $sitemap->url = $options[1];
         $mod = cms_utils::get_module( $sitemap->module );
         $moduleInstalled = is_object( cms_utils::get_module($sitemap->module) );
         $isLISE = ( substr($sitemap->module, 0, 4)=='LISE' );
         $sitemap->active = ($moduleInstalled && !$isLISE);
         if ($sitemap->name=='robots.txt') $sitemap->active = true;
         if ($sitemap->name=='rss') $sitemap->active = is_object( cms_utils::get_module('News'));
         $sitemap->save();
      }

   }



   public function CreateStaticRoutes() {
   //***********************************************************************************************
   //    set & forget all possible static routes
   //***********************************************************************************************
      cms_route_manager::del_static('',$this->GetName());  // remove all modules routes

      $contentOps = \ContentOperations::get_instance();
      $defaultPageId = $contentOps->GetDefaultContent(); // need any valid page id
      $defaults = array( 'action'=>'sitemap', 'returnid'=>$defaultPageId);
      // xml sitemaps
      $route = new CmsRoute( '/^sitemap-*[0-9a-zA-Z-_]*\.xml$/', 'SEOBoost', $defaults );
      cms_route_manager::add_static($route);
      // robots.txt
      $route = new CmsRoute( 'robots.txt', 'SEOBoost', $defaults );
      cms_route_manager::add_static($route);
      // feed-news.rss
      $route = new CmsRoute( 'feeds-news.rss', 'SEOBoost', $defaults );
      cms_route_manager::add_static($route);
   }



   public function get_sitemap_errors() {
   //***********************************************************************************************
   //
   //***********************************************************************************************
      $err = null;
      $query = new SEOBoostSitemapQuery;
      $sitemaps = $query->GetMatches();
      if (!empty($sitemaps)) {
         foreach ($sitemaps as $sitemap) {
            $route = cms_route_manager::find_match($sitemap->url);
            if ( is_object($route) && $route->get_dest()!='SEOBoost' ) {
               $err[] = 'url: '.$sitemap->url.' already exists ('.$route->get_dest().')';
            }
            if ( $sitemap->url!='' && file_exists(CMS_ROOT_PATH.'/'.$sitemap->url) ) {
               $err[] = 'file: '.$sitemap->url.' already exists ';
            }
         }
      }
      return $err;
   }



   public static function page_type_lang_callback($str) {
   //***********************************************************************************************
   //
   //***********************************************************************************************
      $mod = cms_utils::get_module('SEOBoost');
      if( is_object($mod) ) return $mod->Lang('type_'.$str);
   }



   public static function reset_page_type_defaults(CmsLayoutTemplateType $type) {
   //***********************************************************************************************
   //
   //***********************************************************************************************
      if( $type->get_originator()!='SEOBoost' )
         throw new CmsLogicException('Cannot reset contents for this template type');

      $fn = null;
      switch ( $type->get_name() ) {
         case 'Sitemap':
            $fn = 'SEOBoost_sitemap.tpl';
            break;
         case 'Robots_txt':
            $fn = 'SEOBoost_robots_txt.tpl';
            break;
         case 'RSS':
            $fn = 'SEOBoost_rss.tpl';
            break;
         case 'Metadata':
            $fn = 'SEOBoost_metadata.tpl';
            break;
      }
      if ( !is_null($fn) ) {
         $fn = cms_join_path(__DIR__,'templates',$fn);
         if( file_exists($fn) ) return @file_get_contents($fn);
      }
   }



}