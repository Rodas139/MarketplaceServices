/**
 * Init configurations
 */
var Init = {
   
   select2: function() {
        return {
            theme: "bootstrap",
            language: 'pt',
            minimumResultsForSearch: 20
        };
    },
    
    datepicker: function() {
        return {
            format: 'yyyy-mm-dd',
            language: 'pt'
        };
    },
    
    iCheck: function() {
        return {
            checkboxClass: 'icheckbox_minimal-blue',
            radioClass: 'iradio_minimal-blue',
        }
    },
}

$(document).ready(function(){
    $('.select2').select2(Init.select2());
    //$('input').iCheck(Init.iCheck());
    $('[data-toggle="tooltip"]').tooltip();
    
    $('.search-area').hover(function(){
        $('header .search').animate({width:'toggle'},350);
        $('header .search input').focus();
    })
    
    $('select[data-toggle="search-location"]').select2({        
        ajax: {
            url: "/pt/pedido-de-cotacao/search",
            dataType: 'json',
            method: 'POST',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term
                };
            },
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: true
        },
        minimumInputLength: 2
    });
});

/**
 * Toggle view more panel
 * @param {type} param1
 * @param {type} param2
 */
$('[data-toggle="view-more"]').on('click', function(e){
        e.preventDefault();
        var target = $(this).data('target');
        var newLabel = $(this).data('toggle-text');
        $(this).data('toggle-text', $(this).html());
        $(this).html(newLabel);
        
        $(target).toggleClass('active');
    })
    
/**
 * Validate file size in fileinputs
 * 
 * @param {type} param1
 * @param {type} param2
 */
$('input[data-max-size]').bind('change', function(){
    
    if($(this).val() != '') {
        var max   = $(this).data('max-size');
        var unity = (unity === undefined || unity == null || unity.length <= 0) ? 'mb' : $(this).data('unity');
        var fileSize = $(this)[0].files[0].size;

        if(unity == 'gb') {
            var maxBytes = max * 1000000000;
        } else if(unity == 'mb') {
            var maxBytes = max * 1000000;
        } else if(unity == 'kb') {
            var maxBytes = max * 1000;
        } else {
            var maxBytes = max;
        }
        
        if(fileSize > maxBytes) {
  
            $(this).closest('.fileinput').find('[data-dismiss=fileinput]').trigger('click');
            
            swal({
                title: "",
                text: 'O tamanho do ficheiro excede o máximo permitido de '+ max + unity,
                type: 'warning',
                showCancelButton: false,
                confirmButtonClass: "btn-default",
                closeOnConfirm: false,
                closeOnCancel: false
            });
        }
    }
})

/**
 * Validate file extension in fileinputs
 * 
 * @param {type} param1
 * @param {type} param2
 */
$('input[data-file-format]').bind('change', function(){
    
    if($(this).val() != '') {
        var acceptedExtensions = $(this).data('file-format');
        acceptedExtensions = acceptedExtensions.split(",");

        var filename = $(this).val();
        var extension = filename.replace(/^.*\./, '');
        
        if (extension == filename) {
            extension = '';
        } else {
            extension = extension.toLowerCase();
        }
        
        if($.inArray(extension,acceptedExtensions) < 0) {
  
            $(this).closest('.fileinput').find('[data-dismiss=fileinput]').trigger('click');
            
            swal({
                title: "",
                text: 'A extenção do ficheiro selecionado não é permitida.',
                type: 'warning',
                showCancelButton: false,
                confirmButtonClass: "btn-default",
                closeOnConfirm: false,
                closeOnCancel: false
            });
        }
    }
});

/**
 * Submit form by ajax
 * 
 * @param {type} param1
 * @param {type} param2
 */
$('form.ajax-form').on('submit', function (e) {
    e.preventDefault();
    var $form = $(this);
    var $submitBtn = $form.find('button[type=submit]');
    $submitBtn.button('loading');

    var form = $(this)[0];
    var formData = new FormData(form);
    
    $.ajax({
        url: $form.attr('action'),
        data: formData,
        type: 'POST',
        contentType: false,
        processData: false,
    }).success(function(data){
        var feedbackText;
        var alertType;

        if (data.result) {
            $form.find('input[type=text], input[type=email], textarea, select').val('');
            $form.find('input[type=checkbox], input[type=radio]').prop('');
            $('select.select2').trigger("change");
            //$('input').iCheck('uncheck');
            feedbackText = data.feedback;
            alertType = "success";
        } else {
            feedbackText = data.feedback;
            alertType = "error";
        }

        $submitBtn.button('reset');
        $form.find('[data-dismiss=fileinput]').trigger('click');
        
        swal({
            title: "",
            text: feedbackText,
            type: alertType,
            showCancelButton: false,
            confirmButtonClass: "btn-default",
            closeOnConfirm: false,
            closeOnCancel: false
        });
    }).fail(function () {

        swal({
            title: "",
            text: 'Não foi possível processar o seu pedido porque ocorreu um erro interno de servidor.',
            type: "error",
            showCancelButton: false,
            confirmButtonClass: "btn-default",
            closeOnConfirm: false,
            closeOnCancel: false
        });

    }).always(function () {
        $submitBtn.button('reset');
    });
});

var action;
$(".number-spinner button").mousedown(function () {
    btn = $(this);
    input = btn.closest('.number-spinner').find('input');
    btn.closest('.number-spinner').find('button').prop("disabled", false);

    if (btn.attr('data-dir') == 'up') {
        action = setInterval(function(){
            if ( input.attr('max') == undefined || parseInt(input.val()) < parseInt(input.attr('max')) ) {
                input.val(parseInt(input.val())+1);
            }else{
                btn.prop("disabled", true);
                clearInterval(action);
            }
        }, 50);
    } else {
        action = setInterval(function(){
            if ( input.attr('min') == undefined || parseInt(input.val()) > parseInt(input.attr('min')) ) {
                input.val(parseInt(input.val())-1);
            }else{
                btn.prop("disabled", true);
                clearInterval(action);
            }
        }, 50);
    }
}).mouseup(function(){
    clearInterval(action);
});

/**
  * Open an popup window centred on screen
  * 
  * @param {type} url
  * @param {type} title
  * @param {type} w
  * @param {type} h
  * @returns {undefined}
  */
 function popupCenter(url, title,w, h) {
    // Fixes dual-screen position                         Most browsers      Firefox
    var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : screen.left;
    var dualScreenTop = window.screenTop != undefined ? window.screenTop : screen.top;

    var width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
    var height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

    var left = ((width / 2) - (w / 2)) + dualScreenLeft;
    var top = ((height / 2) - (h / 2)) + dualScreenTop;
    var newWindow = window.open(url, title, 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);

    // Puts focus on the newWindow
    if (window.focus) {
        newWindow.focus();
    }
}

/**
 * Action to share url
 */
$('a[data-toggle=share]').on('click', function (e) {
    e.preventDefault();
    var href = $(this).attr('href');
    popupCenter(href, '', 600, 400);
    return false;
})