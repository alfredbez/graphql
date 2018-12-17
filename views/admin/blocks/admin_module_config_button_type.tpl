[{$smarty.block.parent}]

[{if $var_type == 'btn'}]
    <input type=text  class="txt" style="width: 250px;" name="confstrs[[{$module_var}]]" value="[{$confstrs.$module_var}]" readonly><br>
    <input type="submit" class="confinput" name="save" value="Generate" onclick="Javascript:document.module_configuration.fnc.value='generate'">
[{/if}]