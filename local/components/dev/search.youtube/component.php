<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentName */
/** @var string $componentPath */
/** @var string $componentTemplate */

use	Bitrix\Main\Loader;

$arParams["IBLOCK_TYPE"] = trim($arParams["IBLOCK_TYPE"]);
if($arParams["IBLOCK_TYPE"] == '')
	$arParams["IBLOCK_TYPE"] = "news";
$arParams["IBLOCK_ID"] = trim($arParams["IBLOCK_ID"]);

if(!Loader::includeModule("iblock"))
{
	return;
}
if($USER->IsAuthorized()) {
	$el = new CIBlockElement;

	$arFilter = Array("IBLOCK_ID"=>$arParams["IBLOCK_ID"], 'ACTIVE'=>'Y');
	$res = CIBlockElement::GetList(Array(), $arFilter, false, false, ['NAME', 'ID']);
	while($arRes = $res->GetNext())
	{
		$arResult['FAV_ITEMS'][] = $arRes;
	}

	if(!empty($_GET['favorites']) && is_numeric($arParams['IBLOCK_ID'])){

		$arLoad = [
			'NAME' => $_GET['favorites'],
			'CODE' => $_GET['favorites'],
			'IBLOCK_ID' => $arParams["IBLOCK_ID"],
			'ACTIVE' => 'Y'
		];
		$el -> add($arLoad);
	}

	if(!empty($_GET['refavorites']) && is_numeric($arParams['IBLOCK_ID'])){

		$arLoad = [
			'NAME' => $_GET['favorites'],
			'CODE' => $_GET['favorites'],
			'IBLOCK_ID' => $arParams["IBLOCK_ID"],
			'ACTIVE' => 'Y'
		];

		$el -> Delete($_GET['refavorites']);
	}

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$arResult['QUERY'] = $_POST['search'];

		$search = urlencode($_POST['search']);
		$url = 'https://www.googleapis.com/youtube/v3/search?part=snippet&maxResults=50&q=' . $search . '&type=video&key='.$arParams['YOUTUBE_KEY'];
		$data = file_get_contents($url);
		$data = json_decode($data, true);

		foreach ($data['items'] as $key => $item) {
			$data['items'][$key]['SRC'] = 'https://www.youtube.com/embed/' . $item['id']['videoId'];
			foreach ($arResult['FAV_ITEMS'] as $favItem){
				if ($item['id']['videoId'] == $favItem['NAME']) {
					$data['items'][$key]['FAVORITES'] = 'Y';
					$data['items'][$key]['FAVORITES_ID'] = $favItem['ID'];
				}
			}
		}
		unset($item);

		$arResult['ITEMS'] = $data['items'];
	}

	$this->includeComponentTemplate();
}else{
	return;
}
?>
