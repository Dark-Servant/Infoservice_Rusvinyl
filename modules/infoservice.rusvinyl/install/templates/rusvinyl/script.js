;(function() {
    var rusvSelector = {
        headDate: '.rusv-head-date',
        printBtn: '.rusv-footer-menu li:last a',
        newsList: '.rusv-news-list',
        newsItem: '.rusv-news-item',
        newsListPage: '.rusv-news-list-page',
        simpleNewsListPage: '.rusv-news-list-page:not(.rusv-selected)',
    };
    var rusvClass = {
        hidden: 'rusv-hidden',
        selected: 'rusv-selected',
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