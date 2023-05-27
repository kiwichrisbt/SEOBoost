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

$showbase = true;
if ( isset($params['showbase']) && $params['showbase']==false ) $showbase = false;

$tpl = CmsLayoutTemplate::load_dflt_by_type( 'SEOBoost::Metadata' );
if( !is_object($tpl) ) {
   audit('', $this->GetName(), $this->Lang('template_no_default') );
   return;
}
$template = $tpl->get_name();

$tpl = $smarty->CreateTemplate( $this->GetTemplateResource($template), null, null, $smarty );
$tpl->assign('showbase', $showbase );
$rss = SEOBoostSitemap::load_by_name('rss');
if( is_object($rss) && $rss->__get('active') ) {
   $tpl->assign('rss_url', $rss->__get('url') );
}

$tpl->display();


