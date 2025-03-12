<?php
namespace News;

use Bitrix\Main\Loader;
use Bitrix\Main\Application;
use Bitrix\Main\Config\Option;
use Bitrix\Iblock\ElementTable;
use Bitrix\Main\Engine\Controller;

class Api extends Controller
{
    private $module_id = 'news.api';
    private $iblockId = null;

    function __construct()
    {
        parent::__construct();
        $this->iblockId = Option::get($this->module_id, 'NEWS_IBLOCK_ID', '');
    }

    public function configureActions()
    {
        return [
            'list' => ['prefilters' => []],
            'detail' => ['prefilters' => []],
        ];
    }

    /**
     * Получение списка новостей
     * URL: /api/news.list
     */
    public function listAction($limit = 10)
    {
        if (!Loader::includeModule('iblock')) {
            return ['error' => 'IBlock module is not installed'];
        } elseif (!$this->iblockId) {
            return ['error' => 'IBlock not selected'];
        }

        $newsList = ElementTable::getList([
            //'select' => ['ID', 'NAME', 'PREVIEW_TEXT'],
            'filter' => ['IBLOCK_ID' => $this->iblockId, 'ACTIVE' => 'Y'],
            'limit' => (int)$limit,
            'order' => ['ID' => 'ASC'],
        ])->fetchAll();

        return $newsList;
    }

    /**
     * Получение детальной новости по ID
     * URL: /api/news.detail?id=1
     */
    public function detailAction($id)
    {
        if (!Loader::includeModule('iblock')) {
            return ['error' => 'IBlock module is not installed'];
        } elseif (!$this->iblockId) {
            return ['error' => 'IBlock not selected'];
        }

        $news = ElementTable::getList([
            'select' => ['ID', 'NAME', 'DETAIL_TEXT'],
            'filter' => ['IBLOCK_ID' => $this->iblockId, 'ACTIVE' => 'Y', 'ID' => (int)$id],
            'limit' => 1,
        ])->fetch();

        if (!$news) {
            return ['error' => 'News not found'];
        }

        return $news;
    }
}
