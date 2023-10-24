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

echo "<pre>";
   echo "TESTING...\n\n";





   try {
      $templateType = CmsLayoutTemplateType::load('SEOBoost::Metadata');
      $templateType->delete();
   } catch( Exception $e ) {
      audit('',$this->GetName(),'Uninstall Error: '.$e->GetMessage());
   }





echo "</pre>";