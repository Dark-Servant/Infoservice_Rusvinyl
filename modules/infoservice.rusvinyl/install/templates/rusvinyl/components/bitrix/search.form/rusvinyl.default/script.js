;(function() {
    var rusvSelector = {
        searchLayer: '.rusv-search-layer',
        searchForm: '.rusv-search-form',
        searchFormTitle: '.rusv-search-form-title',
        searchFormInput: '.rusv-search-form-input',
        searchFormButton: '.rusv-search-form-button'
    };
    var rusvClass = {
        hidden: 'rusv-hidden'
    };
    // displayCount указывает скрыть ли поле ввода для поиска или нет
    var displayCount = 0;
    // флаги для установки причин, почему не стоит скрывать поле ввода для поиска
    const MAX_FLAG = 7;
    const MOUSE_IN_FLAG = 1;
    const FOCUS_IN_FLAG = 2;

    /**
     * Если displayCount равен нулю, то скрывает поле ввода для поиска
     * 
     * @return void
     */
    var hideSearchInput = function() {
        if (!displayCount) $(rusvSelector.searchFormTitle).removeClass(rusvClass.hidden);
    }

    /**
     * Обрабочтик установки курсора в поле ввода для поиска
     * 
     * @return void
     */
    var fosusInInput = function() {
        displayCount |= FOCUS_IN_FLAG;
    }

    /**
     * Обработчик нажатия на значке "лупы". Показывает поле ввода
     * или отправляет запрос на поиск
     * 
     * @return void
     */
    var showSearchInput = function() {
        if ($(rusvSelector.searchFormTitle).hasClass(rusvClass.hidden)) {
            $(rusvSelector.searchForm).submit();

        } else {
            $(rusvSelector.searchFormTitle).addClass(rusvClass.hidden);
            $(rusvSelector.searchFormInput).focus();
        }
    }

    /**
     * Обрабочтик местоположения курсора мыши над областью поиска.
     * Устанавливает displayCount флаг, что курсов в рамках области
     * иил сбрасывает это флаг
     * 
     * @return void
     */
    var checkSearchArea = function(event) {
        var rect = $(rusvSelector.searchLayer).get(0).getBoundingClientRect();

        if (
            (rect.top + rect.height <= event.clientY) || (rect.top >= event.clientY)
            || (rect.left + rect.width <= event.clientX) || (rect.left >= event.clientX)
        ) {
            displayCount &= MAX_FLAG ^ MOUSE_IN_FLAG;
            hideSearchInput();

        } else {
            displayCount |= MOUSE_IN_FLAG;
        }
    }

    /**
     * Обработчик потери курсора ввода в поле ввода для поиска
     * 
     * @return void
     */
    var fosusOutInput = function() {
        displayCount &= MAX_FLAG ^ FOCUS_IN_FLAG;
        hideSearchInput();
    }

    $(document)
        .on('click', rusvSelector.searchFormButton, showSearchInput)
        .on('focus', rusvSelector.searchFormInput, fosusInInput)
        .on('focusout', rusvSelector.searchFormInput, fosusOutInput)
        .on('mousemove mouseout', rusvSelector.searchLayer, checkSearchArea)
    ;
})();