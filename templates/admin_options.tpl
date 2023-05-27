{$startform}
   <div class="pageoverflow">
      <p class="pagetext">{$title_customModuleName}:</p>
      <p class="pageinput">{$input_customModuleName}</p>
   </div>
   <div class="pageoverflow">
      <p class="pagetext">{$title_adminSection}:</p>
      <p class="pageinput">{$input_adminSection}</p>
   </div>

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

{$endform}