<?
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

Class news_api extends CModule
{
    var $MODULE_ID = "news.api";
    var $MODULE_GROUP_RIGHTS = "N";
    public $MODULE_VERSION;
	public $MODULE_VERSION_DATE;
	public $MODULE_NAME;
	public $MODULE_DESCRIPTION;
	public $PARTNER_NAME;
	public $PARTNER_URI;
    
    function __construct()
    {
        $arModuleVersion = array();
        
        include($this->GetModInstPath()."/version.php");
    
        if (is_array($arModuleVersion) && array_key_exists("VERSION", $arModuleVersion))
        {
            $this->MODULE_VERSION = $arModuleVersion["VERSION"];
            $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        }

        $this->MODULE_NAME = Loc::getMessage("NEWS_API_MODULE_NAME");
        $this->MODULE_DESCRIPTION = Loc::getMessage("NEWS_API_MODULE_DESCRIPTION");
        $this->PARTNER_NAME = Loc::getMessage("JUBIKS_GEOLOCATION_PARTNER_NAME");
		$this->PARTNER_URI = Loc::getMessage("JUBIKS_GEOLOCATION_PARTNER_URI");
    }

    private function GetModInstPath()
    {
        return __DIR__;
    }

    public function InstallFiles()
    {
        CopyDirFiles($this->GetModInstPath() ."/routes/", $_SERVER["DOCUMENT_ROOT"]."/bitrix/routes/", true, true);
    }

    public function UnInstallFiles()
    {
        DeleteDirFiles($this->GetModInstPath() ."/routes/", $_SERVER["DOCUMENT_ROOT"]."/bitrix/routes/");
    }

    private function registerRoutes()
    {
        $configuration = Bitrix\Main\Config\Configuration::getInstance();
        $routing = $configuration->get('routing');

        if(is_array($routing) && isset($routing['config'])) {
            if(is_array($routing['config']) && !in_array("news.api.php", $routing['config'])) {
                $routing['config'][] = "news.api.php";
            }
        } else {
            $routing = [
                'config' => ["news.api.php"]
            ];
        }

        $configuration->add('routing', $routing);
        $configuration->saveConfiguration();
    }

    private function unregisterRoutes()
    {
        $configuration = Bitrix\Main\Config\Configuration::getInstance();
        $routing = $configuration->get('routing');

        if(is_array($routing) && isset($routing['config'])) {
            if(is_array($routing['config']) && in_array("news.api.php", $routing['config'])) {
                unset($routing['config'][array_search("news.api.php", $routing['config'])]);

                $configuration->add('routing', $routing);
                $configuration->saveConfiguration();
            }
        }
    }
    
    public function DoInstall()
    {
        global $APPLICATION;

        $this->InstallFiles();
        
        RegisterModule($this->MODULE_ID);
        $this->registerRoutes();

        $APPLICATION->IncludeAdminFile(GetMessage("NEWS_API_INSTALL_MODULE"), $this->GetModInstPath()."/step.php");
    }

    public function DoUninstall()
    {
        global $APPLICATION, $step;
        
        if($step == 2){

            if($_REQUEST["savedata"] != 'Y')
                COption::RemoveOption($this->MODULE_ID);

            $this->UnInstallFiles();
            $this->unregisterRoutes();
            
            UnRegisterModule($this->MODULE_ID);
            
            $APPLICATION->IncludeAdminFile(GetMessage("NEWS_API_UNINSTALL_MODULE"), $this->GetModInstPath()."/unstep2.php");
            
        }else{
			$APPLICATION->IncludeAdminFile(GetMessage("NEWS_API_UNINSTALL_MODULE"), $this->GetModInstPath()."/unstep1.php");
		}
    }
}
