;(function() {
    var ajaxURL = document.location.origin + '/local/components/infoservice/incidents.count/ajax.php?action=';
    var rusvSelector = {
        incidentsCountValue: '.rusv-incidents-count-value',
        editableLabel: '.rusv-editable',
        incidentsCountPopup: '.rusv-incidents-count-popup',
        modalArea: '.rusv-modal-area',
        incidentsCountInput: '.rusv-incidents-count-input',
        saveIncidentsCountButton: '.rusv-save-incidents-count-button'
    };
    var rusvClass = {
        noReaction: 'rusv-no-reaction',
        error: 'rusv-error',
    };
    var modalWndIds = {
        incidentsCount: 'incidents-count'
    };
    const DAY_SECOND_COUNT = 86400;

    /**
     * Показывает количество дней с учетом окончаний
     * 
     * @return void
     */
    var setIncidentDayCount = function() {
        var incidentsCountValue = $(rusvSelector.incidentsCountValue);
        var dayCount = parseInt(incidentsCountValue.attr('data-last-date')) * 1000;
        dayCount = Math.floor(((new Date()) - dayCount) / (DAY_SECOND_COUNT * 1000));

        var lastDigits = dayCount % 100;
        if (
            ((lastDigits > 4) && (lastDigits < 20))
            || !(lastDigits = dayCount % 10)
            || (lastDigits > 4)
        ) {
            dayCount += ' дней';

        } else if (lastDigits == 1) {
            dayCount += ' день';

        } else {
            dayCount += ' дня';
        }
        incidentsCountValue.html(incidentsCountValue.data('label').replace(/\#VALUE\#/ig, dayCount));
    }

    /**
     * Показывает модульное окно для установки нового
     * значения для количества дней без происшествий
     * 
     * @return void
     */
    var showIncidentsCountWnd = function() {
        $(this).trigger('shommodal', {
            ID: modalWndIds.incidentsCount,
            DATA_SELECTOR: rusvSelector.incidentsCountPopup
        });
    }

    /**
     * Сохраняет новое значения для количества дней без происшествий
     * 
     * @return void
     */
    var saveNewIncidentsCount = function() {
        var popupWindow = $(this).closest(rusvSelector.popupWindow);
        popupWindow.addClass(rusvClass.noReaction);

        var modalArea = $(rusvSelector.incidentsCountInput).closest(rusvSelector.modalArea);
        var countValue = $(rusvSelector.incidentsCountInput).val();
        if (countValue == '') {
            modalArea.addClass(rusvClass.error);
            popupWindow.removeClass(rusvClass.noReaction);
            return;
        }
        modalArea.removeClass(rusvClass.error);

        $.post(ajaxURL + 'new', {
            count: countValue
        }, answer => {
            popupWindow.removeClass(rusvClass.noReaction);
            if (!answer.result) return;

            $(document).trigger('closemodal', modalWndIds.incidentsCount);
            $(rusvSelector.editableLabel).attr('data-last-date', answer.data);
            setIncidentDayCount();
        });
    }

    $(document)
        .on('ready', setIncidentDayCount)
        .on('click', rusvSelector.editableLabel, showIncidentsCountWnd)
        .on('click', rusvSelector.saveIncidentsCountButton, saveNewIncidentsCount)
    ;
})();