{* admin_bulk_edit_content.tpl - v1.0 - 11Dec23 *}

<h3 class="m_top_0">{$mod->Lang('title_bulk_edit_content')}</h3>
{if $show_alias_warning}
    <div class="warning">{$mod->Lang('warn_edit_alias')}</div>
{/if}
{if empty($bulk_edit_fields)}
    <div class="warning">{$mod->Lang('warning_no_fields_selected')}</div>
{/if}


{form_start action='admin_bulk_edit_content'}
<div class="pageoverflow">
    <div class="pageinput">
        <input type="submit" name="{$actionid}submit" value="{$mod->Lang('submit')}"/>
        <input type="submit" name="{$actionid}cancel" value="{$mod->Lang('cancel')}"/>
    </div>
</div>
<div class="table-responsive">
    <table id="bulk_edit_content" class="pagetable">
        <thead>
            <tr>
                <th>&nbsp;</th> 
            {if !empty($bulk_edit_fields)}
                {foreach $bulk_edit_fields as $field}
                <th>{if !empty($field.is_core_field)}{$mod->Lang($field.field)}{else}{$field.field}{/if}</th>
                {/foreach}
            {/if}
            </tr>
        </thead>
        <tbody>
        {foreach $contentlist as $row}
            <tr class="{cycle values='row1,row2'}">
                <td>{$row.id}</td>
            {if !empty($bulk_edit_fields)}
                {foreach $bulk_edit_fields as $field}
                <td>
                {if $field.editable && isset($row[$field.field])}

                    {$length=$field.length|default:false}
                    {$chars=$row[$field.field]|count_characters:true}
                    {if $length}
                    <div class="seoboost-char-count {if $chars>$length}over-length{/if}" data-length="{$length}">
                    {/if}
                    <input type="text" class="{$field.field}" name="{$actionid}bulk_edit[{$row.id}][{$field.field}]" value="{$row[$field.field]|escape}" size="{$field.size|default:$default_field_size}" placeholder="{$mod->Lang('new')} {if $field.is_core_field}{$mod->Lang($field.field)}{else}{$field.field}{/if}" title="{$mod->Lang('current_value')}: {$row[$field.field]|escape}"/>
                    {if $length}
                        <div class="seoboost-char-count-message"><span class="count">{$chars}</span>/{$length}</div>
                        <div class="seoboost-char-count-bar" style="width:{$chars/$length*100}%"></div>
                    </div>
                    {/if}
                    
                {elseif !$field.editable && isset($row[$field.field])}
                    <input type="text" class="{$field.field} non-editable" value="{$row[$field.field]|escape}" readonly {if !empty($field.size)}size="{$field.size}"{/if}>
                {/if}
                </td>
                {/foreach}
            {/if}
            </tr>
        {/foreach}
        </tbody>
    </table>
</div>
<div class="pageinput m_top_10">
    <input type="submit" name="{$actionid}submit" value="{$mod->Lang('submit')}"/>
    <input type="submit" name="{$actionid}cancel" value="{$mod->Lang('cancel')}"/>
</div>
{form_end}