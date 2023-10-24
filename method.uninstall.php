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

// unregister all functions
$smarty->unregister_function('googleAnalytics');
$smarty->unregister_function('pageVars');
$smarty->unregister_function('socialLinks');
$smarty->unregister_function('socialShares');


// remove the permissions
$this->RemovePermission(SEOBoost::USE_PERM);


// remove the database tables
$dict = NewDataDictionary( $db );
$sqlarray = $dict->DropTableSQL( CMS_DB_PREFIX.'module_seoboost_settings');
$dict->ExecuteSQLArray($sqlarray);
$sqlarray = $dict->DropTableSQL( CMS_DB_PREFIX.'module_seoboost_sitemaps');
$dict->ExecuteSQLArray($sqlarray);


// remove all preferences
$this->RemovePreference();


// and routes...
cms_route_manager::del_static('',$this->GetName());


// remove templates and template types
try {
   $types = CmsLayoutTemplateType::load_all_by_originator($this->GetName());
   if( is_array($types) && count($types) ) {
      foreach( $types as $type ) {
         $templates = $type->get_template_list();
         if( is_array($templates) && count($templates) ) {
            foreach( $templates as $template ) {
               $template->delete();
            }
         }
         $type->delete();
      }
   }
} catch( Exception $e ) {
   audit('',$this->GetName(),'Uninstall Error: '.$e->GetMessage());
}


// remove SEOBoost_blank template & page
$contentOps = \ContentOperations::get_instance();
try {
   $blankPage = $contentOps->LoadContentFromAlias('SEOBoost_blank');
   if ( is_object($blankPage) ) $blankPage->Delete();
   $blankTemplate = CmsLayoutTemplate::load('SEOBoost_blank');
   if ( is_object($blankTemplate) ) $blankTemplate->Delete();
} catch( CmsException $e ) {
   debug_to_log(__FILE__.':'.__LINE__.' '.$e->GetMessage());
   audit('',$this->GetName(),'Installation Error: '.$e->GetMessage());
}


