<?php
#---------------------------------------------------------------------------------------------------
# Module: SEOBoost
# Author: Chris Taylor
# Copyright: (C) 2019 Chris Taylor, chris@binnovative.co.uk
# Module's homepage is: http://dev.cmsmadesimple.org/projects/seoboost
# Licence: GNU General Public License version 3
#          see /SEOBoost/lang/LICENCE.txt or <http://www.gnu.org/licenses/>
#---------------------------------------------------------------------------------------------------



function googleAnalytics ($params, &$smarty) {
//**************************************************************************************************
// based on Plugin googleAnalytics
//
//    See https://developers.google.com/analytics/devguides/collection/analyticsjs/ for details.
//
//**************************************************************************************************
   if ( isset($params['addProduct']) || isset($params['setAction']) ) return;
      // for compatibility (non breaking) with previous version of tag

   $GAId ='';    // default GA Tracking Id if not set in admin page
   $GA_debug = false;

   if ( !empty($params['debug']) ) $GA_debug = true;

   $pageVars = $smarty->get_template_vars('pageVars');
   if ( !empty($pageVars['ga_code']) ) {
      $GAId = $pageVars['ga_code'];
   }

   // create smarty template, vars & output
   $mod = cms_utils::get_module('SEOBoost');
   if( is_object($mod) ) {
      $tpl = $smarty->CreateTemplate( $mod->GetTemplateResource('googleAnalytics_template.tpl'), null, null, $smarty );
      $tpl->assign('GAId', $GAId);
      $tpl->assign('GA_debug', $GA_debug);
      $tpl->display();
   }

}


