<?php

/**
 * @author MetalMichael
 * @copyright 2012
 */

//Config
require('config.php');
//Header
require(RESOURCE_DIR . 'header.php');
//Footer
require(RESOURCE_DIR . 'footer.php');

$User->enforceLogin(true);

$DB->query("SELECT * FROM site_config");
if($DB->record_count() !== 1) die('Fatal Settings Error');
$Settings = $DB->next_record(MYSQLI_ASSOC);

showHeader('Admin Settings', array('search'=>false, 'navigation'=>true, 'login'=>false));

?>
<h2>Settings</h2>
<form action="" method="post">
    <table>
        <tr>
            <td class="table-header">Radio Controls</td>
        </tr>
        <tr>
            <td class="label">Radio Enabled</td>
            <td><input type="checkbox" name="enabled" <?=($Settings['running']) ? 'checked="checked"' : ''?> /></td>
        </tr>
        <tr>
            <td class="label">Radio Name</td>
            <td><input type="text" name="radioname" value="<?=display_str($Settings['radioName'])?>" /></td>
        </tr>
        <tr>
            <td class="table-header">Radio Settings</td>
        </tr>
        <tr>
            <td class="label">SomeSetting</td>
            <td><input type="text" name="something" /></td>
        </tr>
    </table>
</form>

<?php

showFooter();

?>