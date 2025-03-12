<?php
use Bitrix\Main\Loader;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Localization\Loc;
use Bitrix\Iblock\IblockTable;

require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_before.php';
Loc::loadMessages(__FILE__);

$module_id = 'news.api';

// Проверка прав
if (!$USER->IsAdmin()) {
    $APPLICATION->AuthForm(Loc::getMessage('ACCESS_DENIED'));
}

$iblocks = [];
if(Loader::includeModule('iblock')) {
    $res = IblockTable::getList(['select' => ['ID', 'NAME']]);
    while ($iblock = $res->fetch()) {
        $iblocks[$iblock['ID']] = '[' . $iblock['ID'] . '] ' . $iblock['NAME'];
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && check_bitrix_sessid()) {
    $newsIBlockId = !empty($_POST['NEWS_IBLOCK_ID']) ? (int)$_POST['NEWS_IBLOCK_ID'] : '';
    Option::set($module_id, 'NEWS_IBLOCK_ID', $newsIBlockId);
}

// Получаем сохраненное значение
$iblockId = Option::get($module_id, 'NEWS_IBLOCK_ID', '');

$tabControl = new CAdminTabControl('tabControl', [
    ['DIV' => 'edit1', 'TAB' => Loc::getMessage('NEWS_API_TAB_NAME'), 'TITLE' => Loc::getMessage('NEWS_API_TAB_TITLE')]
]);

$APPLICATION->SetTitle(Loc::getMessage('NEWS_API_TITLE'));
require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_after.php';
?>

<form method="POST">
    <?= bitrix_sessid_post(); ?>
    <?php $tabControl->Begin(); ?>
    <?php $tabControl->BeginNextTab(); ?>

    <tr>
        <td width="40%"><?=Loc::getMessage('NEWS_API_IBLOCK_TITLE')?>:</td>
        <td width="60%">
            <select name="NEWS_IBLOCK_ID">
                <?php foreach ($iblocks as $id => $name): ?>
                    <option value="<?= $id ?>" <?= $iblockId == $id ? 'selected' : '' ?>><?= htmlspecialcharsbx($name) ?></option>
                <?php endforeach; ?>
            </select>
        </td>
    </tr>

    <?php $tabControl->Buttons(); ?>
    <input type="submit" name="save" value="<?=Loc::getMessage('NEWS_API_SAVE_BUTTON')?>" class="adm-btn-save">
    <?php $tabControl->End(); ?>
</form>

<? require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_admin.php';