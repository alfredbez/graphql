<script type="text/javascript">
<!--
window.onload = function(e) {
    var groupExp = document.getElementsByClassName("groupExp");
    var childDiv = groupExp[0].getElementsByTagName('div')[0];
    childDiv.classList.add("exp");
};

function toggleApiSecret() {
  var input = document.getElementById("apiSecret");
  var button = document.getElementById("apiSecretToggle");
  if (input.type === "password") {
    input.type = "text";
    input.classList.remove("password");
    input.classList.add("txt");
    button.value = "[{oxmultilang ident="MODULE_HIDE"}]";
  } else {
    input.type = "password";
    input.classList.remove("txt");
    input.classList.add("password");
    button.value = "[{oxmultilang ident="MODULE_SHOW"}]";
  }
}
//-->
</script>

[{assign var="secret" value=$confactions.$module_var}]
[{if $secret === "generateApiSecret"}]
    [{capture name="credentials"}]
        <input id="apiSecret" type="password" class="password" style="width: 300px;" name="confstrs[[{$module_var}]]" value="[{$confstrs.$module_var}]" readonly><br>
        <input id="apiSecretToggle" type="[{if $confstrs.$module_var}]button[{else}]hidden[{/if}]" class="confinput" name="toggle" value="[{oxmultilang ident="MODULE_SHOW"}]" onclick="toggleApiSecret()">
    [{/capture}]
[{else}]
    [{capture name="credentials"}]
        <input type="text" class="txt" style="width: 200px;" name="confstrs[[{$module_var}]]" value="[{$confstrs.$module_var}]" readonly><br>
    [{/capture}]
[{/if}]

[{if $confactions[$module_var] }]
    [{$smarty.capture.credentials}]
    <input type="submit" class="confinput" name="generate" value="[{if $confstrs.$module_var}][{oxmultilang ident="MODULE_REGENERATE"}][{else}][{oxmultilang ident="MODULE_GENERATE"}][{/if}]" onclick="Javascript:document.module_configuration.fnc.value='[{$confactions.$module_var}]'">
[{else}]
    [{$smarty.block.parent}]
[{/if}]