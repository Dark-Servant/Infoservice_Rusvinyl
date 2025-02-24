<?
if (!empty($arParams['BUTTON_COMPONENT'])):?>
<div class="rusv-news-detail-buttons"><?
$APPLICATION->IncludeComponent(
    $arParams['BUTTON_COMPONENT'], '',
    [
        'ELEMENT_ID' => $arResult['ELEMENT']['ID'],
        'IBLOCK_TYPE' => $arResult['ELEMENT']['IBLOCK_TYPE_ID'],
    ]
);?>
</div><?
endif;

$APPLICATION->IncludeComponent(
    'bitrix:news.detail', 'rusvinyl.default',
    [
        'ACTIVE_DATE_FORMAT' => 'd.m.Y',
        'ADD_ELEMENT_CHAIN' => 'N',
        'ADD_SECTIONS_CHAIN' => 'Y',
        'AJAX_MODE' => 'N',
        'AJAX_OPTION_ADDITIONAL' => '',
        'AJAX_OPTION_HISTORY' => 'N',
        'AJAX_OPTION_JUMP' => 'N',
        'AJAX_OPTION_STYLE' => 'Y',
        'BROWSER_TITLE' => '-',
        'CACHE_TYPE' => 'N',
        'CHECK_DATES' => 'Y',
        'DETAIL_URL' => '',
        'DISPLAY_BOTTOM_PAGER' => 'Y',
        'DISPLAY_DATE' => 'Y',
        'DISPLAY_NAME' => 'Y',
        'DISPLAY_PICTURE' => 'Y',
        'DISPLAY_PREVIEW_TEXT' => 'Y',
        'DISPLAY_TOP_PAGER' => 'N',
        'ELEMENT_CODE' => '',
        'ELEMENT_ID' => $arResult['ELEMENT']['ID'],
        'FIELD_CODE' => [''],
        'IBLOCK_ID' => '',
        'IBLOCK_TYPE' => $arResult['ELEMENT']['IBLOCK_TYPE_ID'],
        'IBLOCK_URL' => '',
        'INCLUDE_IBLOCK_INTO_CHAIN' => 'Y',
        'MESSAGE_404' => '',
        'META_DESCRIPTION' => '-',
        'META_KEYWORDS' => '-',
        'PAGER_BASE_LINK_ENABLE' => 'N',
        'PAGER_SHOW_ALL' => 'N',
        'PAGER_TEMPLATE' => '.default',
        'PAGER_TITLE' => '',
        'PROPERTY_CODE' => [],
        'SET_BROWSER_TITLE' => 'Y',
        'SET_CANONICAL_URL' => 'N',
        'SET_LAST_MODIFIED' => 'N',
        'SET_META_DESCRIPTION' => 'Y',
        'SET_META_KEYWORDS' => 'Y',
        'SET_STATUS_404' => 'N',
        'SET_TITLE' => 'Y',
        'SHOW_404' => 'N',
        'STRICT_SECTION_CHECK' => 'N',
        'USE_PERMISSIONS' => 'N',
        'USE_SHARE' => 'N',
        'SHOW_VIDEO' => $arResult['SHOW_VIDEO'] ?: false
    ]
);?>
<?$APPLICATION->IncludeComponent('bitrix:rating.vote', '', [
        'ENTITY_TYPE_ID' => 'IBLOCK_ELEMENT',
        'ENTITY_ID' => $arResult['ELEMENT']['ID'],
        'OWNER_ID' => $arResult['ELEMENT']['CREATED_BY'],
        'USER_HAS_VOTED' => 'Y',
        'TOTAL_VOTES' => '0',
        'TOTAL_POSITIVE_VOTES' => '0',
        'TOTAL_NEGATIVE_VOTES' => '0',
        'TOTAL_VALUE' => '0'
    ], null,
    ['HIDE_ICONS' => 'Y']
);?>
<div class="rusv-news-detail-comments"><?
    $APPLICATION->IncludeComponent(
        'bitrix:forum.comments', '', [
            'ALLOW_ALIGN' => 'Y',
            'ALLOW_ANCHOR' => 'Y',
            'ALLOW_BIU' => 'Y',
            'ALLOW_CODE' => 'Y',
            'ALLOW_FONT' => 'Y',
            'ALLOW_HTML' => 'Y',
            'ALLOW_IMG' => 'Y',
            'ALLOW_LIST' => 'Y',
            'ALLOW_MENTION' => 'Y',
            'ALLOW_NL2BR' => 'Y',
            'ALLOW_QUOTE' => 'Y',
            'ALLOW_SMILES' => 'Y',
            'ALLOW_TABLE' => 'Y',
            'ALLOW_VIDEO' => 'Y',
            'CACHE_TIME' => '0',
            'CACHE_TYPE' => 'A',
            'DATE_TIME_FORMAT' => 'd.m.Y H:i:s',
            'EDITOR_CODE_DEFAULT' => 'N',
            'ENTITY_ID' => $arResult['ELEMENT']['ID'],
            'ENTITY_TYPE' => 'IM',
            'ENTITY_XML_ID' => INFS_FORUM_PREFIX . $arResult['ELEMENT']['ID'],
            'FORUM_ID' => $arResult['OPTIONS']['Forums'][INFS_DETAIL_PAGE_FORUM],
            'IMAGE_HTML_SIZE' => '0',
            'IMAGE_SIZE' => '600',
            'MESSAGES_PER_PAGE' => '20000',
            'NAME_TEMPLATE' => '',
            'PAGE_NAVIGATION_TEMPLATE' => '',
            'PERMISSION' => 'Y',
            'PREORDER' => 'N',
            'SET_LAST_VISIT' => 'N',
            'SHOW_MINIMIZED' => 'N',
            'SHOW_RATING' => 'N',
            'SUBSCRIBE_AUTHOR_ELEMENT' => 'N',
            'URL_TEMPLATES_PROFILE_VIEW' => '',
            'URL_TEMPLATES_READ' => '',
            'USE_CAPTCHA' => 'N',
            'COMPONENT_TEMPLATE' => '.default',
        ]);?>
</div>