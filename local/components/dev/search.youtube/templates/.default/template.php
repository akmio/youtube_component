<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
?>
<div class="search-page">
    <form action="" method="post">
        <input type="text" name="search" value="<?=$arResult['QUERY']?>"/>
        <input type="submit" value="<?=GetMessage("SEARCH_GO")?>" />
    </form>
    <br/>

    <?if(empty($arResult['QUERY'])){?>
        <?ShowNote(GetMessage("SEARCH_QUERY"));?>
    <?}elseif(count($arResult['ITEMS'])>0){?>
        <?foreach ($arResult['ITEMS'] as $arItem){?>
            <iframe width="560" height="315" src="<?=$arItem['SRC']?>" title="<?=$arItem['snippet']['title']?>" frameborder="0" allow="accelerometer; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
            <?if($arItem['FAVORITES'] == 'Y'){?>
                <a href="<?=$APPLICATION->GetCurPage();?>?refavorites=<?=$arItem['FAVORITES_ID']?>" style="float: right;">
                    <div style="background-image: url(/local/components/dev/search.youtube/images/love-it-active.png);width: 25px; height: 25px; background-repeat: no-repeat; background-position: center;"></div>
                </a>
            <?}else{?>
                <a href="<?=$APPLICATION->GetCurPage();?>?favorites=<?=$arItem['id']['videoId']?>" style="float: right;">
                    <div style="background-image: url(/local/components/dev/search.youtube/images/love-it.png);width: 25px; height: 25px; background-repeat: no-repeat; background-position: center;"></div>
                </a>
            <?}?>
        <?}?>
    <?}else{?>
        <?ShowNote(GetMessage("SEARCH_NOTHING_TO_FOUND"));?>
    <?}?>
</div>