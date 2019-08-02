;(function() {
    var ajaxURL = document.location.origin + '/local/components/infoservice/iblock.list/ajax.php?action=';
    var handlebarUnits= {
        thanksList: {selector: '#rusv-iblock-thanks-list-template'},
        questionList: {selector: '#rusv-iblock-question-list-template'},
        employee_announceList: {selector: '#rusv-iblock-employee-announce-list-template'},
    };
    var rusvSelector = {
        unitList: '.rusv-unit-list',
        modalArea: '.rusv-modal-area',
        newUnitButton: '.rusv-iblock-new-unit-button',
        addNewUnitPopup: '.rusv-add-new-#CODE#-popup',
        popupWindow: '.popup-window',
        addUnitButton: '.rusv-iblock-add-unit-button',
    };
    var rusvClass = {
        noReaction: 'rusv-no-reaction',
        error: 'rusv-error',
    };
    var modalWndIds = {
        addNewUnit: 'rusv-add-new-#CODE#'
    };
    var mainData;

    /**
     * Обработчик инициализации страницы при ее полной готовности
     * 
     * @return void
     */
    var initPage = function() {
        mainData = $(rusvSelector.unitList).data('main-data');
        rusvSelector.addNewUnitPopup = rusvSelector.addNewUnitPopup.replace('#CODE#', mainData.CODE);
        modalWndIds.addNewUnit = modalWndIds.addNewUnit.replace('#CODE#', mainData.CODE);

        for (var i in handlebarUnits) {
            var html = $(handlebarUnits[i].selector).html();
            if (!html) continue;

            if (typeof(handlebarUnits[i].registerName) == 'string')
                Handlebars.registerPartial(handlebarUnits[i].registerName, html);
            handlebarUnits[i] = Handlebars.compile(html);
        }
        $(document).trigger(
            'list+pages:init',
            {
                ajaxURL: ajaxURL + 'list&code=' + mainData.CODE,
                handlebar: {
                    unitList: handlebarUnits[mainData.CODE + 'List']
                }
            }
        );
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
                $(document).trigger('list+pages:update');
            }
        });
    }

    $(document)
        .on('ready', initPage)
        .on('click', rusvSelector.newUnitButton, showModalNewUnit)
        .on('click', rusvSelector.addUnitButton, saveUnit)
    ;
})();