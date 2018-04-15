<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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
$this->setFrameMode(true);

$res = CIBlock::GetByID( $arParams['IBLOCK_ID'] );
if($ar_res = $res->GetNext())
    $arParams['IBLOCK_CODE'] = $ar_res['CODE'];

echo "<section class='article-list
                      article-list_type_{$arParams['IBLOCK_CODE']}
                      article-list_id_{$arParams['IBLOCK_ID']}'>";

if( $arParams["DISPLAY_TOP_PAGER"] ) {
    echo "<div class='article-list__pager article-list__pager_top'>{$arResult["NAV_STRING"]}</div>";
}

echo '<ul>';
foreach($arResult["ITEMS"] as $arItem) {
    $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'],
        CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
    $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'],
        CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array(
            "CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')
            ) );

    $link = $arParams["HIDE_LINK_WHEN_NO_DETAIL"] ||
        ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"]) ? $arItem["DETAIL_PAGE_URL"] : false;

    printf('<li><article class="article-element article-element_type_%s" id="%s">',
        $arParams['IBLOCK_CODE'],
        $this->GetEditAreaId($arItem['ID']) );

        if( "Y" == $arParams["DISPLAY_PICTURE"] ) {
            if( is_array($arItem["PREVIEW_PICTURE"]) ) {
                $pp_class = 'article-element__picture';

                $pic = sprintf('%s', bx_get_image( $arItem["PREVIEW_PICTURE"], $args, true ));
                if( "Y" == $arParams['PICTURE_DETAIL_URL'] && !empty($arItem["DETAIL_PICTURE"]["SRC"]) ) {
                    $pic = sprintf('<a href="%s" class="zoom">%s</a>', $arItem["DETAIL_PICTURE"]["SRC"], $pic);
                }
                elseif ( $link ) {
                    $pic = sprintf('<a href="%s">%s</a>', $link, $pic);
                }

                printf('<div class="%s">%s</div>', $pp_class, $pic);
            }
            else {
                echo '<div class="article-element__picture article-element_empty"></div>';
            }
        }

        if( "Y" == $arParams["DISPLAY_NAME"] && $arItem["NAME"] ) {
            if( ! $arParams["NAME_TAG"] ) $arParams["NAME_TAG"] = 'h3';
            echo $link ?
                sprintf('<%1$s class="article-element__title"><a href="%3$s">%2$s</a></%1$s>',
                    $arParams["NAME_TAG"], $arItem["NAME"], $link) :
                sprintf('<%1$s class="article-element__title">%2$s</%1$s>', $arParams["NAME_TAG"], $arItem["NAME"]);
        }

        if( "Y" == $arParams["DISPLAY_DATE"] && $arItem["DISPLAY_ACTIVE_FROM"]) {

            printf('<div class="article-element__date">%s</div>', $arItem["DISPLAY_ACTIVE_FROM"]);
        }

        if( "Y" == $arParams["DISPLAY_PREVIEW_TEXT"] && $arItem["PREVIEW_TEXT"]) {

            echo sprintf('<div class="article-element__description">%s</div>', $arItem["PREVIEW_TEXT"]);
        }

        // echo "<div class='clear'></div>";
        // foreach($arItem["FIELDS"] as $code => $value) {
        //  echo "<small>";
        //  echo GetMessage("IBLOCK_FIELD_" . $code) . ":&nbsp;" . $value;
        //  echo "</small><br />";
        // }

        // foreach($arItem["DISPLAY_PROPERTIES"] as $pid => $arProperty) {
        //  echo "<small>";
        //  echo "{$arProperty["NAME"]}:&nbsp;";
        //  if( is_array($arProperty["DISPLAY_VALUE"]) ) {
        //      echo implode("&nbsp;/&nbsp;", $arProperty["DISPLAY_VALUE"]);
        //  }
        //  else {
        //      echo $arProperty["DISPLAY_VALUE"];
        //  }
        //  echo "</small><br />";
        // }

    echo "</article></li>";
}
echo '</ul>';

if( $arParams["DISPLAY_BOTTOM_PAGER"] ) {
    echo "<div class='article-list__pager article-list__pager_bottom'>{$arResult["NAV_STRING"]}</div>";
}

echo "</section>";
