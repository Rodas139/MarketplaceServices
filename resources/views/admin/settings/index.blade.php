@section('title')
    Definições Gerais
@stop

@section('content-header')
    Definições Gerais
@stop

@section('breadcrumb')
    <li>Administração</li>
    <li class="active">
        Definições Gerais
    </li>
@stop

@section('content')
    {{ Form::open(['route' => 'admin.settings.store', 'class' => 'form-horizontal']) }}
    <div class="row box-settings">
        <div class="col-md-3 col-lg-2 padding-right-0">
            <div class="box box-solid">
                <div class="box-body no-padding">
                    <ul class="nav nav-pills nav-stacked">
                        <li class="active">
                            <a href="#tab-geral" data-toggle="tab"><i class="fa fa-fw fa-info-circle"></i> Configurações
                                Base</a>
                        </li>
                        <li>
                            <a href="#tab-notifications" data-toggle="tab"><i class="fa fa-fw fa-bell"></i> Notificações</a>
                        </li>
                        <li>
                            <a href="#tab-tickets" data-toggle="tab"><i class="fa fa-fw fa-envelope"></i> Suporte Técnico</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-9 col-lg-10">
            <div class="tab-content tab-content-settings">
                <div class="active tab-pane" id="tab-geral">
                    @include('admin.settings.partials.geral')
                </div>
                <div class="tab-pane" id="tab-notifications">
                    @include('admin.settings.partials.notifications')
                </div>
                <div class="tab-pane" id="tab-tickets">
                    @include('admin.settings.partials.tickets')
                </div>
            </div>
        </div>
    </div>
    {{ Form::close() }}
@stop

@section('styles')
    {{ HTML::style('vendor/ios-checkbox/dist/css/iosCheckbox.min.css') }}
@stop

@section('scripts')
    {{ HTML::script('vendor/ios-checkbox/dist/js/iosCheckbox.min.js') }}
    {{ HTML::script('vendor/ckeditor/ckeditor.js') }}
    <script>
        $(document).on('ifChecked', 'input[name="mobile_app_opt_edit_shipment"]', function(event) {
            alert(1);
        });

        $(".tab-content-settings .ios").iosCheckbox();


        $('select[name="notification_sound"],select[name="customization_notification_sound"]').on('change', function() {
            $.playSound("/assets/sounds/" + $(this).val());
        })

        $('.btn-play-notification').on('click', function() {
            var sound = $(this).closest('tr').find('select').val();
            $.playSound("/assets/sounds/" + sound);
        })


        /*CKEDITOR.editorConfig = function( config ) {
            config.toolbar = [
                { name: 'document', items: [ 'Source', '-', 'Save', 'NewPage', 'Preview', 'Print', '-', 'Templates' ] },
                { name: 'clipboard', items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
                { name: 'editing', items: [ 'Find', 'Replace', '-', 'SelectAll', '-', 'Scayt' ] },
                { name: 'forms', items: [ 'Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField' ] },
                '/',
                { name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'CopyFormatting', 'RemoveFormat' ] },
                { name: 'paragraph', items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl', 'Language' ] },
                { name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },
                { name: 'insert', items: [ 'Image', 'Flash', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe' ] },
                '/',
                { name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },
                { name: 'colors', items: [ 'TextColor', 'BGColor' ] },
                { name: 'tools', items: [ 'Maximize', 'ShowBlocks' ] },
                { name: 'about', items: [ 'About' ] }
            ];
        };
        CKEDITOR.replace('ckeditor1', { height: 250 });*/
    </script>
@stop
