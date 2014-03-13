if (!RedactorPlugins) var RedactorPlugins = {};

RedactorPlugins.advancedimage = {
    
    init : function(){
        this.buttonAdd('advancedimage', 'Выбрать изображение', this.advancedImageButton);
        //this.buttonAwesome('advancedimage', 'fa-picture-o');
    },
            
    advancedImageButton: function(buttonName, buttonDOM, buttonObj, e) {
        var callback = $.proxy(function(){
            var settings = $('#advancedimageSettings').data();
            
            $.post(
                settings.url,
                {},
                function(data){
                    console.log(data);
                    if ( 'object' == typeof data.result ) {
                        var oList = $('<select></select>');
                        var element = $('<option value="" data-url="">&mdash;</option>');
                        oList.append(element);
                        for (var i in data.result) {
                            var element = $('<option value="' + data.result[i].name + '" data-url="/pub_img' + data.result[i].url + '">' + data.result[i].name + '</option>');
                            oList.append(element);
                        }
                        $('#advancedImageModal > section').append(oList);
                        oList.on('change', function(event){
                            var that = $(this);
                            var option = that.children(':selected');
                            var url = option.data('url');
                            $('#advancedImageModal > preview').empty().append((url)?$('<img width="150" src="'+url+'" />'):$(''));
                            console.log(that);
                            console.log(option);
                        });
                    }
                },
                'json'
            );
            console.log(settings);
        });
        this.modalInit('Выбрать изображение', this.selectImageModal(), 500, callback);
    },
            
    selectImageModal: function(){
        var modal = '<div id="advancedImageModal"><section></section><preview></preview><footer></footer></div>';
        return modal;
    }
    
};