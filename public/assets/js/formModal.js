function getForm(item) {
    $("#modalTitle").html(item.attr("data-type"));
    console.log(item.attr("data-type"));

    var $href = item.attr('href');


    $.ajax({
        url: $href,
        type: "GET",
        dataType: 'html',
        success: function (obj) {

            $("#folderForm").html(obj);
            $('#modalFolder').modal("show");
            setTimeout(function(){$('#folderForm').find('input[type=text]').focus();},100)
;               
        },
        complete: function () {
            console.log("complete!");

        },
        error: function (err) {
            console.log('error');
            console.log(err);
        }
    });
}

$(document).ready( function() {
var $envoi = true;

    $(".addForm").click(function (e) {
        e.preventDefault();
        getForm($(this));
    });
    
    function updateArticle(){
        if($envoi == true)
        {
            $envoi = false;
            var $form = $("#form_envoi");
            //var path= "/admin/article/update/" + $("#form_envoi").attr("data-id") + "/" + "Text" + "/" + CKEDITOR.instances.article_text.getData();
            CKEDITOR.instances.article_text.updateElement();
            $.ajax({
                url: $("#form_envoi").attr("action"),
                data: $("#form_envoi").serialize() ,
                type: "POST",
                dataType: "json",
                success: function (data) {
                    console.log("saved" + data.id);
                    $envoi = true;
                    
                },
                error: function (err) {
                    console.log('error');
                    console.log(err);
                }
            });
        };
    };

    CKEDITOR.instances.article_text.on('change', function(){
        updateArticle();
    });
    $("#form_envoi").on('change', function(){
        updateArticle();
    });
        
});

$("#modalFolder").on('submit', 'form', function(e){
    // il est impératif de commencer avec cette méthode qui va empêcher le navigateur d'envoyer le formulaire lui-même
    e.preventDefault();
    
    $form = $(e.target);
    modal = $('#modalFolder');
    
    var $submitButton = $form.find(':submit');
    $submitButton.html('<i class="fas fa-spinner fa-pulse"></i>');
    $submitButton.prop('disabled', true);

    // ajaxSubmit du plugin ajaxForm nécessaire pour l'upload de fichier
    $form.ajaxSubmit({
        type: 'post',
        success: function(data) {
           console.log(data.id + " + " + data.name + " + " + data.type);
           
            $("#article_" + data.type).append("<option selected=\"selected\" value=" + data.id + ">" + data.name + "</option>")
            modal.modal('toggle');
        },
        error: function(jqXHR, status, error) {
              $submitButton.html(button.data('label'));
              $submitButton.prop('disabled', false);
        }
    });
});

  