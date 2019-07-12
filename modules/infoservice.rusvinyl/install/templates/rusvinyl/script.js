;(function() {
    var rusvSelector = {
        headDate: '.rusv-head-date',
        printBtn: '.rusv-footer-menu li:last a',
        menu: '.rusv-menu',
        nextEvent: '.rusv-body-next-event',
        leftMenu: '.rusv-body-left-menu',
    };
    var rusvClass = {
        fixed: 'rusv-fixed',
    };
    var savedScrollTop;
    var savedScrollTopDiff;

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

    /**
     * Обработчик прокрутки страницы, меняет класс для главного меню и левого, чтобы они
     * стали зафиксированы и могли быть всегда в поле зрения
     * 
     * @return void
     */
    var checkDocumentPlace = function() {
        var menu = $(rusvSelector.menu);
        if (menu.hasClass(rusvClass.fixed)) {
            if (savedScrollTopDiff > savedScrollTop - document.body.getBoundingClientRect().y) {
                $('.' + rusvClass.fixed).removeAttr('style');
                $('.' + rusvClass.fixed).removeClass(rusvClass.fixed);
            }

        } else {
            var menuYValue = menu.get(0).getBoundingClientRect().y;
            if (menuYValue <= 0) {
                savedScrollTop = menuYValue;
                savedScrollTopDiff = savedScrollTop - document.body.getBoundingClientRect().y;

                var cssData = {};
                [
                    rusvSelector.menu, rusvSelector.nextEvent,
                    rusvSelector.leftMenu
                ].forEach(unitSelector => {
                    var unit = $(unitSelector).get(0);
                    cssData[unitSelector] = {
                        width: unit.clientWidth + 'px',
                        top: (unit.getBoundingClientRect().y - menuYValue) + 'px'
                    };
                });
                Object.keys(cssData).forEach(selector => {
                    $(selector).addClass(rusvClass.fixed);
                    $(selector).css(cssData[selector]);
                });
            }
        }
    }

    $(document)
        .on('ready', initPage)
        .on('scroll', checkDocumentPlace)
        .on('click', rusvSelector.printBtn, showPrintWnd)
    ;
})();