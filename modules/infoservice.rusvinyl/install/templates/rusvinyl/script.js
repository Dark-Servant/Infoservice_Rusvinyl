;(function() {
    var rusvSelector = {
        headDate: '.rusv-head-date',
        printBtn: '.rusv-footer-menu li:last a',
        newsList: '.rusv-news-list',
        newsItem: '.rusv-news-item',
        newsListPages: '.rusv-news-list-pages',
        newsListPage: '.rusv-news-list-page',
        simpleNewsListPage: '.rusv-news-list-page:not(.rusv-selected)',
    };
    var rusvClass = {
        hidden: 'rusv-hidden',
        selected: 'rusv-selected',
    };
    const PAGE_WATING_TIME = 5000;

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
     * Автоматически показывает следующую новость
     * 
     * @param selector - 
     * @return void
     */
    var nextNewsUnit = function(selector) {
        var page = selector.find(rusvSelector.newsListPage + '.' + rusvClass.selected).next();
        if (page.length) {
            page.click();

        } else {
            selector.find(rusvSelector.simpleNewsListPage).first().click();
        }

        setTimeout(() => nextNewsUnit(selector), PAGE_WATING_TIME);
    }

    /**
     * Запускает процесс листания страниц для указанной области и 
     * процесс ожидания такого же запуска листания для следующей
     * области
     * 
     * @param selector - селектор ко всем областям страниц
     * @param number - номер конкретной области
     * @return void
     */
    var initPageChoosing = function(selector, number) {
        if (!selector.get(number)) return;
        
        setTimeout(() => nextNewsUnit($(selector.get(number))), PAGE_WATING_TIME);
        setTimeout(() => initPageChoosing(selector, number + 1), PAGE_WATING_TIME);
    }

    /**
     * Обработчик инициализации страницы при ее полной готовности
     * 
     * @return void
     */
    var initPage = function() {
        nextSecond();
        initPageChoosing($(rusvSelector.newsListPages), 0);
    }

    /**
     * Обработчик клика по страница в листающих блоках на главной странице
     * 
     * @return void
     */
    var chooseNews = function() {
        var newsList = $(this).closest(rusvSelector.newsList);
        newsList.find(rusvSelector.newsListPage).removeClass(rusvClass.selected);
        $(this).addClass(rusvClass.selected);

        newsList.find(rusvSelector.newsItem).addClass(rusvClass.hidden);
        newsList.find(rusvSelector.newsItem + '[data-id="' + $(this).data('id') + '"]').removeClass(rusvClass.hidden);
    }

    $(document)
        .on('ready', initPage)
        .on('click', rusvSelector.printBtn, showPrintWnd)
        .on('click', rusvSelector.simpleNewsListPage, chooseNews)
    ;
})();