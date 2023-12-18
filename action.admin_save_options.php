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

// Save Parameters Options Tab
$this->SetPreference('customModuleName', !empty($params['input_customModuleName']) ? $params['input_customModuleName'] : 'SEOBoost');
$this->SetPreference('adminSection', isset($params['input_adminSection']) ? $params['input_adminSection'] : 'content');
$this->SetPreference('useSearchable', isset($params['input_useSearchable']) ? (bool)$params['input_useSearchable'] : false);

// Save Options Tab - Bulk Edit Content Settings
$this->set_bulk_edit_fields($params['bulk_edit_fields']  ?? []);
$this->set_cm_add_in_fields($params['cm_add_in_fields'] ?? []);

// Touch menu cache files
if (version_compare(CMS_VERSION, '1.99-alpha0', '<')) {
   foreach (glob(cms_join_path(TMP_CACHE_LOCATION, "themeinfo*.cache")) as $filename) { @unlink($filename); } // 1.11
} else {
   foreach (glob(cms_join_path(TMP_CACHE_LOCATION, "cache*.cms")) as $filename) { @unlink($filename); } // 2.0
}

// Show saved parameters in debug mode
debug_display($params);

// Put mention into the admin log
audit('', 'SEOBoost - Options tab', 'Saved');

$this->SetMessage( $this->Lang('settings_saved', 'Options') );
$this->RedirectToAdminTab('options');


