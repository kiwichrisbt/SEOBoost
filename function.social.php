<?php
#---------------------------------------------------------------------------------------------------
# Module: SEOBoost
# Author: Chris Taylor
# Copyright: (C) 2019 Chris Taylor, chris@binnovative.co.uk
# Module's homepage is: http://dev.cmsmadesimple.org/projects/seoboost
# Licence: GNU General Public License version 3
#          see /SEOBoost/lang/LICENCE.txt or <http://www.gnu.org/licenses/>
#---------------------------------------------------------------------------------------------------


function socialShares ($params, $smarty) {
//***********************************************************************************************
// based on Plugin socialShares - v1.5.1 - 15Sep16
//
//***********************************************************************************************

   // an array of Social Share options btnClass [0], iconClass [1], btnText [2], shareUrl [3]
   $shareOptions = [
      ['email', 'icon-envelope-o', '',
         'mailto:?subject={$pageVars.pageTitle|cms_escape:url}&amp;body={$pageVars.pageURL|cms_escape:url}'],
      ['facebook', 'icon-facebook', '',
         'http://www.facebook.com/sharer/sharer.php?u={$pageVars.pageURL|cms_escape:url}'],
      ['twitter', 'icon-twitter', '',
         'https://twitter.com/intent/tweet?text={$pageVars.pageTitle|cms_escape:url}&amp;url={$pageVars.pageURL|cms_escape:url}'],
      //['pinterest', 'icon-pinterest-p', '',
      // "https://uk.pinterest.com/pin/create/bookmarklet/?url=$pageURL&amp;title=$pageTitle&amp;description=$pageDescription"],
      ['linkedin', 'icon-linkedin', '',
         'http://www.linkedin.com/shareArticle?mini=true&amp;url={$pageVars.pageURL|cms_escape:url}&amp;title={$pageVars.pageTitle|cms_escape:url}&amp;summary={$pageVars.pageDescription|cms_escape:url}&amp;source={$pageVars.pageURL|cms_escape:url}']
   ];

   $mod = cms_utils::get_module('SEOBoost');
   $tpl = $smarty->CreateTemplate( $mod->GetTemplateResource('socialShares_template.tpl'), null, null, $smarty );
   // $pageVars - should already be set and in scope (global)
   $tpl->assign('shareOptions', $shareOptions);
   $tpl->display();


}/* socialShares */



function socialLinks ($params, $smarty) {
//**************************************************************************************************
// socialLinks - replaces UDT socialLinks v1.2 - 30Apr15 - plus microdata added
//**************************************************************************************************
   // array of social field aliases (key) to array of associated 'icon classes' [0], 'link text' [1], url start [2]
   $socialOptions = array(
      'facebook' => array('icon-facebook', 'Facebook', '//www.facebook.com/'),
      'twitter'  => array('icon-twitter',  'Twitter', '//www.twitter.com/'),
      'linkedin' => array('icon-linkedin', 'LinkedIn', '//www.linkedin.com/'),
      'instagram' => array('icon-instagram', 'Instagram', '//www.instagram.com/')
   );

   $mod = cms_utils::get_module('SEOBoost');
   $tpl = $smarty->CreateTemplate( $mod->GetTemplateResource('socialLinks_template.tpl'), null, null, $smarty );
   // $pageVars - should already be set and in scope (global)
   $tpl->assign('socialOptions', $socialOptions);
   $tpl->display();

}/* socialLinks */
