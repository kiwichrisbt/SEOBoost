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

if( !$this->CheckPermission(SEOBoost::USE_PERM) ) return;

// Save Parameters Social Tab
$settings = [
   'twitter',
   'facebook',
   'linkedin',
   'instagram'
];

foreach ($settings as $setting_name) {
    $setting = SEOBoostSetting::load_by_name($setting_name);
    if ( !is_object($setting) ) {
        $setting = new SEOBoostSetting();
        $setting->__set('name', $setting_name);
        $setting->__set('type', 'pagevar');
    }
    if ( isset($params['input_'.$setting_name]) && $params['input_'.$setting_name]!=$setting->value ) {
        $setting->__set('value', $params['input_'.$setting_name]);
        $setting->save(); 
    }
}


// Show saved parameters in debug mode
debug_display($params);
debug_to_log($params,'$params');

// Put mention into the admin log & send user msg
audit( '', $this->GetPreference('customModuleName', 'SEOBoost'), $this->Lang('social_tab_saved') );
$this->SetMessage( $this->Lang('social_tab_saved') );
$this->RedirectToAdminTab('social');


