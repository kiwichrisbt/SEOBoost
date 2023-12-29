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
    const MODULE_VERSION = '1.4.2';

    const BULK_EDIT_CORE_FIELDS = [
            'core_content_name',     // these are in the content table
            'core_titleattribute',
            'core_menu_text',
            'core_content_alias'
    ];
    const DEFAULT_BULK_EDIT_FIELDS = [
        'field' => '',
        'editable' => 0,
        'size' => '',
        'length' => ''
    ];
    const DEFAULT_BULK_EDIT_FIELD_SIZE = 50;
    const BULK_EDIT_LAYOUTS = [
        'table',
        'stacked',
        'stacked_with_inline_titles',
        'stacked_with_title_column'
    ];
    const CM_ADD_IN_FIELDS = [
        'core_content_name' => [
            'editable' => false,
            'size' => 55,
            'length' => 55,
            'cm_field_name' => 'title'
        ],
        'core_titleattribute' => [
            'editable' => false,
            'size' => 155,
            'length' => 155,
            'cm_field_name' => 'titleattribute'
        ],
        'core_menu_text' => [
            'editable' => false,
            'size' => 50,
            'length' => 50,
            'cm_field_name' => 'menutext'
        ]
    ];

    private static $_seoboostSitemap = 0;
    private $_seoboostAdminJsCss = false;

    public function GetVersion() { return self::MODULE_VERSION; }
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
    public function GetHeaderHTML() { return $this->get_header_css_js(); }



    public function __construct() {
        parent::__construct();

        $smarty = \CmsApp::get_instance()->GetSmarty();
        if( !$smarty ) return;

        // register functions - put more complex functions in separate function.Name.php file
        $smarty->register_function('googleAnalytics', 'googleAnalytics');
        $smarty->register_function('pageVars', 'pageVars');
        $smarty->register_function('socialLinks', 'socialLinks');
        $smarty->register_function('socialShares', 'socialShares');
        
        \CMSMS\Hookmanager::add_hook('admin_add_headtext',[$this, 'AdminAddHeadText'],      
            \CMSMS\HookManager::PRIORITY_NORMAL);

    }




    public function InitializeAdmin()
    {
        parent::InitializeAdmin();

        if ( $this->CheckPermission(self::USE_PERM) ) {
            $this->RegisterBulkContentFunction($this->Lang('bulk_edit_content'), 'admin_bulk_edit_content');
        }
    }



    public function InitializeFrontend() {
        $this->RegisterModulePlugin();

        $this->SetParameterType('debug', CLEAN_INT);
        $this->SetParameterType('type',CLEAN_STRING);

        // set Hook to generate $pageVars smarty var before each page rendered
        \CMSMS\HookManager::add_hook('Core::ContentPreRender','SEOBoost::SetPageVarsHook');

    }



    /**
     * @return string $admin_css_js - add in admin css & js - only add when module=CMSContentManager
     */
    public function AdminAddHeadText() {
        $called_module = '';
        if (isset($_REQUEST['mact'])) {
            $ary = explode(',', cms_htmlentities($_REQUEST['mact']), 4);
            $called_module = (isset($ary[0])?$ary[0]:'');
        }

        if ( $called_module!='CMSContentManager' ) return;
        
        $admin_css_js = $this->get_header_css_js();

        // create js to add in fields to ContentManager
        $cm_add_in_fields_js = '';
        $cm_add_in_fields = $this->get_cm_add_in_fields();
        // set var seoboost_cm_addin_data to control what fields are added into ContentManager
        foreach ($cm_add_in_fields as $field_name => $field_settings) {
            if ($field_settings['editable']) {
                $cm_add_in_fields_js .= "
                    '$field_name' : {
                        'cm_field_name' : '".$field_settings['cm_field_name']."',
                        'size' : '".$field_settings['size']."',
                        'length' : '".$field_settings['length']."'
                    },";
            }
        }
        if (!empty($cm_add_in_fields_js)) {
            $admin_css_js .= "
            <script>
            var seoboost_cm_addin_data = {
                $cm_add_in_fields_js
            };
            </script>";
        }

        return $admin_css_js;
    }



    public function get_header_css_js() {
        if (cms_utils::get_app_data('SEOBoost_js_css_loaded')) return;
        $path = $this->GetModuleURLPath();
        $admin_css_js = '
            <link rel="stylesheet" type="text/css" href="'.$path.'/lib/css/seoboost_admin.css?v'.self::MODULE_VERSION.'">
            <script language="javascript" src="'.$path.'/lib/js/seoboost_admin.js?v'.self::MODULE_VERSION.'"></script>';
        cms_utils::set_app_data('SEOBoost_js_css_loaded', 1);

        return $admin_css_js;
    }
    


    public function HasCapability($capability, $params=[])
    {
        if ($capability=='bulkcontentoption') {
            return TRUE;
        }
        return FALSE;
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
            $name = strtolower(substr( $instance->module_name, 4 ));
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
      $route = new CmsRoute( '/^robots.txt$/', 'SEOBoost', $defaults );
      cms_route_manager::add_static($route);
      // feed-news.rss
      $route = new CmsRoute( '/^feeds-news.rss$/', 'SEOBoost', $defaults );
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



    /**
     * @return array $bulk_edit_fields - load from module preference - and ensure correct format
     */
    public function get_bulk_edit_fields()
    {
        $tmp_bulk_edit_fields = json_decode($this->GetPreference('bulkEditFields', ''), true);
        // if (empty($tmp_bulk_edit_fields)) return [self::DEFAULT_BULK_EDIT_FIELDS];
        if (empty($tmp_bulk_edit_fields)) return [];

        $bulk_edit_fields = [];
        foreach ($tmp_bulk_edit_fields as $row_id => $row_values) {
            foreach (self::DEFAULT_BULK_EDIT_FIELDS as $key => $default_value) {
                $bulk_edit_fields[$row_id][$key] = $row_values[$key] ?? $default_value;
            }
            if (isset($bulk_edit_fields[$row_id])) {
                $bulk_edit_fields[$row_id]['is_core_field'] = in_array($bulk_edit_fields[$row_id]['field'], self::BULK_EDIT_CORE_FIELDS);
            }
        }
        return array_values($bulk_edit_fields); // re-index array numerically
    }



    /**
     * @param array $bulk_edit_fields - save as module preference
     */
    public function set_bulk_edit_fields($bulk_edit_fields) 
    {
        // save valid fields only & 'field' must be set
        $edit_fields = [];
        foreach ($bulk_edit_fields as $row_id => $row_values) {
            if (empty($row_values['field'])) continue;
            foreach (self::DEFAULT_BULK_EDIT_FIELDS as $key => $default_value) {
                $edit_fields[$row_id][$key] = $row_values[$key] ?? $default_value;
            }
        }
        $json_bulk_edit_fields = json_encode($edit_fields, JSON_NUMERIC_CHECK | JSON_PRESERVE_ZERO_FRACTION);
        $this->SetPreference('bulkEditFields', $json_bulk_edit_fields);
    }



    /**
     * @return array $cm_add_in_fields - load from module preference - and ensure correct format
     */
    public function get_cm_add_in_fields()
    {
        $tmp_cm_add_in_fields = json_decode($this->GetPreference('cmAddInFields', ''), true);
        if (empty($tmp_cm_add_in_fields)) return self::CM_ADD_IN_FIELDS;

        $cm_add_in_fields = [];
        foreach (self::CM_ADD_IN_FIELDS as $field_name => $field_settings) {
            foreach ($field_settings as $setting => $default_value) {
                $cm_add_in_fields[$field_name][$setting] = $tmp_cm_add_in_fields[$field_name][$setting] ?? $default_value;
            }
        }
        return $cm_add_in_fields;
    }



    /**
     *  @param array $cm_add_in_fields - save as module preference
     *      save valid fields only & 'field' must be set
     */
    public function set_cm_add_in_fields($cm_add_in_fields) 
    {
        $add_in_fields = [];
        foreach (self::CM_ADD_IN_FIELDS as $field_name => $field_settings) {
            foreach ($field_settings as $setting => $default_value) {
                $add_in_fields[$field_name][$setting] = $cm_add_in_fields[$field_name][$setting] ?? $default_value;
            }
        }
        $json_add_in_fields = json_encode($add_in_fields, JSON_NUMERIC_CHECK | JSON_PRESERVE_ZERO_FRACTION);
        $this->SetPreference('cmAddInFields', $json_add_in_fields);
    }



    public static function page_type_lang_callback($str) 
    {
        $mod = cms_utils::get_module('SEOBoost');
        if( is_object($mod) ) return $mod->Lang('type_'.$str);
    }



    public static function reset_page_type_defaults(CmsLayoutTemplateType $type) 
    {
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