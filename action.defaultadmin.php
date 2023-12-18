<?php
#---------------------------------------------------------------------------------------------------
# Module: SEOBoost
# Author: Chris Taylor
# Copyright: (C) 2019 Chris Taylor, chris@binnovative.co.uk
# Module's homepage is: http://dev.cmsmadesimple.org/projects/seoboost
# Licence: GNU General Public License version 3
#          see /SEOBoost/lang/LICENCE.txt or <http://www.gnu.org/licenses/>
#---------------------------------------------------------------------------------------------------

if ( !defined('CMS_VERSION') ) exit;

if( !$this->CheckPermission(SEOBoost::USE_PERM) ) {
   $this->ShowErrors( $this->Lang('need_permission') );
   return;
}

$adminTheme = cms_utils::get_theme_object();


// Create Admin tabs
echo $this->StartTabHeaders();
echo $this->SetTabHeader("company_info",$this->Lang("company_info"));
echo $this->SetTabHeader("social",$this->Lang("social"));
echo $this->SetTabHeader("sitemaps",$this->Lang("sitemaps"));
echo $this->SetTabHeader("options",$this->Lang("tab_options"));
echo $this->EndTabHeaders();


// create Tab content
echo $this->StartTabContent();


   echo $this->StartTab("company_info");
      $tpl = $smarty->CreateTemplate( $this->GetTemplateResource('admin_company_info.tpl'), null, null, $smarty );
      $settings = [
         'phoneNumber' => 20,
         'mobileNumber' => 20,
         'email' => 40,
         'fax' => 40,
         'fullBusinessName' => 50,
         'street1' => 50,
         'street2' => 50,
         'city' => 50,
         'region' => 30,
         'postcode' => 10,
         'country' => 30,
         'businessType' => 30,
         'latitude_longitude' => 30,
         'geo_region' => 7,   
         'copyright_start' => 4,
         'ga_code' => 20,
         'ga_gtag_id' => 20
      ];

      // load all settings
      $query = new SEOBoostSettingQuery;
      $res = $query->GetMatches();
      if ($res) {
         foreach ($res as $aSetting) {
            $allSettings[$aSetting->name] = $aSetting->value;
         }
      }
      foreach ($settings as $setting => $size) {
         $value = $allSettings[$setting];
         $tpl->assign( 'title_'.$setting, $this->Lang('title_'.$setting) );
         $tpl->assign( 'input_'.$setting, $this->CreateInputText($id, 'input_'.$setting, $value , $size, 255) );
         $tpl->assign( 'info_'.$setting, $this->Lang('info_'.$setting) );
      }
      $tpl->assign('startform', $this->CreateFormStart( $id, 'admin_save_company_info', $returnid, 'post', 'multipart/form-data'));
      $tpl->assign('endform', $this->CreateFormEnd());
      $tpl->display();
   echo $this->EndTab();


   echo $this->StartTab("social");
      $tpl = $smarty->CreateTemplate( $this->GetTemplateResource('admin_social.tpl'), null, null, $smarty );
      $fields = [
         'twitter' => 50,
         'facebook' => 50,
         'linkedin' => 50,
         'instagram' => 50
      ];
      if ($res) {
         foreach ($res as $field) {
            $social[$field->name] = $field->value;
         }
      }
      foreach ($fields as $field => $size) {
         $tpl->assign( 'title_'.$field, $this->Lang('title_'.$field) );
         $social_value = isset($social[$field]) ? $social[$field] : ''; 
         $tpl->assign( 'input_'.$field, $this->CreateInputText($id, 'input_'.$field, $social_value , $size, 255));
         $tpl->assign( 'info_'.$field, $this->Lang('info_'.$field) );
      }
      $tpl->assign('startform', $this->CreateFormStart( $id, 'admin_save_social', $returnid, 'post', 'multipart/form-data'));
      $tpl->assign('endform', $this->CreateFormEnd());
      $tpl->display();
   echo $this->EndTab();


   echo $this->StartTab("sitemaps");
      $this->refresh_sitemaps(); // refreshes sitemaps table
      $sitemap_errors = $this->get_sitemap_errors();
      $query = new SEOBoostSitemapQuery;
      $sitemaps = $query->GetMatches();
      $tpl = $smarty->CreateTemplate( $this->GetTemplateResource('admin_sitemaps.tpl'), null, null, $smarty );
      $tpl->assign('sitemap_errors', $sitemap_errors);
      $tpl->assign('sitemaps', $sitemaps);
      $tpl->assign('startform', $this->CreateFormStart( $id, 'admin_save_sitemaps', $returnid, 'post', 'multipart/form-data'));
      $tpl->assign('endform', $this->CreateFormEnd());
      $tpl->display();
   echo $this->EndTab();



    echo $this->StartTab("options");
        $tpl = $smarty->CreateTemplate( $this->GetTemplateResource('admin_options.tpl'), null, null, $smarty );
        $tpl->assign('bulk_edit_core_fields', $this::BULK_EDIT_CORE_FIELDS);
        $db = \cms_utils::get_db();
        $sql = 'SELECT prop_name FROM '.CMS_DB_PREFIX.'content_props GROUP BY prop_name ORDER BY prop_name';
        $custom_fields = $db->GetCol($sql);
        $tpl->assign('bulk_edit_custom_fields', $custom_fields);

        $tpl->assign('title_customModuleName', $this->Lang('title_customModuleName'));
        $tpl->assign('input_customModuleName', $this->CreateInputText($id, 'input_customModuleName',$this->GetPreference('customModuleName',''),50,255));
        $tpl->assign('title_adminSection', $this->Lang('title_adminSection'));
        $tpl->assign('input_adminSection', $this->CreateInputDropdown($id, 'input_adminSection',
            array(lang('main') => 'main',
                lang('content') => 'content',
                lang('layout') => 'layout',
                lang('usersgroups') => 'usersgroups',
                lang('extensions') => 'extensions',
                lang('admin') => 'siteadmin',
                lang('myprefs') => 'myprefs'),
            -1,
            $this->GetPreference('adminSection', 'content')
        ));


        $tpl->assign( 'title_useSearchable', $this->Lang('title_useSearchable') );
        $tpl->assign( 'input_useSearchable', $this->CreateInputCheckbox($id, 'input_useSearchable', true, $this->GetPreference('useSearchable', true)) );
        $tpl->assign( 'info_useSearchable', $this->Lang('info_useSearchable') );

        $tpl->assign( 'bulk_edit_fields', $this->get_bulk_edit_fields() );
        $tpl->assign( 'cm_add_in_fields', $this->get_cm_add_in_fields() );
        
        $tpl->display();
    echo $this->EndTab();


echo $this->EndTabContent();


