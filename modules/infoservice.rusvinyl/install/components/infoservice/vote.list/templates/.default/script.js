;(function() {
    var ajaxURL = document.location.origin + '/local/components/infoservice/vote.list/ajax.php?action=';
    var handlebarUnits= {
        voteList: {selector: '#rusv-vote-list-template'},
    };
    var rusvSelector = {
        voteList: '.rusv-vote-list',
    };
    var rusvClass = {
        noReaction: 'rusv-no-reaction',
        error: 'rusv-error',
    };
    var channelId, pageSize;

    /**
     * Обработчик инициализации страницы при ее полной готовности
     * 
     * @return void
     */
    var initPage = function() {
        channelId = $(rusvSelector.voteList).data('id');
        pageSize = $(rusvSelector.voteList).data('page-size');

        for (var i in handlebarUnits) {
            var html = $(handlebarUnits[i].selector).html();
            if (!html) continue;

            if (typeof(handlebarUnits[i].registerName) == 'string')
                Handlebars.registerPartial(handlebarUnits[i].registerName, html);
            handlebarUnits[i] = Handlebars.compile(html);
        }
        $(document).trigger(
            'list+pages:init',
            {
                ajaxURL: ajaxURL + 'list&channelId=' + channelId + '&pageSize=' + pageSize,
                handlebar: {
                    unitList: handlebarUnits.voteList
                }
            }
        );
    }

    $(document)
        .on('ready', initPage)
    ;
})();