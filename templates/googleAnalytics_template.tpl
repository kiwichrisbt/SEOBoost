{* googleAnalytics_template.tpl - v1.1 - 07Mar19

   - v1.1 - 07Mar19 - removed 'defer' & updated template for debug output
   - v1.0 - 08Feb19 - moved to smarty template

   See https://developers.google.com/analytics/devguides/collection/analyticsjs/ for details.

***************************************************************************************************}
{if empty($GAId)}
   <{if $GA_debug} {/if}!-- SEOBoost: Google Analytics - Google Tracking Id - NOT SET {if $GA_debug}- debug ON{/if} -->

{else}
   <{if $GA_debug} {/if}!-- SEOBoost: Google Analytics {if $GA_debug}- debug ON{/if} --{if $GA_debug} {/if}>
   <{if $GA_debug} {/if}script>{literal}
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');{/literal}
      ga('create', '{$GAId}', 'auto');
      ga('send', 'pageview');
   <{if $GA_debug} {/if}/script>
{/if}