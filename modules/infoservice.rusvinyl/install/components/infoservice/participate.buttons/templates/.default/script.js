;(function() {
	var ajaxURL = document.location.origin + '/local/components/infoservice/participate.buttons/ajax.php?action=';
    var rusvSelector = {
    	participateButtons: '.rusv-participate-buttons',
    	addButton: '.rusv-participate-add-button',
    	status: '.rusv-participate-status'
    };
    var rusvClass = {
    	noReaction: 'rusv-no-reaction'
    };
    var elementId;

    /**
     * Обработчик готовности страницы
     * 
     * @return void
     */
    var initComponent = function() {
    	elementId = $(rusvSelector.participateButtons).data('element-id');
    }

    /**
     * Обработчик нажатия кнопки "Участвовать"
     *
     * @return void
     */
    var setParticipation = function() {
    	$(this).addClass(rusvClass.noReaction);

    	$.post(ajaxURL + 'setparticipation', {elementId: elementId}, answer => {
    		$(this).removeClass(rusvClass.noReaction);
    		if (!answer.result) return;

    		if (answer.message) $(rusvSelector.status).html(answer.message);
    		$(rusvSelector.addButton).remove();
    	});
    }

    $(document)
    	.on('ready', initComponent)
    	.on('click', rusvSelector.addButton, setParticipation)
    ;
})();