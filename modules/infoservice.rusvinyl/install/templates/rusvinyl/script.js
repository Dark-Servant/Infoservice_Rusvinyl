;(function() {
    var rusvSelector = {
        headDate: '.rusv-head-date',
        printBtn: '.rusv-footer-menu li:last a',
    };

    /**
     * Обработчик пункта нижнего меню "Печать"
     * 
     * @return void
     */
    var showPrintWnd = function() {
        window.print();
    }

    /**
     * Устанавливает ожидание для следующего запуска showDate
     * 
     * @return void
     */
    var nextSecond = function() {
        setTimeout(showDate, 1000);
    }

    /**
     * Показывает дату и время в заголовке
     * 
     * @return void
     */
    var showDate = function() {
        $(rusvSelector.headDate).html(moment().locale('ru').format('D MMM HH:mm:ss'));
        nextSecond();
    }

    /**
     * Обработчик инициализации страницы при ее полной готовности
     * 
     * @return void
     */
    var initPage = function() {
        nextSecond();
    }

    $(document)
        .on('ready', initPage)
        .on('click', rusvSelector.printBtn, showPrintWnd)
    ;
})();