$(document).ready(function(){
    
    function validateField( field ) {
        var oField = null;
        switch ($.type(field)) {
            case 'string':
                oField = $('#' + field);
                break;
            case 'object':
                oField = $(field);
                break;
            default: 
                return false;
                break;
        }
        
        var aData = oField.data();
        var sFieldName = ( aData.validateName ) ? aData.validateName : oField.attr('name');
        var sValue = oField.val();
        aData.validate_name = sFieldName;
        aData.validate_value = sValue;
        aData.validate_model = aData.validateModel;
        
        if (aData.dynamicParameters) {
            var sDynamicParameters = aData.dynamicParameters;
            var aDynamicList = sDynamicParameters.split(';');
            for (var i in aDynamicList) {
                if ( aDynamicList[i].length == 0 ) {
                    continue;
                }
                var aDynamicParameter = aDynamicList[i].split(':');
                aData[aDynamicParameter[0]] = $('#' + aDynamicParameter[1]).val();
            }
        }
        
        if (aData.validateOnSuccess) {
            var sValidateOnSuccess = aData.validateOnSuccess;
            var aValidateOnSuccess = sValidateOnSuccess.split(';');
        } else {
            var aValidateOnSuccess = [];
        }
        
        $.post(
            '/validate/field',
            aData,
            function(data){
                console.log(data);
                var sId = oField.attr('id');
                var oErrors = $('#' + sId + 'Errors');
                oErrors.empty();
                if ( !data.result ) {
                    oField.parent().removeClass('has-success').removeClass('has-warning').addClass('has-error');
                    if (oErrors.hasClass('_validate-errors')) {
                        for (i in data.errors) {
                            oErrors.append($('<li>' + data.errors[i] + '</li>'))
                        }
                    }
                } else {
                    oField.parent().removeClass('has-error').removeClass('has-warning').addClass('has-success');
                    for ( var i in aValidateOnSuccess ) {
                        validateField(aValidateOnSuccess[i]);
                    }
                }
            },
            'json'
        );
    }
    
    $('._validate').each(function(){
        $(this).change(function(){
            validateField(this);
        })
    });
    
});


