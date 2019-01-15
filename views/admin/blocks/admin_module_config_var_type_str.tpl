[{if !isset($oConfig)}]
    [{assign var="oConfig" value=$oViewConf->getConfig()}]
[{/if}]

[{assign var="sAction" value=$confactions.$module_var}]
[{assign var="sJWToken" value=$oConfig->getConfigParam('strGraphQLApiToken')}]
[{assign var="blPromt" value=$oConfig->getConfigParam('blGraphQLApiTokenPromt')}]
[{assign var="sJWTName" value=$oConfig->getConfigParam('strGraphQLApiTokenName')}]

<script type="text/javascript">
<!--
window.onload = function(e) {
    var groupExp = document.getElementsByClassName("groupExp");
    var childDiv = groupExp[0].getElementsByTagName('div')[0];
    childDiv.classList.add("exp");

    var jwt = "[{$sJWToken}]";
    var blPromt = "[{$blPromt}]";
    var name = "[{$sJWTName}]";
    if(jwt && blPromt){
         prompt("[{oxmultilang ident="MODULE_COPY_TOKEN"}]", jwt);
    }
}

function deleteToken() {
    var yes = confirm("Are you sure you want to delete the access token?");
    var moduleForm = document.forms['module_configuration'];
    if (yes){
            document.module_configuration.fnc.value='deleteApiToken'
            moduleForm.submit();
    } else {
        return false;
    }
}

function createToken() {
    var moduleForm = document.forms['module_configuration'];
    var name = document.getElementById("apiTokenName").value;
    if (name){
            document.module_configuration.fnc.value='createApiToken'
            moduleForm.submit();
    } else {
        alert("Please give a name for the token.")
    }
}

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


[{if $sAction === "generateApiToken"}]
    [{capture name="credentials"}]
        [{if $oConfig->getConfigParam('strGraphQLApiToken')}]
            <p style="font-weight: normal; color: #787878;">The following long-lived access tokens are currently active.</p>
            [{foreach from=$oConfig->getConfigParam('arrGraphQLApiTokens') item=tokenDate key=tokenName}]
                <span style="font-weight: normal; color: #000;">[{$tokenName}]</span><br>
                <span style="padding: 0 0 5px; fontsize: 10px; font-weight: normal; color: #787878;">created at: [{$tokenDate}]</span>
                <input type="button" name="delete" value="&#x1f5d1;" onclick="deleteToken()">
            [{/foreach}]
        [{else}]
            <span style="font-weight: normal; color: #787878;">[{oxmultilang ident="SHOP_MODULE_strGraphQLApiTokenHelp"}]</span><br>
            <input id="apiTokenName" type="text" class="txt" style="width: 200px;" name="confstrs[[{$module_var}]]" value="[{$confstrs.$module_var}]">
            <span style="font-weight: normal; color: #000;">[{oxmultilang ident="MODULE_TokenName"}]</span><br>
            <input type="button" class="confinput" name="create" value="[{oxmultilang ident="MODULE_CREATE_TOKEN"}]" onclick="createToken()">
        [{/if}]
    [{/capture}]
[{elseif $sAction === "generateApiSecret"}]
    [{capture name="credentials"}]
        <input id="apiSecret" type="password" class="password" style="width: 300px;" name="confstrs[[{$module_var}]]" value="[{$confstrs.$module_var}]" readonly><br>
        <input id="apiSecretToggle" type="[{if $confstrs.$module_var}]button[{else}]hidden[{/if}]" class="confinput" name="toggle" value="[{oxmultilang ident="MODULE_SHOW"}]" onclick="toggleApiSecret()">
        <input type="submit" class="confinput" name="generate" value="[{if $confstrs.$module_var}][{oxmultilang ident="MODULE_REGENERATE"}][{else}][{oxmultilang ident="MODULE_GENERATE"}][{/if}]" onclick="Javascript:document.module_configuration.fnc.value='[{$confactions.$module_var}]'">
    [{/capture}]
[{else}]
    [{capture name="credentials"}]
        <input type="text" class="txt" style="width: 200px;" name="confstrs[[{$module_var}]]" value="[{$confstrs.$module_var}]" readonly><br>
        <input type="submit" class="confinput" name="generate" value="[{if $confstrs.$module_var}][{oxmultilang ident="MODULE_REGENERATE"}][{else}][{oxmultilang ident="MODULE_GENERATE"}][{/if}]" onclick="Javascript:document.module_configuration.fnc.value='[{$confactions.$module_var}]'">
    [{/capture}]
[{/if}]

[{if $confactions[$module_var] }]
    [{$smarty.capture.credentials}]
[{else}]
    [{$smarty.block.parent}]
[{/if}]