$(document).ready(function(){
    $('textarea.wysiwyg-redactor').redactor({
        buttons: ['formatting', 'bold', 'italic', 'deleted', 'unorderedlist', 'orderedlist', 'outdent', 'indent', 'image', 'video', 'file', 'table', 'link', 'alignment', 'horizontalrule'],
        plugins: ['advancedimage', 'fullscreen'],
        lang: 'ru',
        dragUpload: false,
        minHeight: 150
    });
});