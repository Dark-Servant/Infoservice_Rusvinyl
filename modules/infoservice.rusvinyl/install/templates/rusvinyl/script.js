;(function() {
    var rusvSelector = {
        headDate: '.rusv-head-date',
        printBtn: '.rusv-footer-menu li:last a',
        menu: '.rusv-menu',
        nextEvent: '.rusv-body-next-event',
        leftMenu: '.rusv-body-left-menu',
        mainUserSelectorRemove: '.ui-tile-selector-item-remove',
        fileInput: '.rusv-modal-file-input',
        fileLinkName: '.rusv-modal-file-link-name',
    };
    var rusvClass = {
        fixed: 'rusv-fixed',

        // обязательны для вызова модальных окон
        inited: 'rusv-inited',
        popupWindowContent: 'popup-window-content',
    };
    var savedScrollTop;
    var savedScrollTopDiff;
    var modalWnd = {};

    /**
     * Перемещает модальное окно вместе со скроллом, учитывая так же случай, если
     * высота окна больше, чем высота экрана, чтобы содержимое такого окна можно
     * было посмотреть
     * 
     * @return void
     */
    var scrollHandle = function() {        
        for (var idValue in modalWnd) {
            var rect = modalWnd[idValue].modal.popupContainer.getBoundingClientRect();
            var viewArea = document.body.clientHeight;
            if (rect.height >= viewArea) {
                if (rect.y > 0) {
                    modalWnd[idValue].modal.popupContainer.style['top'] = window.scrollY + 'px';
                } else if ((rect.y < 0) && (rect.y + rect.height < viewArea)) {
                    modalWnd[idValue].modal.popupContainer.style['top'] = (window.scrollY - rect.height + viewArea) + 'px';
                }
            } else {
                modalWnd[idValue].modal.popupContainer.style['top'] = (window.scrollY + (viewArea - rect.height) / 2) + 'px';
            }
        }
    }

    /**
     * Создание модального окна
     * 
     * @param idValue - ID модального окна
     * @param templateSelector - шаблон для тела модального окна
     * @param params - дополнительные параметры модального окна
     * @param eventHandles - события модального окна
     * @return Object
     */
    var getModalWindow = function(idValue, templateSelector, params, eventHandles) {
        if (!(modalWnd[idValue] instanceof Object)) {
            delete params.events;
            var resultParams = Object.assign({
                content: '',
                zIndex: 0,
                offsetLeft: 0,
                offsetTop: 0,
                draggable: {restrict: false},
                events: Object.assign({}, typeof(eventHandles) != 'undefined' ? eventHandles : {}, {
                    onPopupShow: function() {
                        var wnd = $(this.popupContainer);
                        if (!wnd.hasClass(rusvClass.inited)) {
                            wnd.addClass(rusvClass.inited);
                            wnd.find('.' + rusvClass.popupWindowContent).append($(templateSelector));
                            $(templateSelector).removeClass(rusvClass.hidden);
                        }
                        if (
                            (modalWnd[idValue].events instanceof Object)
                            && (typeof(modalWnd[idValue].events.onPopupShow) == 'function')
                        ) modalWnd[idValue].events.onPopupShow(wnd);
                    },
                    onAfterPopupShow: function() {
                        var wnd = $(this.popupContainer);
                        modalWnd[idValue].rect = wnd.get(0).getBoundingClientRect();
                        if (
                            (modalWnd[idValue].events instanceof Object)
                            && (typeof(modalWnd[idValue].events.onAfterPopupShow) == 'function')
                        ) modalWnd[idValue].events.onAfterPopupShow(wnd);
                    }
                })
            }, params);
            modalWnd[idValue] = {modal: new BX.PopupWindow(idValue, window.body, resultParams)};
        }
        /**
         * Переопределение этого параметра, в котором должна сохраняться новая реализация методов, при повторных вызовах
         * модального окна будет работать только для событий onPopupShow, onAfterPopupShow
         */
        modalWnd[idValue].events = eventHandles;
        return modalWnd[idValue];
    }

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

    /**
     * Обработчик события shommodal для показа модального окна
     * 
     * @param event - данные события
     * @param data - параметры модального окна, важными являются ID и DATA_SELECTOR
     * @return void
     */
    var showModal = function(event, data) {
        if (
            !(data instanceof Object) || (typeof(data.ID) != 'string')
            || (typeof(data.DATA_SELECTOR) != 'string')
        ) return;
        var params = Object.assign({
                overlay: {
                    backgroundColor: 'rgba(0, 0, 0, 0.58)'
                },
                zIndex: 1,
                closeIcon: {right: '20px', top: '20px'}
            },
            data.PARAMS instanceof Object ? data.PARAMS : {}
        );
        var events = data.EVENTS instanceof Object ? data.EVENTS : {};
        var refresh = typeof(data.REFRESH) == 'boolean' ? data.REFRESH : true;

        delete data.REFRESH;
        getModalWindow(
            data.ID, data.DATA_SELECTOR, params,
            Object.assign({}, events, {
                onAfterPopupShow: wnd => {
                    if (refresh) {
                        $(wnd).find('*[name]:not([type="hidden"])').each((number, unit) => {
                            $(unit).val('');
                            if (unit.type == 'file') $(unit).change();
                        });
                        $(wnd).find(rusvSelector.mainUserSelectorRemove).click();
                    }
                    if (typeof(events.onAfterPopupShow) == 'function')
                        events.onAfterPopupShow(wnd);
                }
            })
        ).modal.show();
    }

    /**
     * Обработчик события closemodal для закрытия модального окна
     * 
     * @param event - данные события
     * @param ID - идентификатор модального окна
     * @return void
     */
    var closeModal = function(event, ID) {
        if ((typeof(ID) != 'string') || !(modalWnd[ID] instanceof Object))
            return;

        modalWnd[ID].modal.close();
    }

    /**
     * Обработчик выбора файла в модальном окне
     * 
     * @return void
     */
    var choosingFile = function() {
        if (
            (this.files.length)
            && (['image/jpeg', 'image/png'].indexOf(this.files[0].type) > -1)
        ) {
            var fileLinkName = $(this).parent().find(rusvSelector.fileLinkName);
            fileLinkName.attr('title', this.files[0].name);
            fileLinkName.html(this.files[0].name);

        } else {
            $(this).val('');
            $(this).parent().find(rusvSelector.fileLinkName).html('');
        }
    }

    $(document)
        .on('ready', initPage)
        .on('scroll', scrollHandle)
        .on('scroll', checkDocumentPlace)
        .on('click', rusvSelector.printBtn, showPrintWnd)
        .on('shommodal', showModal)
        .on('closemodal', closeModal)
        .on('change', rusvSelector.fileInput, choosingFile)
    ;
})();