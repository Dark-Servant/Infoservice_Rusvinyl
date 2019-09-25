;(function() {
    var ajaxURL = document.location.origin + '/local/components/infoservice/brainbox/ajax.php?action=';
    var rusvSelector = {
        bodyContentData: '.rusv-body-content-data',
        branboxMain: '.rusv-branbox-main',
        branboxMainFileInput: '.rusv-branbox-main-file-input',
        branboxMainImage: '.rusv-branbox-main.rusv-is-editabled'
    };
    var rusvClass = {
        noReaction: 'rusv-no-reaction',
        isEmpty: 'rusv-is-empty',
    };

    /**
     * Обработчик установки нового изображения
     * 
     * @return void
     */
    var choosePicture = function() {
        if (!this.files.length || (['image/jpeg', 'image/png'].indexOf(this.files[0].type) < 0))
            return;

        $(rusvSelector.bodyContentData).addClass(rusvClass.noReaction);
        var data = new FormData;
        data.append('image', this.files[0]);
        $.ajax({
            url: ajaxURL + 'new',
            data: data,
            processData: false,
            contentType: false,
            type: 'POST',
            success: answer => {
                $(rusvSelector.bodyContentData).removeClass(rusvClass.noReaction);
                if (!answer.result) return;

                var image = $(rusvSelector.branboxMainImage + ' img');
                if (!image.length) {
                    image = $('<img>');
                    $(rusvSelector.branboxMainImage).html('');
                    $(rusvSelector.branboxMainImage).append(image);
                    $(rusvSelector.branboxMainImage).removeClass(rusvClass.isEmpty);
                }
                image.attr('src', answer.data + '?' + (new Date()).getTime());
            }
        });
    }

    $(document)
        .on('change', rusvSelector.branboxMainFileInput, choosePicture)
    ;
})();