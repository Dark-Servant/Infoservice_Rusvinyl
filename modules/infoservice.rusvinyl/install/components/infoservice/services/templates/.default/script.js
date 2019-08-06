;(function() {
    var ajaxURL = document.location.origin + '/local/components/infoservice/services/ajax.php?action=';
    var handlebarUnits= {
    };
    var rusvSelector = {
        addServiceButton: '.rusv-add-service-button',
        popupWindow: '.popup-window',
        modalArea: '.rusv-modal-area'
    };
    var rusvClass = {
        noReaction: 'rusv-no-reaction',
        error: 'rusv-error'
    };
    var modalWndIds = {};
    var options;

    /**
     * Показывает модальное окно для добавления элемента в инфоблок
     * для конкретного сервиса
     * 
     * @param event - данные события
     * @param code - символьные код инфоблока, созданный для сервисов
     * @return boolean
     */
    var showServiceWindow =  function(event ,code) {
        var wndId = code.replace(/[^a-z\d]+/ig, '-');
        var wndClass = '.' + wndId + '-popup';
        if (!$(wndClass).length && !modalWndIds[code]) return;

        modalWndIds[code] = wndId;
        $(document).trigger('shommodal', {
            ID: wndId,
            DATA_SELECTOR: wndClass
        });
        return false;
    }

    /**
     * Обработчик нажатия кнопки "Добавить" в модальных окнах
     * для сервисов
     *
     * @return void
     */
    var addService = function() {
        var popupWindow = $(this).closest(rusvSelector.popupWindow);
        popupWindow.addClass(rusvClass.noReaction);

        var code = $(this).data('service-code');
        var data = new FormData;

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
            url: ajaxURL + 'new&code=' + code,
            data: data,
            processData: false,
            contentType: false,
            type: 'POST',
            success: answer => {
                popupWindow.removeClass(rusvClass.noReaction);
                if (!answer.result) return;

                $(document).trigger('closemodal', code.replace(/[^a-z\d]+/ig, '-'));
            }
        });
    }

    /**
     * Обработчик инициализации страницы при ее полной готовности
     * 
     * @return void
     */
    var initPage = function() {
        for (var i in handlebarUnits) {
            var html = $(handlebarUnits[i].selector).html();
            if (!html) continue;

            if (typeof(handlebarUnits[i].registerName) == 'string')
                Handlebars.registerPartial(handlebarUnits[i].registerName, html);
            handlebarUnits[i] = Handlebars.compile(html);
        }
        if (typeof(serviceOptions) == 'undefined') return;

        options = serviceOptions;
        for (let iblockId in options) {
            $(document).on('click', 'a[href="' + options[iblockId].LIST_PAGE_URL + '"]',
                event => {
                    return showServiceWindow(event, options[iblockId].CODE);
                }
            );
        }
    }

    $(document)
        .on('ready', initPage)
        .on('click', rusvSelector.addServiceButton, addService)
    ;
})();