{{ Form::model($chat, $formOptions) }}
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">
        <span class="fs-15" aria-hidden="true"><i class="fas fa-times"></i></span>
        <span class="sr-only">{{trans('account/orders.close') }}</span>
    </button>
    <h4 class="modal-title">{{ $action }}</h4>
</div>
<div class="modal-body">
    <div class="row row-10">
        <div class="col-sm-12">
            <div>
                <div class="tab-content" style="padding-top: 15px">
                    <div role="tabpanel" class="tab-pane active" id="locale-pt">
                        <div class="form-group is-required">
                            {{ Form::label('subject', {{trans('account/orders.subject') }}) }}
                            {{ Form::text('subject', substr(@$chat->subject ?? null, 0, 20), ['class' => 'form-control', 'maxlength' => 20]) }}
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">{{trans('account/orders.close') }}</button>
    <button class="btn btn-primary btn-submit">{{trans('account/orders.save') }}</button>
</div>
{{ Form::close() }}


{{ Html::script('vendor/jquery-simplecolorpicker/jquery.simplecolorpicker.js') }}
{{ Html::style('vendor/jquery-simplecolorpicker/jquery.simplecolorpicker.css') }}
{{ Html::style('vendor/jquery-simplecolorpicker/jquery.simplecolorpicker-fontawesome.css') }}
<script>
    $('.modal .select2').select2(Init.select2());

    $(document).ready(function () {
        $('select[name="color"]').simplecolorpicker({theme: 'fontawesome'});
    })
</script>