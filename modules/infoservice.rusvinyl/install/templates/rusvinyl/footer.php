<?
use Bitrix\Main\Localization\Loc;

?></div></div>
    </div>

    <div class="rusv-footer">
        <div class="rusv-footer-menu"><?
        $APPLICATION->IncludeComponent(
            'bitrix:menu', 'rusviny.horizontal_multilevel', 
            [
                'ROOT_MENU_TYPE' => 'footer',
                'MAX_LEVEL' => '1',
                'USE_EXT' => 'Y',
            ]
        );?></div>
        <div class="rusv-footer-icons">
            <a class="rusv-icon rusv-video-icon" href="#" title="<?=Loc::getMessage('ICON_CAMERA_TITLE')?>">
                <img src="<?=SITE_TEMPLATE_PATH?>/images/video.svg">
            </a>
            <a class="rusv-icon rusv-teacher-icon" href="#" title="<?=Loc::getMessage('ICON_ITS_KONSULTANT_PLUS_TITLE')?>">
                <img src="<?=SITE_TEMPLATE_PATH?>/images/teacher.svg">
            </a>
            <a class="rusv-icon rusv-circle-icon" href="#" title="<?=Loc::getMessage('ICON_WEB_SERVISDESK_TITLE')?>">
                <img src="<?=SITE_TEMPLATE_PATH?>/images/circle.svg">
            </a>
            <a class="rusv-icon rusv-text-icon" href="#" title="<?=Loc::getMessage('ICON_DIRECTUM_EDMS_TITLE')?>">
                <img src="<?=SITE_TEMPLATE_PATH?>/images/text.svg">
            </a>
        </div>
    </div><?$APPLICATION->IncludeComponent('infoservice:services', '');?>
</body>
</html>