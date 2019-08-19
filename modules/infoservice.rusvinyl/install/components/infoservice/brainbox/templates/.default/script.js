;(function() {
    var ajaxURL = document.location.origin + '/local/components/infoservice/brainbox/ajax.php?action=';
    var rusvSelector = {
        bodyContentData: '.rusv-body-content-data',
        branboxMainFileInput: '.rusv-branbox-main-file-input',
        branboxMainImage: '.rusv-branbox-main.rusv-is-editabled'
    };
    var rusvClass = {
        noReaction: 'rusv-no-reaction',
    };

    /**
     * Обработчик установки нового изображения
     * 
     * @return void
     */
    var choosePicture = function() {
        console.log(this.files[0].type);

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

                $(rusvSelector.branboxMainImage + ' img').attr('src', answer.data + '?' + (new Date()).getTime());
            }
        });
    }

    $(document)
        .on('change', rusvSelector.branboxMainFileInput, choosePicture)
    ;
})();