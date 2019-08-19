;(function() {
    var rusvSelector = {
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
    const PAGE_WAITING_TIME = 10000;
    var lastStart = {};

    /**
     * Автоматически показывает следующую новость
     * 
     * @param selector - 
     * @return void
     */
    var nextNewsUnit = function(selector) {
        var selectorId = selector.closest(rusvSelector.newsList).data('id');
        var currTime = (new Date()).getTime();
        var newWaintingTime = 0;

        if (lastStart[selectorId])
            newWaintingTime = PAGE_WAITING_TIME - currTime + lastStart[selectorId];

        if (newWaintingTime < 1) {
            var page = selector.find(rusvSelector.newsListPage + '.' + rusvClass.selected).next();
            if (page.length) {
                page.click();

            } else {
                selector.find(rusvSelector.simpleNewsListPage).first().click();
            }
            newWaintingTime = PAGE_WAITING_TIME;
            lastStart[selectorId] = currTime;
        }

        setTimeout(() => nextNewsUnit(selector), newWaintingTime);
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
        
        setTimeout(() => nextNewsUnit($(selector.get(number))), PAGE_WAITING_TIME);
        setTimeout(() => initPageChoosing(selector, number + 1), Math.floor(PAGE_WAITING_TIME / 2));
    }

    /**
     * Обработчик инициализации страницы при ее полной готовности
     * 
     * @return void
     */
    var initPage = function() {
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
        lastStart[newsList.data('id')] = (new Date()).getTime();
    }

    $(document)
        .on('ready', initPage)
        .on('click', rusvSelector.simpleNewsListPage, chooseNews)
    ;
})();