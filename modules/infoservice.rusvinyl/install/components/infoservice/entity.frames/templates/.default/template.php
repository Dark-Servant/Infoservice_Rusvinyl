<div class="rusv-main-rows" data-row-count="<?=count($arResult['ENTITY_ROWS'])?>"><?
    foreach ($arResult['ENTITY_ROWS'] as $entityRow):?>
    <div class="rusv-main-page"><?
        foreach ($entityRow as $entity):?>
        <div class="rusv-main-page-unit"><?
            $itemCount = count($entity[1]['ITEMS']);?>
            <div class="rusv-news-list" data-count="<?=$itemCount?>" data-id="<?=$entity[0] . $entity[1]['ID']?>"><?
            foreach ($entity[1]['ITEMS'] as $arNum => $arItem):?>
                <div class="rusv-news-item<?=$arNum ? ' rusv-hidden' : ''?>" data-id="<?=$arItem['ID']?>"><?
                    $previewImg = is_array($arItem['PREVIEW_PICTURE']) ? $arItem['PREVIEW_PICTURE']['SRC']
                                : SITE_TEMPLATE_PATH . '/images/news-default.svg';?>
                    <div class="rusv-news-item-image">
                        <a href="<?=$arItem['DETAIL_PAGE_URL']?>">
                            <img
                                class="preview_picture"
                                border="0"
                                src="<?=$previewImg?>"
                                title="<?=empty($arItem['PREVIEW_PICTURE']['TITLE']) ? '' : $arItem['PREVIEW_PICTURE']['TITLE']?>">
                        </a>
                    </div>
                    <div class="rusv-news-item-title">
                        <a href="<?=$arItem['DETAIL_PAGE_URL']?>"><?=$arItem['NAME']?></a>
                    </div>
                    <div class="rusv-news-item-preview-text">
                        <span><?=$arItem['PREVIEW_TEXT'];?></span>
                    </div>
                    <div class="rusv-news-item-preview-bottom"></div>
                </div><?
            endforeach;

            if ($itemCount):?>
                <div class="rusv-news-list-pages"><?
                for ($pUnit = 0; $pUnit < $itemCount; ++$pUnit):?>
                    <span
                        class="rusv-news-list-page <?if (!$pUnit):?>rusv-selected<?endif;?>"
                        data-id="<?=$entity[1]['ITEMS'][$pUnit]['ID']?>"></span><?
                endfor;?>
                </div><?
            endif?>
            </div>
            <div class="rusv-main-page-unit-title">
                <a href="<?=$entity[1]['LIST_PAGE_URL']?>"><?=$entity[1]['NAME']?></a>
            </div>
        </div><?
        endforeach;?>
    </div><?
    endforeach;?>
</div>