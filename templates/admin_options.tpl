
{form_start action=admin_save_options}

    <fieldset class="m_bottom_30">  
        <legend>{$mod->Lang('title_bulk_edit_content_settings')}</legend>

        <table id="bulk-edit-repeater" class="seoboost_repeater sortable pagetable ui-sortable" data-block-name="{$actionid}bulk_edit_fields" data-highest-row="{if empty($bulk_edit_fields)}0{else}{$bulk_edit_fields|@count}{/if}" data-repeater-add="#bulk-edit-repeater-add">
            <thead>
                <tr class="repeater-wrapper-header">
                    <th class="left-panel"></th>
                    <th class="sub-field-heading">{$mod->Lang('title_page_field')}</th>
                    <th class="sub-field-heading pagepos">{$mod->Lang('title_editable')} {cms_help key=help_editable title=$mod->Lang('title_editable')}</th>
                    <th class="sub-field-heading">{$mod->Lang('title_size')} {cms_help key=help_size title=$mod->Lang('title_size')}</th>
                    <th class="sub-field-heading">{$mod->Lang('title_recommended_length')} {cms_help key=help_recommended_length title=$mod->Lang('title_recommended_length')}</th>
                    <th class="right-panel"></th>
                </tr>
            </thead>
            <tbody class="">
                {* template row *}
                <tr class="repeater-wrapper-template sortable-item" style="display: none;">
                    <td class="left-panel handle">
                        <span class="seoboost-icon-grip-dots-vertical-solid"></span>
                    </td>
                    <td class="sub-field sub-field-textinput">
                        <select class="repeater-field" name="" data-field-name="field">
                            <option value="">{$mod->Lang('select_field')}</option>
                            <optgroup label="{$mod->Lang('core_page_fields')}">
                        {foreach $bulk_edit_core_fields as $core_field}
                            <option value="{$core_field}">{$mod->Lang($core_field)}</option>
                        {/foreach}
                            <optgroup label="{$mod->Lang('custom_page_fields')}">
                            {html_options values=$bulk_edit_custom_fields output=$bulk_edit_custom_fields selected=''}
                        </select>
                    </td>
                    <td class="sub-field sub-field-checkbox pagepos">
                        <input type="hidden" name="" value="0" class="repeater-field" data-field-name="editable">
                        <input type="checkbox" name="" id="" value="1" class="cms_checkbox repeater-field" data-field-name="editable">
                    </td>
                    <td class="sub-field sub-field-textinput">
                        <input type="text" id="" name="" class="repeater-field" value="" size="6" maxlength="6" data-field-name="size">
                    </td>
                    <td class="sub-field sub-field-textinput">
                        <input type="text" id="" name="" class="repeater-field" value="" size="6" maxlength="6" data-field-name="length">
                    </td>
                    <td class="right-panel controls">
                        <button class="seoboost-repeater-remove seoboost-btn seoboost-btn-default seoboost-icon-only" title="Remove line" role="button" aria-disabled="false"><span class="seoboost-icon-trash-can-regular"></span></button>
                    </td>
                </tr>
                {* data rows *}
                {foreach $bulk_edit_fields as $selected_field}
                <tr class="repeater-wrapper sortable-item" style="{*display: none;*}">
                    <td class="left-panel handle">
                        <span class="seoboost-icon-grip-dots-vertical-solid"></span>
                    </td>
                    <td class="sub-field sub-field-textinput">
                        <select class="cms_dropdown" name="{$actionid}bulk_edit_fields[r_{$selected_field@index}][field]">
                            <option value="">{$mod->Lang('select_field')}</option>
                            <optgroup label="{$mod->Lang('core_page_fields')}">
                        {foreach $bulk_edit_core_fields as $core_field}
                            <option value="{$core_field}" {if $selected_field.field==$core_field}selected{/if}>{$mod->Lang($core_field)}</option>
                        {/foreach}
                            <optgroup label="{$mod->Lang('custom_page_fields')}">
                            {html_options values=$bulk_edit_custom_fields output=$bulk_edit_custom_fields selected=$selected_field.field}
                        </select>
                    </td>
                    <td class="sub-field sub-field-checkbox pagepos">
                        <input type="hidden" name="{$actionid}bulk_edit_fields[r_{$selected_field@index}][editable]" value="0">
                        <input type="checkbox" name="{$actionid}bulk_edit_fields[r_{$selected_field@index}][editable]" id="" value="1" {if $selected_field.editable==1}checked="checked"{/if} class="cms_checkbox repeater-field" data-field-name="editable">
                    </td>
                    <td class="sub-field sub-field-textinput">
                        <input type="text" id="" name="{$actionid}bulk_edit_fields[r_{$selected_field@index}][size]" class=" repeater-field" value="{$selected_field.size}" size="6" maxlength="6" data-field-name="size">
                    </td>
                    <td class="sub-field sub-field-textinput">
                        <input type="text" id="" name="{$actionid}bulk_edit_fields[r_{$selected_field@index}][length]" class=" repeater-field" value="{$selected_field.length}" size="6" maxlength="6" data-field-name="length">
                    </td>
                    <td class="right-panel controls">
                        <button class="seoboost-repeater-remove seoboost-btn seoboost-btn-default seoboost-icon-only" title="Remove line" role="button" aria-disabled="false"><span class="seoboost-icon-trash-can-regular"></span></button>
                    </td>
                </tr>
                {/foreach}
            </tbody>
        </table>

        <div class="seoboost_repeater_footer">
            <button id="bulk-edit-repeater-add" class="seoboost-repeater-add seoboost-btn seoboost-btn-default" data-repeater="#bulk-edit-repeater" title="Add line" role="button" aria-disabled="false"><span class="seoboost-icon-plus"></span>&nbsp;&nbsp;{$mod->Lang('add_field')}</button>
        </div>

        <div class="pageoverflow">
            <p class="pagetext">{$mod->Lang('title_bulk_edit_layout')}:</p>
            
            <p class="pageinput">
                <select name="{$actionid}bulk_edit_layout">
                {foreach $mod::BULK_EDIT_LAYOUTS as $layout}
                    <option value="{$layout}" {if $bulk_edit_layout==$layout}selected{/if}>{$mod->Lang("title_layout_$layout")}</option>
                {/foreach}
                </select>
            </p>
        </div>
    </fieldset>




    <fieldset class="m_bottom_30">  
        <legend>{$mod->Lang('title_contentmanager_add_ins')}</legend>

        <table id="cm-add-in-options" class="pagetable">
            <thead>
                <tr>
                    <th class="pagepos">{$mod->Lang('title_use_addin')}</th>
                    <th class="">{$mod->Lang('title_content_manager_field')}</th>
                    <th class="">{$mod->Lang('title_size')} {cms_help key=help_size title=$mod->Lang('title_size')}</th>
                    <th class="">{$mod->Lang('title_recommended_length')} {cms_help key=help_recommended_length title=$mod->Lang('title_recommended_length')}</th>
                </tr>
            </thead>
            <tbody>
                {foreach $cm_add_in_fields as $field_name => $field_settings}
                <tr class="{cycle values='row1,row2'} {if $field_settings.editable==0}disabled{/if}">
                    <td class="pagepos">
                        <input type="hidden" name="{$actionid}cm_add_in_fields[{$field_name}][editable]" value="0">
                        <input type="checkbox" class="editable" name="{$actionid}cm_add_in_fields[{$field_name}][editable]" value="1" {if $field_settings.editable==1}checked="checked"{/if}>
                    </td>
                    <td>
                        <input class="name non-editable can-be-disabled" type="text" value="{$mod->Lang($field_name)}" size="30" readonly>
                    </td>
                    <td>{* size *}
                        <input class="size can-be-disabled can-be-readonly" type="text" name="{$actionid}cm_add_in_fields[{$field_name}][size]" value="{$field_settings.size}" size="6" maxlength="6" {if $field_settings.editable==0}readonly{/if}>
                    </td>
                    <td>{* length *}
                        <input class="length can-be-disabled can-be-readonly" type="text" name="{$actionid}cm_add_in_fields[{$field_name}][length]" value="{$field_settings.length}" size="6" maxlength="6" {if $field_settings.editable==0}readonly{/if}>
                    </td>
                </tr>
                {/foreach}
            </tbody>

        </table>
    </fieldset>






    <fieldset class="m_bottom_30">  
        <legend>{$mod->Lang('title_custom_module_settings')}</legend>

        <div class="pageoverflow">
            <p class="pagetext">{$title_customModuleName}:</p>
            <p class="pageinput">{$input_customModuleName}</p>
        </div>
        <div class="pageoverflow">
            <p class="pagetext">{$title_adminSection}:</p>
            <p class="pageinput">{$input_adminSection}</p>
        </div>

    </fieldset>


    <div class="pageoverflow">
        <p class="pagetext">{$title_useSearchable}:</p>
        <p class="pageinput">
            {$input_useSearchable}
            {$info_useSearchable}
        </p>
    </div>


   <div class="pageoverflow">
      <p class="pagetext">&nbsp;</p>
      <p class="pageinput">
         <input type="submit" name="{$actionid}submit" value="{$mod->Lang('submit')}"/>
      </p>
   </div>

{$debug=false}
{if $debug}
    <div class="pageoverflow">
        <p class="pagetext">&nbsp;</p>
        <p class="pageinput">
            <a href="{cms_action_url action=test_something}">TEST SOMETHING?</a>
        </p>
    </div>
{/if}

{form_end}