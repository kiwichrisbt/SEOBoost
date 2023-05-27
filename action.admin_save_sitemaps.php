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


// toggle a single sitemap
if ( !empty($params['toggle_active']) && !empty($params['sid']) ) {
   $sitemap = SEOBoostSitemap::load_by_id( (int)$params['sid'] );
   if ($sitemap->module!='LISE') {
     $sitemap->toggle_active();
   }
   $this->RedirectToAdminTab('sitemaps');
   return;
}



// Show saved parameters in debug mode
debug_display($params);

// Put mention into the admin log & user msg
audit( '', 'SEOBoost', $this->Lang('sitemaps_tab_saved') );
$this->SetMessage( $this->Lang('sitemaps_tab_saved') );
$this->RedirectToAdminTab('sitemaps');


