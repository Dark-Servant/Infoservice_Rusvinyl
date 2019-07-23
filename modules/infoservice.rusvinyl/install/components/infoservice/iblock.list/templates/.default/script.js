;(function() {
    var ajaxURL = document.location.origin + '/local/components/infoservice/iblock.list/ajax.php?action=';
    var handlebarUnits= {
        thanksList: {selector: '#rusv-iblock-thanks-list-template'},
        questionList: {selector: '#rusv-iblock-question-list-template'},
        listPages: {selector: '#rusv-iblock-list-pages-template'},
    };
    var rusvSelector = {
        contentData: '.rusv-body-content-data',
        iblockList: '.rusv-iblock-list',
        iblockPages: '.rusv-iblock-pages',
        modalArea: '.rusv-modal-area',
        controlButtons: '.rusv-iblock-list-control-buttons',
        newUnitButton: '.rusv-iblock-new-unit-button',
        addNewUnitPopup: '.rusv-add-new-#CODE#-popup',
        popupWindow: '.popup-window',
        addUnitButton: '.rusv-iblock-add-unit-button',
        otherListPage: '.rusv-iblock-list-page:not(.rusv-iblock-list-current-page)',
        listUnit: '.rusv-list-unit',
        unitTextMore: '.rusv-unit-text-more-value',
    };
    var rusvClass = {
        noReaction: 'rusv-no-reaction',
        error: 'rusv-error',
        opened: 'rusv-unit-text-opened'
    };
    var modalWndIds = {
        addNewUnit: 'rusv-add-new-#CODE#'
    };
    var mainData;

    /**
     * Получение списка элементов на конкретной странице
     * 
     * @param pageNumber - номер страницы (0 и 1 считаются одним и тем же)
     * @return void
     */
    var loadUnits = function(pageNumber) {
        $(rusvSelector.contentData).addClass(rusvClass.noReaction);

        $.get(ajaxURL + 'list', {
            code: mainData.CODE,
            page: pageNumber
        }, answer => {
            $(rusvSelector.contentData).removeClass(rusvClass.noReaction);
            if (!answer.result) return;

            $(rusvSelector.iblockList).html(handlebarUnits[mainData.CODE + 'List']({UNITS: answer.data.list}));
            $(rusvSelector.iblockList).attr('data-count', answer.data.list.length);

            var pages = [...Array(answer.data.pages.count).keys()].map(pageI => {
                var realNumber = pageI + 1;
                return {
                    NUMBER: realNumber,
                    CURRENT: realNumber == answer.data.pages.current
                };
            });
            $(rusvSelector.iblockPages).html('<!-- ' + handlebarUnits.listPages({PAGES: pages}) + ' -->');
            $(rusvSelector.iblockPages).attr('data-many-pages', +(pages.length > 1));
        });
    }

    /**
     * Обработчик инициализации страницы при ее полной готовности
     * 
     * @return void
     */
    var initPage = function() {
        mainData = $(rusvSelector.iblockList).data('main-data');
        rusvSelector.addNewUnitPopup = rusvSelector.addNewUnitPopup.replace('#CODE#', mainData.CODE);
        modalWndIds.addNewUnit = modalWndIds.addNewUnit.replace('#CODE#', mainData.CODE);

        for (var i in handlebarUnits) {
            var html = $(handlebarUnits[i].selector).html();
            if (!html) continue;

            if (typeof(handlebarUnits[i].registerName) == 'string')
                Handlebars.registerPartial(handlebarUnits[i].registerName, html);
            handlebarUnits[i] = Handlebars.compile(html);
        }
        loadUnits(0);
    }

    /**
     * Обработчик наатия кнопки добавления. Показывает модальное окно
     * 
     * @return void
     */
    var showModalNewUnit = function() {
        $(this).trigger('shommodal', {
            ID: modalWndIds.addNewUnit,
            DATA_SELECTOR: rusvSelector.addNewUnitPopup
        });
    }

    /**
     * Обработчик нажатия кнопки "Сохранить"
     * 
     * @return void
     */
    var saveUnit = function() {
        var popupWindow = $(this).closest(rusvSelector.popupWindow);
        popupWindow.addClass(rusvClass.noReaction);

        var data = new FormData;
        data.append('code', mainData.CODE);

        popupWindow.find(rusvSelector.modalArea + ':not(:last)').addClass(rusvClass.error);
        popupWindow.find('*[name]').each((number, unit) => {
            if (unit.type != 'file') {
                var value = unit.value.trim();
                if (value) {
                    $(unit).closest(rusvSelector.modalArea).removeClass(rusvClass.error);
                    data.append(unit.name, unit.value);
                }

            } else if (unit.files.length) {
                    data.append(unit.name, unit.files[0]);
            }
        });
        if (popupWindow.find(rusvSelector.modalArea + '.' + rusvClass.error).length)
            return popupWindow.removeClass(rusvClass.noReaction);

        $.ajax({
            url: ajaxURL + 'new',
            data: data,
            processData: false,
            contentType: false,
            type: 'POST',
            success: answer => {
                popupWindow.removeClass(rusvClass.noReaction);
                if (!answer.result) return;

                $(document).trigger('closemodal', modalWndIds.addNewUnit);
                loadUnits(0);
            }
        });
    }

    /**
     * Обработчик выбора страницы
     *
     * @return void
     */
    var setPage = function() {
        loadUnits($(this).attr('data-number'));
    }

    /**
     * Обработчик клика по тексте "Читать еще"
     * 
     * @return void
     */
    var showMoreText = function() {
        $(rusvSelector.iblockList).find(rusvSelector.listUnit).removeClass(rusvClass.opened);

        $(this).closest(rusvSelector.listUnit).addClass(rusvClass.opened);
    }

    $(document)
        .on('ready', initPage)
        .on('click', rusvSelector.newUnitButton, showModalNewUnit)
        .on('click', rusvSelector.addUnitButton, saveUnit)
        .on('click', rusvSelector.otherListPage, setPage)
        .on('click', rusvSelector.unitTextMore, showMoreText)
    ;
})();