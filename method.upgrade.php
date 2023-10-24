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

// first get userid
$uid = null;
if( cmsms()->test_state(CmsApp::STATE_INSTALL) ) {
   $uid = 1; // hardcode to first user
} else {
   $uid = get_userid();
}

if ( version_compare($oldversion,'0.8.6beta') < 0 ) {
   // remove SEOBoost_blank template & page
   $contentOps = \ContentOperations::get_instance();
   try {
      $blankPage = $contentOps->LoadContentFromAlias('SEOBoost_blank');
      if ( is_object($blankPage) ) $blankPage->Delete();
      $blankTemplate = CmsLayoutTemplate::load('SEOBoost_blank');
      if ( is_object($blankTemplate) ) $blankTemplate->Delete();
   } catch( CmsException $e ) {
      debug_to_log(__FILE__.':'.__LINE__.' '.$e->GetMessage());
      audit('',$this->GetName(),'Upgrade Error: '.$e->GetMessage());
   }

   // update preferences
   $this->RemovePreference('sitemap_page');
   $this->SetPreference('useSearchable', true);

   // and update routes - use default page content
   $this->CreateStaticRoutes();

   // Setup 'SEOBoost_metadata' template & 'metadata' template type
   try { // Setup 'metadata' template type
      $template_type = new CmsLayoutTemplateType();
      $template_type->set_originator($this->GetName());
      $template_type->set_name('Metadata');
      $template_type->set_dflt_flag(TRUE);
      $template_type->set_lang_callback('SEOBoost::page_type_lang_callback');
      $template_type->set_content_callback('SEOBoost::reset_page_type_defaults');
      $template_type->reset_content_to_factory();
      $template_type->save();
   } catch( CmsException $e ) {
      debug_to_log(__FILE__.':'.__LINE__.' '.$e->GetMessage());
      audit('',$this->GetName(),'Upgrade Error: '.$e->GetMessage());
   }
   try { // Setup 'SEOBoost_metadata' template default:
      $fn = cms_join_path(__DIR__,'templates','SEOBoost_metadata.tpl');
      if( file_exists( $fn ) ) {
         $template = @file_get_contents($fn);
         $tpl = new CmsLayoutTemplate();
         $tpl->set_name('SEOBoost_metadata');
         $tpl->set_owner($uid);
         $tpl->set_content($template);
         $tpl->set_type($template_type);
         $tpl->set_type_dflt(TRUE);
         $tpl->save();
      }
   } catch( CmsException $e ) {
      debug_to_log(__FILE__.':'.__LINE__.' '.$e->GetMessage());
      audit('',$this->GetName(),'Upgrade Error: '.$e->GetMessage());
   }
}


if( version_compare($oldversion,'0.9.3beta') < 0 ) {    // pre 0.9.3beta
    // Create fields - newSettings: name, type, CustomGS field (if it exists), default value
    $newSettings = [
        'instagram' => ['pagevar', 'Instagram', '']
    ];
    foreach ($newSettings as $name => $options) {
        $setting = new SEOBoostSetting();
        $setting->name = $name;
        $setting->type = $options[0];
        $setting->value = $options[2]; // default value
        $setting->save();
    }
}


if ( version_compare($oldversion,'1.3') < 0 ) {    // pre 1.3
    // Create fields - newSettings: name, type, CustomGS field (if it exists), default value
    $newSettings = [
        'ga_gtag_id' => ['pagevar', 'Google Analytics Gtag Measurement ID', '']
    ];
    foreach ($newSettings as $name => $options) {
        $setting = new SEOBoostSetting();
        $setting->name = $name;
        $setting->type = $options[0];
        $setting->value = $options[2]; // default value
        $setting->save();
    }
}

if ( version_compare($oldversion,'1.3.3') < 0 ) {    // pre 1.3
    // Create fields - newSettings: name, type, CustomGS field (if it exists), default value
    $newSettings = [
        'geo_region' => ['pagevar', 'geo.region', '']
    ];
    foreach ($newSettings as $name => $options) {
        $setting = new SEOBoostSetting();
        $setting->name = $name;
        $setting->type = $options[0];
        $setting->value = $options[2]; // default value
        $setting->save();
    }

    try { 
        // Reset default 'Metadata' template
        $template_type = new CmsLayoutTemplateType();
        $metadata_template_type = $template_type->load('SEOBoost::Metadata');
        $old_default_contents = $metadata_template_type->get_dflt_contents();
        $metadata_template_type->reset_content_to_factory(); // get latest from module
        $metadata_template_type->save();
        $new_default_contents = $metadata_template_type->get_dflt_contents();

        // update any templates that were unmodified old default templates
        $tpl = new CmsLayoutTemplate();
        $all_metadata_templates = $tpl->load_all_by_type($metadata_template_type);
        foreach ($all_metadata_templates as $metadata_template) {
            if ($metadata_template->get_content() == $old_default_contents) {
                $metadata_template->set_content($new_default_contents);
                $metadata_template->save();

            }
        }

    } catch( CmsException $e ) {
    debug_to_log(__FILE__.':'.__LINE__.' '.$e->GetMessage());
    audit('',$this->GetName(),'Upgrade error - unable to update Metadata template: '.$e->GetMessage());
    }

}
