;(function() {
    var rusvSelector = {
        bodyContentData: '.rusv-body-content-data',
        newsList: '.rusv-news-simple-list',
        nextPage: '.rusv-news-simple-list-next-page',
        nextPageLink: '.rusv-news-simple-list-next-page-link'
    };
    var rusvClass = {
        noReaction: 'rusv-no-reaction'
    };
    var regNextNewsSearch = [
        /<div\s+class=['"]rusv-news-simple-list['"]\s*>([\W\w]*)/i,
        /([\W\w]*<div\s+class=['"]rusv-news-simple-list-data-bottom['"]\s*>(?:\s*<\/div>){2})/i
    ];
    var regNextPage = /<div\s+class="rusv-news-simple-list-next-page"[^>]+\bdata-next-page=['"]([^'"]+)/;

    /**
     * Обработчик ссылки "Посмотреть еще"
     * 
     * @return void
     */
    var showNextElements = function() {
        var nextPage = $(this).closest(rusvSelector.nextPage);
        nextPage.addClass(rusvClass.noReaction);

        var nextNumber = nextPage.attr('data-next-page');
        $.get(document.location, {PAGEN_1: nextNumber}, answer => {
            var data = answer;
            regNextNewsSearch.forEach(regUnit => {
                if (!data) return;

                var regResult = data.match(regUnit);
                data = regResult ? regResult[1] : null;
            });
            nextPage.removeClass(rusvClass.noReaction);

            if (data) $(data).insertBefore(nextPage);

            data = answer.match(regNextPage);
            if (data) {
                nextPage.attr('data-next-page', data[1]);

            } else {
                nextPage.remove();
            }
            window.scrollTo({top: document.body.scrollHeight});

            var bodyContentData = $(rusvSelector.bodyContentData);
            bodyContentData.animate({scrollTop: bodyContentData.get(0).scrollHeight})
        });
    }

    $(document)
        .on('click', rusvSelector.nextPageLink, showNextElements)
    ;
})();