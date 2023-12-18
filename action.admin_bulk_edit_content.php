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

try {
    $contentops = ContentOperations::get_instance();
    $contentmanager = cms_utils::get_module('CMSContentManager');


    if ( isset($params['cancel']) ) {
        $contentmanager->Redirect('m1_','defaultadmin');
    }


    if ( isset($params['submit']) ) {
        if ( !isset($params['bulk_edit']) || !is_array($params['bulk_edit']) || empty($params['bulk_edit']) ) {
            throw new \RuntimeException($this->Lang('error_nocontentselected'));
        }

        $ids = array_keys($params['bulk_edit']);
        $contentops->LoadChildren(null,FALSE,TRUE,$ids);


        $changes_saved = [];        // array of all fields & content ids that have been saved
        $bulk_edit_fields = $this->get_bulk_edit_fields();
        $bulk_edit_data = $params['bulk_edit'];


        foreach( $bulk_edit_data as $c_id => $field_values ) {
            $content_obj = $contentops->LoadContentFromId($c_id);
            if ( !$content_obj ) continue;
        
            // save fields with changed values only
            $changed = false;    // flag to indicate if any changes have been made
            foreach( $bulk_edit_fields as $field ) {

                if ( $field['editable']!=1 || !isset($field_values[$field['field']]) ) continue;
                switch ($field['field']) {
                    case 'core_content_name':
                        $current_name = $content_obj->Name();
                        $new_name = $field_values['core_content_name'];
                        if ( $current_name != $new_name ) {
                            $content_obj->SetName($new_name);
                            $changes_saved['core_content_name'][] = $c_id;
                            $changed = true;
                        }
                        break;

                    case 'core_menu_text':
                        $current_menu_text = $content_obj->MenuText();
                        $new_menu_text = $field_values['core_menu_text'];
                        if ( $current_menu_text != $new_menu_text ) {
                            $content_obj->SetMenuText($new_menu_text);
                            $changes_saved['core_menu_text'][] = $c_id;
                            $changed = true;
                        }
                        break;

                    case 'core_titleattribute':
                        $current_title = $content_obj->TitleAttribute();
                        $new_title = $field_values['core_titleattribute'];
                        if ( $current_title != $new_title ) {
                            $content_obj->SetTitleAttribute($new_title);
                            $changes_saved['core_titleattribute'][] = $c_id;
                            $changed = true;
                        }
                        break;

                    case 'core_content_alias':
                        $current_alias = $content_obj->Alias();
                        $new_alias = $field_values['core_content_alias'];
                        if ( $current_alias != $new_alias ) {
                            $content_obj->SetAlias($new_alias);
                            $changes_saved['core_content_alias'][] = $c_id;
                            $changed = true;
                        }
                        break;
                        
                    default:  // custom content fields
                        if ( $content_obj->HasProperty($field['field']) ) {
                            $current_value = $content_obj->GetPropertyValue($field['field']);
                            $new_value = $field_values[$field['field']];
                            if ( $current_value != $new_value ) {
                                $content_obj->SetPropertyValue($field['field'], $new_value);
                                $changes_saved[$field['field']][] = $c_id;
                                $changed = true;
                            }
                        }
                        break;
                }
            }

            if ( $changed ) {
                $content_obj->Save();
            }
        }

        $pages_edited = [];
        if ( !empty($changes_saved) ) {
            $msg = $this->Lang('changes_saved');
            foreach ($changes_saved as $field => $ids) {
                $msg .= '<br>'.$field.' on pages: '.implode(', ', $ids);
                $pages_edited = array_merge($pages_edited, $ids);
            }
            $contentmanager->SetMessage([$msg]);  
            audit('',$this->GetName(),'Bulk edited content on '.implode(', ', $pages_edited).' content pages');
        }

        $contentmanager->Redirect('m1_','defaultadmin');

    }


    $contentlist = strval($params['contentlist']);
    if ( !$contentlist ) throw new \RuntimeException($this->Lang('error_nocontentselected'));

    $tmp = explode(',', $contentlist);
    $contentlist_t = [];
    foreach( $tmp as $one ) {
        $one = (int) $one;
        if ( $one < 1 ) continue;
        if ( !in_array($one, $contentlist_t) ) $contentlist_t[] = $one;
    }
    if ( empty($contentlist_t) ) throw new \RuntimeException($this->Lang('error_nocontentselected'));

    $contentops->LoadChildren(null,FALSE,TRUE,$contentlist_t);

    $contentlist = [];
    $bulk_edit_fields = $this->get_bulk_edit_fields();
    $show_alias_warning = FALSE;
    foreach( $contentlist_t as $c_id ) {
        $content_obj = $contentops->LoadContentFromId($c_id);
        if ( !$content_obj ) continue;
    
        $page = [];
        $page['id'] = $c_id;    // page id always included
        // get fields to edit
        foreach( $bulk_edit_fields as $field ) {
            switch ($field['field']) {
                case 'core_content_name':
                    $page['core_content_name'] = $content_obj->Name();
                    break;
                    
                case 'core_menu_text':
                    $page['core_menu_text'] = $content_obj->MenuText();
                    break;

                case 'core_titleattribute':
                    $page['core_titleattribute'] = $content_obj->TitleAttribute();
                    break;

                case 'core_content_alias':
                    $page['core_content_alias'] = $content_obj->Alias();
                    if ($field['editable']==1) $show_alias_warning = TRUE;
                    break;
                    
                default:  // custom content fields
                    if ( $content_obj->HasProperty($field['field']) ) {
                        $page[$field['field']] = $content_obj->GetPropertyValue($field['field']);
                    }
                    break;
            }
        }
        $contentlist[] = $page;
    }

    // create a new form
    $tpl = $smarty->CreateTemplate( $this->GetTemplateResource('admin_bulk_edit_content.tpl'), null, null, $smarty );
    $tpl->assign('bulk_edit_fields', $bulk_edit_fields);
    $tpl->assign('contentlist', $contentlist);
    $tpl->assign('default_field_size', $this::DEFAULT_BULK_EDIT_FIELD_SIZE);
    $tpl->assign('core_fields', $this::BULK_EDIT_CORE_FIELDS);
    $tpl->assign('show_alias_warning', $show_alias_warning);
    $tpl->display();

} catch( \Exception $e ) {
    echo $this->ShowErrors($e->GetMessage());
    return;
}
