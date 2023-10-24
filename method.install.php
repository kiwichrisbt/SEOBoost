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

// a test for another (private) module that is incompatible
$mod = cms_utils::get_module('BIExtensions');
if ( is_object($mod) ) {
   // fail install & return message
   return $this->lang('install_BIExtensions_error');
}

// Setup Module Permissions
$this->CreatePermission(SEOBoost::USE_PERM,'SEOBoost - Use');

// Set Preferences
$this->SetPreference('customModuleName', 'SEOBoost');
$this->SetPreference('adminSection', 'content');
$this->SetPreference('useSearchable', true);

// Create Tables
$db = $this->GetDb();
$dict = NewDataDictionary($db);
$taboptarray = array('mysql' => 'TYPE=MyISAM');

// create SEOBoost settings table
$fields = "
   id I KEY AUTO,
   name C(255) KEY NOTNULL,
   type C(255),
   value C(255)
   ";
$sqlarray = $dict->CreateTableSQL(CMS_DB_PREFIX.'module_seoboost_settings', $fields, $taboptarray);
$dict->ExecuteSQLArray($sqlarray);
// Create fields - allSettings: name, type, CustomGS field (if it exists), default value
$allSettings = [
   'phoneNumber' => ['pagevar', 'Phone Number', ''],
   'mobileNumber' => ['pagevar', 'Mobile Number', ''],
   'email' => ['pagevar', 'Email Address', ''],
   'fax' => ['pagevar', '', ''],
   'twitter' => ['pagevar', 'Twitter', ''],
   'facebook' => ['pagevar', 'Facebook', ''],
   'linkedin' => ['pagevar', 'LinkedIn', ''],
   'fullBusinessName' => ['pagevar', 'Full Business Name', html_entity_decode(cms_siteprefs::get('sitename',''))],
   'street1' => ['pagevar', 'Street Address', ''],
   'street2' => ['pagevar', '', ''],
   'city' => ['pagevar', 'City or Town', ''],
   'region' => ['pagevar', 'County', ''],
   'postcode' => ['pagevar', 'Postcode', ''],
   'country' => ['pagevar', 'Country', ''],
   'businessType' => ['pagevar', 'Business Type', 'LocalBusiness'],
   'latitude_longitude' => ['pagevar', 'Latitude-Longitude', ''],
   'geo_region' => ['pagevar', 'geo.region', ''],   
   'copyright_start' => ['pagevar', 'Copyright Start Year', ''],
   'ga_code' => ['pagevar', 'Google Analytics Account Code', ''],
   'ga_gtag_id' => ['pagevar', 'Google Analytics Gtag Measurement ID', '']
];
$CustomGS = cms_utils::get_module('CustomGS');
foreach ($allSettings as $name => $options) {
   $setting = new SEOBoostSetting();
   $setting->name = $name;
   $setting->type = $options[0];
   $setting->value = $options[2]; // default value
   if ( is_object($CustomGS) && !empty($options[1]) ) {
      $tmp = $CustomGS->GetField($options[1]);
      if ( !empty($tmp['value']) ) {
         $setting->value = $tmp['value'];
      }
   }
   $setting->save();
}
// split into separate streets if '|' was used
$street1 = SEOBoostSetting::load_by_name('street1');
if ( is_object($street1) && $street1->value!='' ) {
   $streets = explode('|', $street1->value, 2);
   if ( count($streets)>1 ) {
      $street1->value = $streets[0];
      $street1->save();
      $street2 = SEOBoostSetting::load_by_name('street2');
      $street2->value = $streets[1];
      $street2->save();
   }
}

// create SEOBoost sitemaps table
$fields = "
   id I KEY AUTO,
   name C(255) KEY NOTNULL,
   module C(255),
   url C(255),
   active I1
   ";
$sqlarray = $dict->CreateTableSQL(CMS_DB_PREFIX.'module_seoboost_sitemaps', $fields, $taboptarray);
$dict->ExecuteSQLArray($sqlarray);

$this->refresh_sitemaps(); // loads sitemaps table & sets static routes


// Create SEOBoost Templates
// first get userid
$uid = null;
if( cmsms()->test_state(CmsApp::STATE_INSTALL) ) {
   $uid = 1; // hardcode to first user
} else {
   $uid = get_userid();
}

try { // Setup 'Sitemap' template type
   $template_type = new CmsLayoutTemplateType();
   $template_type->set_originator($this->GetName());
   $template_type->set_name('Sitemap');
   $template_type->set_dflt_flag(TRUE);
   $template_type->set_lang_callback('SEOBoost::page_type_lang_callback');
   $template_type->set_content_callback('SEOBoost::reset_page_type_defaults');
   $template_type->reset_content_to_factory();
   $template_type->save();
} catch( CmsException $e ) {
   debug_to_log(__FILE__.':'.__LINE__.' '.$e->GetMessage());
   audit('',$this->GetName(),'Installation Error: '.$e->GetMessage());
}
try { // Setup 'Sitemap' template default:
   $fn = cms_join_path(__DIR__,'templates','SEOBoost_sitemap.tpl');
   if( file_exists( $fn ) ) {
      $template = @file_get_contents($fn);
      $tpl = new CmsLayoutTemplate();
      $tpl->set_name('SEOBoost_sitemap');
      $tpl->set_owner($uid);
      $tpl->set_content($template);
      $tpl->set_type($template_type);
      $tpl->set_type_dflt(TRUE);
      $tpl->save();
   }
} catch( CmsException $e ) {
   debug_to_log(__FILE__.':'.__LINE__.' '.$e->GetMessage());
   audit('',$this->GetName(),'Installation Error: '.$e->GetMessage());
}

try { // Setup 'Robots_txt' template type
   $template_type = new CmsLayoutTemplateType();
   $template_type->set_originator($this->GetName());
   $template_type->set_name('Robots_txt');
   $template_type->set_dflt_flag(TRUE);
   $template_type->set_lang_callback('SEOBoost::page_type_lang_callback');
   $template_type->set_content_callback('SEOBoost::reset_page_type_defaults');
   $template_type->reset_content_to_factory();
   $template_type->save();
} catch( CmsException $e ) {
   debug_to_log(__FILE__.':'.__LINE__.' '.$e->GetMessage());
   audit('',$this->GetName(),'Installation Error: '.$e->GetMessage());
}
try { // Setup 'Robots_txt' template default:
   $fn = cms_join_path(__DIR__,'templates','SEOBoost_robots_txt.tpl');
   if( file_exists( $fn ) ) {
      $template = @file_get_contents($fn);
      $tpl = new CmsLayoutTemplate();
      $tpl->set_name('SEOBoost_robots_txt');
      $tpl->set_owner($uid);
      $tpl->set_content($template);
      $tpl->set_type($template_type);
      $tpl->set_type_dflt(TRUE);
      $tpl->save();
   }
} catch( CmsException $e ) {
   debug_to_log(__FILE__.':'.__LINE__.' '.$e->GetMessage());
   audit('',$this->GetName(),'Installation Error: '.$e->GetMessage());
}

try { // Setup 'RSS' template type
   $template_type = new CmsLayoutTemplateType();
   $template_type->set_originator($this->GetName());
   $template_type->set_name('RSS');
   $template_type->set_dflt_flag(TRUE);
   $template_type->set_lang_callback('SEOBoost::page_type_lang_callback');
   $template_type->set_content_callback('SEOBoost::reset_page_type_defaults');
   $template_type->reset_content_to_factory();
   $template_type->save();
} catch( CmsException $e ) {
   debug_to_log(__FILE__.':'.__LINE__.' '.$e->GetMessage());
   audit('',$this->GetName(),'Installation Error: '.$e->GetMessage());
}
try { // Setup 'Robots_txt' template default:
   $fn = cms_join_path(__DIR__,'templates','SEOBoost_rss.tpl');
   if( file_exists( $fn ) ) {
      $template = @file_get_contents($fn);
      $tpl = new CmsLayoutTemplate();
      $tpl->set_name('SEOBoost_rss');
      $tpl->set_owner($uid);
      $tpl->set_content($template);
      $tpl->set_type($template_type);
      $tpl->set_type_dflt(TRUE);
      $tpl->save();
   }
} catch( CmsException $e ) {
   debug_to_log(__FILE__.':'.__LINE__.' '.$e->GetMessage());
   audit('',$this->GetName(),'Installation Error: '.$e->GetMessage());
}

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
   audit('',$this->GetName(),'Installation Error: '.$e->GetMessage());
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
   audit('',$this->GetName(),'Installation Error: '.$e->GetMessage());
}



// and routes...
$this->CreateStaticRoutes();


