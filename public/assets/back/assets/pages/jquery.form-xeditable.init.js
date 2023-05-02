$(function() {
    $.fn.editableform.buttons = '<button type="submit" class="btn btn-success editable-submit btn-sm waves-effect waves-light"><i class="mdi mdi-check"></i></button><button type="button" class="btn btn-danger editable-cancel btn-sm waves-effect waves-light"><i class="mdi mdi-close"></i></button>', 
    
    $(".inlineInput").editable({
        type: "text",
        url: '/admin/language/edit',
        name: "username",
        title: "Enter Translation",
        mode: "inline",
        inputclass: "form-control-sm"
    })
    
});