{* socialShares_template.tpl - v1.0 - 12Feb19

   - v1.0 - 12Feb19 - moved to smarty template

***************************************************************************************************}
      <ul class="social-shares">{strip}
{foreach $shareOptions as $option}
         <li>
            <a class="btn social-share {$option[0]}" target="_blank" href="{eval var=$option[3]}">
               {if !empty($option[1])}<i class="{$option[1]}"></i>{/if}
               {if !empty($option[2])}<span>{$option[2]}</span>{/if}
            </a>
         </li>
{/foreach}{/strip}
      </ul>