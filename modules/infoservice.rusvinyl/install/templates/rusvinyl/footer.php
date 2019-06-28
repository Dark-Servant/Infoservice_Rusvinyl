</div></div>
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
            <img class="rusv-icon rusv-video-icon" src="<?=SITE_TEMPLATE_PATH?>/images/video.svg">
            <img class="rusv-icon rusv-teacher-icon" src="<?=SITE_TEMPLATE_PATH?>/images/teacher.svg">
            <img class="rusv-icon rusv-circle-icon" src="<?=SITE_TEMPLATE_PATH?>/images/circle.svg">
            <img class="rusv-icon rusv-text-icon" src="<?=SITE_TEMPLATE_PATH?>/images/text.svg">
        </div>
    </div>
</body>
</html>