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

if ( !$this->CheckPermission(SEOBoost::USE_PERM) ) return;

// Save Parameters Company Info Tab
$settings = [
   'phoneNumber',
   'mobileNumber',
   'email',
   'fax',
   'fullBusinessName',
   'street1',
   'street2',
   'city',
   'region',
   'postcode',
   'country',
   'businessType',
   'latitude_longitude',
   'geo_region',   
   'copyright_start',
   'ga_code',
   'ga_gtag_id'
];


foreach ($settings as $name) {
   $setting = SEOBoostSetting::load_by_name($name);
   if ( isset($params['input_'.$name]) && $params['input_'.$name]!=$setting->value ) {
      $setting->value = $params['input_'.$name];
      $setting->save();
   }
}


// Show saved parameters in debug mode
debug_display($params);

// Put mention into the admin log & send user msg
audit( '', $this->GetPreference('customModuleName', 'SEOBoost'), $this->Lang('company_info_tab_saved') );
$this->SetMessage( $this->Lang('company_info_tab_saved') );
$this->RedirectToAdminTab('company_info');


