;(function() {
    var ajaxURL;
    var handlebarUnits= {
        listPages: {selector: '#rusv-unit-list-pages-template'},
    };
    var rusvSelector = {
        contentData: '.rusv-body-content-data',
        unitList: '.rusv-unit-list',
        listUnit: '.rusv-list-unit',
        unitPages: '.rusv-unit-pages',
        otherListPage: '.rusv-unit-list-page:not(.rusv-unit-list-current-page)',
        unitTextMore: '.rusv-unit-text-more-value',
    };
    var rusvClass = {
        noReaction: 'rusv-no-reaction',
        opened: 'rusv-unit-text-opened'
    };

    /**
     * Получение списка элементов на конкретной странице
     * 
     * @param pageNumber - номер страницы (0 и 1 считаются одним и тем же)
     * @return void
     */
    var loadUnits = function(pageNumber) {
        $(rusvSelector.contentData).addClass(rusvClass.noReaction);

        $.get(ajaxURL, {page: pageNumber}, answer => {
            $(rusvSelector.contentData).removeClass(rusvClass.noReaction);
            if (!answer.result || !handlebarUnits.unitList) return;

            $(rusvSelector.unitList).html(handlebarUnits.unitList({UNITS: answer.data.list}));
            $(rusvSelector.unitList).attr('data-count', answer.data.list.length);

            var pages = [...Array(answer.data.pages.count).keys()].map(pageI => {
                var realNumber = pageI + 1;
                return {
                    NUMBER: realNumber,
                    CURRENT: realNumber == answer.data.pages.current
                };
            });
            $(rusvSelector.unitPages).html('<!-- ' + handlebarUnits.listPages({PAGES: pages}) + ' -->');
            $(rusvSelector.unitPages).attr('data-many-pages', +(pages.length > 1));
        });
    }

    /**
     * Обработчик инициализации страницы при ее полной готовности
     * 
     * @return void
     */
    var initPage = function(event, data) {
        if (typeof(data.ajaxURL) != 'string') return;

        ajaxURL = data.ajaxURL;
        Object.assign(handlebarUnits, data.handlebar || {});
        for (var i in handlebarUnits) {
            if (typeof(handlebarUnits[i]) == 'function') continue;

            var html = $(handlebarUnits[i].selector).html();
            if (!html) continue;

            if (typeof(handlebarUnits[i].registerName) == 'string')
                Handlebars.registerPartial(handlebarUnits[i].registerName, html);
            handlebarUnits[i] = Handlebars.compile(html);
        }
        loadUnits(0);
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
        $(rusvSelector.unitList).find(rusvSelector.listUnit).removeClass(rusvClass.opened);

        $(this).closest(rusvSelector.listUnit).addClass(rusvClass.opened);
    }

    /**
     * Обработчик события list+pages:update для перезагрузки
     * всех данных списка
     * 
     * @return void
     */
    var updateList = function() {
        loadUnits(0);
    }

    $(document)
        .on('list+pages:init', initPage)
        .on('list+pages:update', updateList)
        .on('click', rusvSelector.otherListPage, setPage)
        .on('click', rusvSelector.unitTextMore, showMoreText)
    ;
})();