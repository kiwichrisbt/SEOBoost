{* socialLinks_template.tpl - v1.0 - 12Feb19

   - v1.0 - 12Feb19 - moved to smarty template

***************************************************************************************************}
      <ul class="social-links list-unstyled">
{foreach $socialOptions as $social => $options}
{if !empty($pageVars[$social])}
         <li><a itemprop="sameAs" href="{$options[2]}{$pageVars[$social]}" class="social" target="_blank"><i class="{$options[0]}"></i>{$options[1]}</a></li>
{/if}
{/foreach}
      </ul>