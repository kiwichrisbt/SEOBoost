{* admin_bulk_edit_content.tpl - v1.1 - 21Dec23 *}

<h3 class="m_top_0">{$mod->Lang('title_bulk_edit_content')}</h3>
{if $show_alias_warning}
    <div class="warning">{$mod->Lang('warn_edit_alias')}</div>
{/if}
{if empty($bulk_edit_fields)}
    <div class="warning">{$mod->Lang('warning_no_fields_selected')}</div>
{/if}


{function name=seoboost_output_bulk_edit_field field=[] row=[]}
    {if $field.editable && isset($field)}
        {$length=$field.length|default:false}
        {$chars=$row[$field.field]|default:''|count_characters:true}
        {$value=$row[$field.field]|default:''|escape}
        {if $length}
        <div class="seoboost-char-count {if $chars>$length}over-length{/if}" data-length="{$length}">
        {/if}
        <input type="text" class="{$field.field}" name="{$actionid}bulk_edit[{$row.id}][{$field.field}]" value="{$value}" data-size="{$field.size|default:$default_field_size}" placeholder="{$mod->Lang('new')} {if $field.is_core_field}{$mod->Lang($field.field)}{else}{$field.field}{/if}" title="{$mod->Lang('current_value')}: {$value}" {if !empty($field.size)}style="width:{$field.size*6.6}px"{/if}/>
        {if $length}
            <div class="seoboost-char-count-message"><span class="count">{$chars}</span>/{$length}</div>
            <div class="seoboost-char-count-bar" style="width:{$chars/$length*100}%"></div>
        </div>
        {/if}
        
    {elseif !$field.editable && isset($field)}
        {* <input type="text" class="{$field.field} non-editable" value="{$row[$field.field]|escape}" readonly {if !empty($field.size)}size="{$field.size}"{/if}> *}
        <div class="text-only">{$row[$field.field]|escape}</div>
    {/if}
{/function}



{form_start action='admin_bulk_edit_content'}
<div class="pageoverflow">
    <div class="pageinput">
        <input type="submit" name="{$actionid}submit" value="{$mod->Lang('submit')}"/>
        <input type="submit" name="{$actionid}cancel" value="{$mod->Lang('cancel')}"/>
    </div>
</div>


{if !empty($bulk_edit_fields) && $layout=='table'}
<div class="table-responsive">
    <table id="bulk_edit_content" class="pagetable">
        <thead>
            <tr>
                <th>&nbsp;</th> 
                {foreach $bulk_edit_fields as $field}
                <th>{if !empty($field.is_core_field)}{$mod->Lang($field.field)}{else}{$field.field}{/if}</th>
                {/foreach}
            </tr>
        </thead>
        <tbody>
        {foreach $contentlist as $row}
            <tr class="{cycle values='row1,row2'}">
                <td>{$row.id}</td>
            {foreach $bulk_edit_fields as $field}
                <td>{seoboost_output_bulk_edit_field field=$field row=$row}</td>
            {/foreach}
            </tr>
        {/foreach}
        </tbody>
    </table>
</div>


{elseif !empty($bulk_edit_fields) && $layout=='stacked'}
{$field_count=$bulk_edit_fields|count}{* count fields *}
<div class="table-stacked">
    <table id="bulk_edit_content" class="pagetable table-stacked">
        <thead>
            <tr>
                <th>&nbsp;</th> 
                <th>{$mod->Lang('title_page_fields')}</th>
            </tr>
        </thead>
    {foreach $contentlist as $row}
        <tbody class="{if $row@index is even}row1{else}row2{/if}">
        {foreach $bulk_edit_fields as $field}
            <tr>
            {if $field@index==0}
                <td rowspan="{$field_count}" class="text-only">
                    {$row.id}
                </td>
            {/if}        
                <td>
                    <span class="field-label">{if !empty($field.is_core_field)}{$mod->Lang($field.field)}{else}{$field.field}{/if}:</span>
                    <span class="field-values">{seoboost_output_bulk_edit_field field=$field row=$row}</span>
                </td>
            </tr>
        {/foreach}
        </tbody>
    {/foreach}
    </table>
</div>


{elseif !empty($bulk_edit_fields) && $layout=='stacked_with_inline_titles'}
{$field_count=$bulk_edit_fields|count}{* count fields *}
<div class="table-stacked-with-inline-titles">
    <table id="bulk_edit_content" class="pagetable">
        <thead>
            <tr>
                <th>&nbsp;</th> 
                <th>{$mod->Lang('title_page_fields')}</th>
                <th>&nbsp;</th>
            </tr>
        </thead>
    {foreach $contentlist as $row}
        <tbody class="{if $row@index is even}row1{else}row2{/if}">
        {foreach $bulk_edit_fields as $field}
            <tr>
            {if $field@index==0}
                <td rowspan="{$field_count}" class="text-only">
                    {$row.id}
                </td>
            {/if}        
                <td class="field-label text-only">
                    {if !empty($field.is_core_field)}{$mod->Lang($field.field)}{else}{$field.field}{/if}:
                </td>
                <td class="field-values">
                    {seoboost_output_bulk_edit_field field=$field row=$row}
                </td>
            </tr>
        {/foreach}
        </tbody>
    {/foreach}
    </table>
</div>


{elseif !empty($bulk_edit_fields) && $layout=='stacked_with_title_column'}
{$title_field=$bulk_edit_fields|array_shift}{* first field as title_field & removed from $bulk_edit_fields *}
{$field_count=$bulk_edit_fields|count}{* count remaining fields *}
<div class="table-stacked-with-title-column">
    <table id="bulk_edit_content" class="pagetable">
        <thead>
            <tr>
                <th>&nbsp;</th> 
                <th>{if !empty($title_field.is_core_field)}{$mod->Lang($title_field.field)}{else}{$title_field.field}{/if}</th>
                {if $field_count>0}
                    <th>{$mod->Lang('title_page_fields')}</th>
                    <th>&nbsp;</th>
                {/if}
            </tr>
        </thead>
        
        {foreach $contentlist as $row}
            <tbody class="{if $row@index is even}row1{else}row2{/if}">
            {if $field_count>0}
                {foreach $bulk_edit_fields as $field}
                <tr>
                {if $field@index==0}
                    <td rowspan="{$field_count}" class="text-only">
                        {$row.id}
                    </td>
                    <td rowspan="{$field_count}">
                        {seoboost_output_bulk_edit_field field=$title_field row=$row}
                    </td>
                {/if}
                    <td class="field-label text-only">
                        {if !empty($field.is_core_field)}{$mod->Lang($field.field)}{else}{$field.field}{/if}:
                    </td>
                    <td class="field-values">
                        {seoboost_output_bulk_edit_field field=$field row=$row}
                    </td>
                </tr>
                {/foreach}
            {else}
                <tr>
                    <td valign="top" rowspan="{$field_count}" class="text-only">
                        {$row.id}
                    </td>
                    <td valign="top" rowspan="{$field_count}">
                        {seoboost_output_bulk_edit_field field=$title_field row=$row}
                    </td>
                </tr>
            {/if}
            </tbody>
        {/foreach}
    </table>
</div>
{/if}{* $layout!='table' *}

<div class="pageinput m_top_10">
    <input type="submit" name="{$actionid}submit" value="{$mod->Lang('submit')}"/>
    <input type="submit" name="{$actionid}cancel" value="{$mod->Lang('cancel')}"/>
</div>
{form_end}

{* 
<pre>   
$bulk_edit_fields:{$bulk_edit_fields|print_r}

$contentlist:{$contentlist|print_r}
</pre> *}
