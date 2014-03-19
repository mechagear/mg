 (function( $ ) {
     
     $.fn.inlineEditor = function (options) {
         var oElements = $(this);
         var opts = $.extend( {}, $.fn.defaults, options );
         
         oElements.each(function(){
             var oElement = $(this);
             appendIconEvents(oElement, opts.icon);
             appendClickEvent(oElement);
         });
         console.log(opts);
     }
     
     function appendIconEvents( $obj, icon ) {
         $obj.append('&nbsp;');
         $obj.on('mouseover', function(){
             $(this).append($('<i class="fa ' + icon + '"></i>'))
         });
         $obj.on('mouseout', function(){
             $(this).children('i.' + icon).remove();
         });
     }
     
     function appendClickEvent( $obj ) {
         $obj.on('click', function(){
             $obj.trigger('mouseout');
             
             var oWrapper = $('<div class="input-group m-b"></div>');
             var oInput = $('<input type="text" class="form-control" value="' + $obj.data('value') + '" />');
             var oSaveBtnWrapper = $('<span class="input-group-btn"></span>');
             var oSaveBtn = $('<a class="btn btn-default" type="button"><i class="fa fa-save"></i></a>')
             
             oSaveBtn.on('click', updateValue);
             
             var oEditor = oWrapper.append(oInput).append(oSaveBtnWrapper.append(oSaveBtn));
             oEditor.data.originalValue = $obj.text();
             $obj.empty();
             $obj.append(oEditor);
             $obj.unbind('click').unbind('mouseover');
         });
     }
     
     function updateValue() {
         alert('UPD!');
         console.log(this);
     }
     
     $.fn.defaults = {
         icon: 'fa-pencil',
         editableClass: ''
     };
     
 })( jQuery )

