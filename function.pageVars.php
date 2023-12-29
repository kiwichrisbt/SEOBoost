<?php
#---------------------------------------------------------------------------------------------------
# Module: SEOBoost
# Author: Chris Taylor
# Copyright: (C) 2019 Chris Taylor, chris@binnovative.co.uk
# Module's homepage is: http://dev.cmsmadesimple.org/projects/seoboost
# Licence: GNU General Public License version 3
#          see /SEOBoost/lang/LICENCE.txt or <http://www.gnu.org/licenses/>
#---------------------------------------------------------------------------------------------------



function pageVars ($params, $smarty) {
//***********************************************************************************************
//
//***********************************************************************************************
   return;
}



function SetPageVars ($params) {
//**************************************************************************************************
//
//**************************************************************************************************
   $smarty = \CmsApp::get_instance()->GetSmarty();
   $contentops = \ContentOperations::get_instance();
   $mod = cms_utils::get_module('SEOBoost');

   // check for valid current 'content' object
   if ( empty($params['content']) || !is_object($params['content'] ) ) {
      $error = "404 Error (SEOBoost)";
      return $error;
   }

   // get details from current page and site settings
   $curpage = $params['content'];
   $pageVars['pageTitle'] = cms_htmlentities( $curpage->Name() );
   $pageVars['siteName'] = html_entity_decode( cms_siteprefs::get('sitename','') );
   $pageVars['pageDescription'] = cms_htmlentities( $curpage->TitleAttribute() );
   if (!(isset($config["use_smarty_php_tags"]) && $config["use_smarty_php_tags"] == true)) {
      $pageVars['pageTitle'] = preg_replace("/\{\/?php\}/", "", $pageVars['pageTitle']);
      $pageVars['pageDescription'] = preg_replace("/\{\/?php\}/", "", $pageVars['pageDescription']);
   }
   $pageVars['pageURL'] = $curpage->GetURL();
   
    $page_image = $curpage->GetPropertyValue('image');
    if ( !empty($page_image) ) {
        $cmsms_config = CmsApp::get_instance()->GetConfig();
        $images_dir = !empty($config['image_uploads_url']) ? $config['image_uploads_url'] : 'images';
        $pageVars['pageImage'] = $cmsms_config->smart_uploads_url().DIRECTORY_SEPARATOR.$images_dir.
            DIRECTORY_SEPARATOR.$curpage->GetPropertyValue('image');
    }

   if ( is_object($mod) && $mod->GetPreference('useSearchable', true) ) {
      $pageVars['searchable'] = $curpage->getPropertyValue('searchable');
   }

   // create keyword1 & keyword2
   $keywordDelimiter = "|";
   $demilimterPos = strpos($pageVars['pageTitle'], $keywordDelimiter);
   if ( $demilimterPos===FALSE ) {
      $pageVars['keyword1'] = $pageVars['pageTitle'];
      $pageVars['keyword2'] = $pageVars['pageTitle'];
   } else {
      $pageVars['keyword1'] = trim( substr($pageVars['pageTitle'], 0, $demilimterPos) );
      $pageVars['keyword2'] = trim( substr($pageVars['pageTitle'], $demilimterPos+1) );
   }

   // append site to page title if specified
   if ( isset($params['addsitename']) ) {
      $pageVars['pageTitle'] .= " - ".$pageVars['siteName'];
   }

   // get home page keywords
   $defaultid = $contentops->GetDefaultPageID();
   $defaultpage = $contentops->LoadContentFromId( $contentops->GetDefaultPageID() );
   $pageVars['homePageKeywords'] = $defaultpage->Name();
   $pageVars['homePageKeywords'] = str_replace("|", ",", str_replace(" |", ",", $pageVars['homePageKeywords']));

   // load all settings from db
   $query = new SEOBoostSettingQuery;
   $res = $query->GetMatches();
   if ($res) {
      foreach ($res as $aSetting) {
         $pageVars[$aSetting->name] = $aSetting->value;
      }
   }

   // add copyright - get start date if set - or just use current year
   if ( empty($pageVars['copyright_start']) || (date('Y')==$pageVars['copyright_start']) ) {
      $pageVars['copyright'] = date('Y');
   } else {
      $pageVars['copyright'] = $pageVars['copyright_start'].'-'.date('Y');
   }

   // split latitude & longitude
   if ( !empty($pageVars['latitude_longitude']) ) {
      $latLong = explode(',', $pageVars['latitude_longitude']);
      if ( count($latLong)==2 ) {
         $pageVars['latitude'] = $latLong[0];
         $pageVars['longitude'] = $latLong[1];
      }
   }

   // set alias
   $pageVars['county'] = $pageVars['region'];

   // create smarty template, vars & output
   if( is_object($mod) ) {
      // this template creates a lot of additional pageVar entries scope=global
      $tpl = $smarty->CreateTemplate( $mod->GetTemplateResource('pageVars_template.tpl'), null, null, $smarty );
      $tpl->assign('pageVars', $pageVars);
      $tpl->display();
   }

}


