;(function() {
    var rusvSelector = {
        headDate: '.rusv-head-date',
        printBtn: '.rusv-footer-menu li:last a',
        mainUserSelectorRemove: '.ui-tile-selector-item-remove',
        fileInput: '.rusv-modal-file-input',
        fileLinkName: '.rusv-modal-file-link-name',
        mustBeFixed: '.rusv-must-be-fixed',
        datetimeInput: '.rusv-datetime-input',
        numericInput: '.rusv-numeric-input',
    };
    var rusvClass = {
        fixed: 'rusv-fixed',

        // обязательны для вызова модальных окон
        inited: 'rusv-inited',
        popupWindowContent: 'popup-window-content',
    };
    var modalWnd = {};
    var mustBeFixed;

    /**
     * Сбрасывает все настройки для всех элементов с классом rusv-fixed
     * 
     * @return void
     */
    var cancelFixedStatus = function() {
        $('.' + rusvClass.fixed).removeAttr('style');
        $('.' + rusvClass.fixed).removeClass(rusvClass.fixed);
    }

    /**
     * Обработчик прокрутки страницы, для помеченных специальным классом "rusv-must-be-fixed",
     * если они начали выходить за рамки видимой области, добавляет класс "rusv-fixed" и дополнительные
     * стили, чтобы они стали фиксированными и всегда были в поле зрения. Если область, в которой
     * находился элемент, становится снова видимой, то у элемента удаляется класс "rusv-fixed" и
     * дополнительные стили.
     * 
     * При использовании этого функционала стоит такие элементы выделять дополнительным слоем с такими
     * же шириной и высотой, чтобы при получении класса "rusv-fixed" не происходило прыганий других
     * компонентов страницы влево-вправо и вверх-вниз
     * 
     * @return void
     */
    var checkDocumentPlace = function() {
        if (!mustBeFixed) return;

        var unit = $(mustBeFixed.list[mustBeFixed.top[0]].obj);
        if (unit.hasClass(rusvClass.fixed)) {
            if (unit.parent().get(0).getBoundingClientRect().y >= 0)
                cancelFixedStatus();

        } else {
            var parentUnitY = unit.parent().get(0).getBoundingClientRect().y;
            if (parentUnitY > 0) return;

            mustBeFixed.list.forEach(unit => {
                $(unit.obj).addClass(rusvClass.fixed);
                $(unit.obj).css(unit.cssY);
            });
        }
    }

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
            var viewArea = screen.height;
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
        checkDocumentPlace();
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
        $(rusvSelector.headDate).html(moment().locale('ru').format('D MMMM HH:mm:ss'));
        nextSecond();
    }

    /**
     * Для указанного параметра (x или y) сохранить номер местоположения элемента в специальном
     * для этого параметра массиве таким образом, чтобы элементы по этому массиву для этого параметра
     * можно было использовать в порядке возрастания значения этого параметра
     * 
     * @param number - номер элемента
     * @param rect - параметры о координатах, ширине, высоте и других подобных параметров
     * @param paramList - список, в котором хранятся номера элементов в том порядке, чтобы по нему можно
     * было работать с элементами по возрастанию значения параметра
     * 
     * @param paramName - название параметра
     * @return void
     */
    var addWithSortingFixedByParam = function(number, rect, paramList, paramName) {
        var index = 0;
        for (;
            (index < paramList.length)
            && (rect[paramName] >= mustBeFixed.list[paramList[index]].rect[paramName])
            ; ++index
        );
        paramList.splice(index, 0, number);
    }

    /**
     * Устанавливает для каждого элемента списка разницу между указанным параметром
     * и таким же параметром родительского элемента первого элемента списка.
     * Результат сохраняется в css<название параметра>
     * 
     * @param paramList -
     * @param savedParam -
     * @param paramName -
     * @return void
     */
    var initFixedUnitParamCSS = function(paramList, savedParam, paramName) {
        var parentRect = $(mustBeFixed.list[paramList[0]].obj).parent().get(0).getBoundingClientRect();
        var parentParamValue = parentRect[paramName];
        // Получаем имя параметра, преобразовывая значения вроде "test-te_set" в "testTeSet"
        var cssParamName = 'css' + paramName.replace(/(?:^|[^a-z\d])(\w)/ig, (result, part) => {
            return part.toUpperCase();
        });

        mustBeFixed.list.forEach(unit => {
            var params = {};
            params[savedParam] = (unit.rect[paramName] - parentParamValue) + 'px';
            unit[cssParamName] = Object.assign(unit[cssParamName] || {}, params);
        });
    }

    /**
     * Для элементов с классом "rusv-must-be-fixed" проводит вычисления их сдвигов от верхнего начала
     * страниы и левого края страницы, а так же устанавливает дополнительные свойства, дабы потом при
     * прокрутке это использовалось для установки методом checkDocumentPlace вычисленных свойств,
     * чтобы элементы находились всегда в поле зрения
     * 
     * @return void
     */
    var initFixedElements = function() {
        mustBeFixed = {list: [], top: [], left: []};

        $(rusvSelector.mustBeFixed + ':not(:hidden)').each((num, unit) => {
            var rect = unit.getBoundingClientRect();
            mustBeFixed.list.push({
                obj: unit,
                rect: rect,
                cssY: {width: rect.width + 'px'}
            });

            addWithSortingFixedByParam(num, rect, mustBeFixed.top, 'y');
            addWithSortingFixedByParam(num, rect, mustBeFixed.left, 'x');
        });
        initFixedUnitParamCSS(mustBeFixed.top, 'top', 'y');
        initFixedUnitParamCSS(mustBeFixed.left, 'left', 'x');

        console.log(mustBeFixed);
    }

    /**
     * Обработчик события за изменение размеров браузера
     * 
     * @return void
     */
    var checkDocumentSize = function() {
        cancelFixedStatus();
        initFixedElements();
        checkDocumentPlace();
    }

    /**
     * Обработчик инициализации страницы при ее полной готовности
     * 
     * @return void
     */
    var initPage = function() {
        nextSecond();
        initFixedElements();
        checkDocumentPlace();
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

    /**
     * Вызывает календарь для полей ввода с классом rusv-datetime-input
     *
     * @return void
     */
    var setDateEnterData = function() {
        BX.calendar({node: this, field: this, bTime: false});
    }

    /**
     * Устанавливает запрет на ввод нечисловых данных во все поля ввода,
     * которые имеют класс rusv-numeric-input
     *
     * @return void
     */
    var setNumericEnterData = function() {
        $(this).numeric({decimal: '.', decimalPlaces: 0, negative: false});
    }

    $(document)
        .on('ready', initPage)
        .on('scroll', scrollHandle)
        .on('click', rusvSelector.printBtn, showPrintWnd)
        .on('shommodal', showModal)
        .on('closemodal', closeModal)
        .on('change', rusvSelector.fileInput, choosingFile)
        .on('click', rusvSelector.datetimeInput, setDateEnterData)
        .on('click', rusvSelector.numericInput, setNumericEnterData)
    ;
    $(window)
        .on('resize', checkDocumentSize)
    ;
})();