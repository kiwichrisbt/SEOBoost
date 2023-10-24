<?php
#---------------------------------------------------------------------------------------------------
# Module: SEOBoost
# Author: Chris Taylor
# Copyright: (C) 2019 Chris Taylor, chris@binnovative.co.uk
# Module's homepage is: http://dev.cmsmadesimple.org/projects/seoboost
# Licence: GNU General Public License version 3
#          see /SEOBoost/lang/LICENCE.txt or <http://www.gnu.org/licenses/>
#---------------------------------------------------------------------------------------------------

class SEOBoostSitemapQuery extends CmsDbQueryBase {

   public function __construct($args = '') {
      parent::__construct($args);
      if ( isset($this->_args['limit']) ) $this->_limit = (int) $this->_args['limit'];
   }

   public function execute() {
      if( !is_null($this->_rs) ) return;
      $sql = 'SELECT SQL_CALC_FOUND_ROWS * FROM '.CMS_DB_PREFIX.'module_seoboost_sitemaps
              ORDER BY id';
      $db = \cms_utils::get_db();
      $this->_rs = $db->SelectLimit($sql,$this->_limit,$this->_offset);
      if ( $db->ErrorMsg() ) throw new \CmsSQLErrorException( $db->sql.' -- '.$db->ErrorMsg() );
      $this->_totalmatchingrows = $db->GetOne('SELECT FOUND_ROWS()');
   }

   public function &GetObject() {
      $obj = new SEOBoostSitemap;
      $obj->fill_from_array($this->fields);
      return $obj;
   }


}


