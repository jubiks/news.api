<?if(!check_bitrix_sessid()) return;?>
<?
    echo CAdminMessage::ShowNote(GetMessage('MOD_INST_OK'));
?>
<form action="<?echo $APPLICATION->GetCurPage()?>">
	<input type="hidden" name="lang" value="<?echo LANG?>">
	<input type="submit" name="" value="<?echo GetMessage("MOD_BACK")?>">	
	<!--input type="button" name="" value="<?echo GetMessage("MOD_GO_SETTINGS")?>"<?echo " onclick=\"location.assign('/bitrix/admin/settings.php?lang=".LANG."&mid=news.api&mid_menu=1')\""?> /-->
</form>	