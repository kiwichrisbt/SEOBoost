<?php
#---------------------------------------------------------------------------------------------------
# Module: SEOBoost
# Author: Chris Taylor
# Copyright: (C) 2019 Chris Taylor, chris@binnovative.co.uk
# Module's homepage is: http://dev.cmsmadesimple.org/projects/seoboost
# Licence: GNU General Public License version 3
#          see /SEOBoost/lang/LICENCE.txt or <http://www.gnu.org/licenses/>
#---------------------------------------------------------------------------------------------------

class SEOBoostSitemap {

   private $_data = array( 'id'=>null, 'name'=>null, 'module'=>null, 'url'=>null, 'active'=>null );

   public function __get($key) {
      switch( $key ) {
         case 'id':
         case 'name':
         case 'module':
         case 'url':
         case 'active':
         return $this->_data[$key];
      }
   }

   public function __set($key,$val) {
      switch( $key ) {
         case 'name':
            $this->_data[$key] = trim($val);
            break;
         case 'module':
            $this->_data[$key] = trim($val);
            break;
         case 'url':
            $this->_data[$key] = trim($val);
            break;
         case 'active':
            $this->_data[$key] = (bool)$val;
            break;
      }
   }

   public function save() {
      // test if valid before calling save()
      if ( $this->id > 0 ) {
         $saved = $this->update();
      } else {
         $saved = $this->insert();
      }
      return $saved;
   }


   protected function insert() {
      $db = \cms_utils::get_db();
      $sql = 'INSERT INTO '.CMS_DB_PREFIX.'module_seoboost_sitemaps (name, module, url, active) VALUES (?,?,?,?)';
      $dbr = $db->Execute($sql, array($this->name, $this->module, $this->url, $this->active));
      if( !$dbr ) return FALSE;
      $this->_data['id'] = $db->Insert_ID();
      return TRUE;
   }

   protected function update() {
      $db = \cms_utils::get_db();
      $sql = 'UPDATE '.CMS_DB_PREFIX.'module_seoboost_sitemaps SET name = ?, module = ?, url = ?, active = ?    WHERE id = ?';
      $dbr = $db->Execute($sql, array($this->name, $this->module, $this->url, $this->active, $this->id));
      if( !$dbr ) return FALSE;
      return TRUE;
   }

   public function delete() {
      if( !$this->id ) return FALSE;
      $db = \cms_utils::get_db();
      $sql = 'DELETE FROM '.CMS_DB_PREFIX.'module_seoboost_sitemaps WHERE id = ?';
      $dbr = $db->Execute($sql,array($this->id));
      if( !$dbr ) return FALSE;
      $this->_data['id'] = null;
      return TRUE;
   }

   /** internal */
   public function fill_from_array($row) {
      foreach( $row as $key => $val ) {
         if( array_key_exists($key,$this->_data) ) {
            $this->_data[$key] = $val;
         }
      }
   }

   public static function &load_by_id($id) {
      $id = (int) $id;
      $db = \cms_utils::get_db();
      $sql = 'SELECT * FROM '.CMS_DB_PREFIX.'module_seoboost_sitemaps WHERE id = ?';
      $row = $db->GetRow($sql,array($id));
      if( is_array($row) ) {
         $obj = new self();
         $obj->fill_from_array($row);
         return $obj;
      }
   }

   public static function &load_by_name($name) {
      $name = trim($name);
      $db = \cms_utils::get_db();
      $sql = 'SELECT * FROM '.CMS_DB_PREFIX.'module_seoboost_sitemaps WHERE name = ?';
      $row = $db->GetRow($sql,array($name));
      if( is_array($row) ) {
         $obj = new self();
         $obj->fill_from_array($row);
         return $obj;
      }
   }

   public function toggle_active() {
      if( !$this->id ) return FALSE;
      $db = \cms_utils::get_db();
      $sql = 'UPDATE '.CMS_DB_PREFIX.'module_seoboost_sitemaps SET active = ? WHERE id = ?';
      $dbr = $db->Execute($sql,array(!(bool)$this->active, $this->id));
      if( !$dbr ) return FALSE;
      return TRUE;
   }

   public function set_all_active($active = true) {
debug_to_log('set_all_active RAN');
      $db = \cms_utils::get_db();
      $sql = 'UPDATE '.CMS_DB_PREFIX.'module_seoboost_sitemaps SET active = ?';
      $dbr = $db->Execute($sql,array(!(bool)$active));
      if( !$dbr ) return FALSE;
      return TRUE;
   }

}


