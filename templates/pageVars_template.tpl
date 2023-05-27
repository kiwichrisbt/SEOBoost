{* pageVars_template.tpl - v1.0 - 09Feb19

   - v1.0 - 09Feb19 - moved to smarty template

********************************************************************************************}{strip}

{if !empty($pageVars.phoneNumber)}
   {$pageVars.phoneNumberLink="<a class=\"phone\" itemprop=\"telephone\" href=\"tel:{$pageVars.phoneNumber|replace:' ':''}\"><span>{$pageVars.phoneNumber}</span></a>" scope=global}
{/if}

{if !empty($pageVars.mobileNumber)}
   {$pageVars.mobileNumberLink="<a class=\"mobile\" itemprop=\"telephone\" href=\"tel:{$pageVars.mobileNumber|replace:' ':''}\"><span>{$pageVars.mobileNumber}</span></a>" scope=global}
{/if}

{if !empty($pageVars.email)}{* obfusicated email link *}
   {$pageVars.emailLink="{mailto address=$pageVars.email encode='javascript' extra='class=\"email\" itemprop=\"email\"'}" scope=global}
{/if}

{* add microdata fields *}
{if !empty($pageVars.businessType)}
   {$pageVars.typeMicrodata="itemscope itemtype=\"http://schema.org/{$pageVars.businessType}\" " scope=global}
{/if}

{if !empty($pageVars.fullBusinessName)}
   {$pageVars.fullBusinessNameMicrodata="<span class=\"name\" itemprop=\"name\">{$pageVars.fullBusinessName}</span>" scope=global}
{/if}

{$pageVars.address="
   {if !empty($pageVars.street1)}{$pageVars.street1}, {/if}
   {if !empty($pageVars.street2)}{$pageVars.street2}, {/if}
   {if !empty($pageVars.city)}{$pageVars.city}, {/if}
   {if !empty($pageVars.county)}{$pageVars.county}, {/if}
   {if !empty($pageVars.postcode)}{$pageVars.postcode}, {/if}
   {if !empty($pageVars.country)}{$pageVars.country}{/if}"|strip scope=global}

{$pageVars.addressMicrodata="
   <div class=\"address\" itemprop=\"address\" itemscope itemtype=\"http://schema.org/PostalAddress\">
      {$pageVars.fullBusinessNameMicrodata|default:''}
      {if !empty($pageVars.street1)}
         <span class=\"street\" itemprop=\"streetAddress\">{$pageVars.street1}</span>
      {/if}
      {if !empty($pageVars.street2)}
         <span class=\"street\" itemprop=\"streetAddress\">{$pageVars.street2}</span>
      {/if}
      {if !empty($pageVars.city)}
         <span class=\"city\" itemprop=\"addressLocality\">{$pageVars.city}</span>
      {/if}
      {if !empty($pageVars.county)}
         <span class=\"county\" itemprop=\"addressRegion\">{$pageVars.county}</span>
      {/if}
      {if !empty($pageVars.postcode)}
         <span class=\"postcode\" itemprop=\"postalCode\">{$pageVars.postcode}</span>
      {/if}
      {if !empty($pageVars.country)}
         <span class=\"country\" itemprop=\"addressCountry\">{$pageVars.country}</span>
      {/if}
   </div>"|strip scope=global}

{/strip}