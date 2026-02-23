<div class="box no-border">
    <div class="box-body">
        <div class="row">
            <div class="col-sm-8">
                <div class="row">
                    <div class="col-sm-6">
                        <h4 class="section-title">Preferências Regionais</h4>
                        <table class="table table-condensed">
                            <tr>
                                <td>{{ Form::label('app_country', 'País da aplicação', ['class' => 'control-label']) }}</td>
                                <td class="w-65">{{ Form::select('app_country', trans('admin/localization.countries'), Setting::get('app_country'), ['class' =>'form-control select2']) }}</td>
                            </tr>
                            <tr>
                                <td>{{ Form::label('app_currency', 'Moeda e Faturação', ['class' => 'control-label']) }}</td>
                                <td>{{ Form::select('app_currency', trans('admin/localization.currencies'), Setting::get('app_currency'), ['class' =>'form-control select2']) }}</td>
                            </tr>
                            <tr>
                                <td class="w-70">{{ Form::label('shipments_volumes_mesure_unity', 'Unidade de medida', ['class' => 'control-label']) }}</td>
                                <td>
                                    {{ Form::select('shipments_volumes_mesure_unity', ['cm' => 'cm (centímetro)', 'm' => 'm (metro)', 'in' => 'in (Inches)'], Setting::get('shipments_volumes_mesure_unity'), ['class' =>'form-control select2']) }}
                                </td>
                            </tr>
                        </table>
                        <h4 class="section-title">Suporte Técnico</h4>
                        <table class="table table-condensed">
                            <tr>
                                <td>{{ Form::label('support_periods_per_hour', 'Cobrar suporte por a cada', ['class' => 'control-label']) }}</td>
                                <td class="w-120px">{{ Form::select('support_periods_per_hour', ['1' => '1 min', '5' => '5 min', '10' => '10 min', '20' => '20 min', '30' => '30 min', '60' => '60 min'], Setting::get('support_periods_per_hour'), ['class' =>'form-control select2']) }}</td>
                            </tr>
                            <tr>
                                <td>{{ Form::label('support_minutes', 'Minutos Grátis', ['class' => 'control-label']) }}</td>
                                <td>
                                    <div class="input-group">
                                        {{ Form::text('support_minutes',  Setting::get('support_minutes'), ['class' =>'form-control', 'number']) }}
                                        <div class="input-group-addon">Min</div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>{{ Form::label('support_price_per_period', 'Preço por Período', ['class' => 'control-label']) }}</td>
                                <td>
                                    <div class="input-group">
                                        {{ Form::text('support_price_per_period',  Setting::get('support_price_per_period'), ['class' =>'form-control decimal']) }}
                                        <div class="input-group-addon">€</div>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-sm-6">
                        <h4 class="section-title">
                            <a href="?tab=customization"
                               data-toggle="tooltip"
                               title="Altere a côr da aplicação só para a sua conta sem afetar outros utilizadores."
                               style="margin: -4px;" class="btn btn-xs btn-primary pull-right">
                                Personalizar para mim
                            </a>
                            Design e Usabilidade
                        </h4>
<!--                        <table class="table table-condensed m-0">
                            <tr style="border-bottom: 1px solid #eee">
                                <td>{{ Form::label('app_skin', 'Côr da aplicação', ['class' => 'control-label']) }}</td>
                                <td class="w-1">
                                    <div class="{{ Setting::get('app_skin') }}">
                                        <div class="skin-preview skin-master" data-current-skin="{{ Setting::get('app_skin') }}" style="height: 22px; width: 22px; margin: 6px -3px 0 0;"></div>
                                    </div>
                                </td>
                                <td class="w-200px">{{-- Form::select('app_skin', trans('admin/global.skins'), Setting::get('app_skin'), ['class' =>'form-control select2']) --}}</td>
                            </tr>
                        </table>-->
                        <table class="table table-condensed">
                            <tr>
                                <td>{{ Form::label('datatable_search_enter', 'Pesquisar tabelas só quando pressionar "Enter"', ['class' => 'control-label']) }}</td>
                                <td class="check">{{ Form::checkbox('datatable_search_enter', 1, Setting::get('datatable_search_enter'), ['class' => 'ios'] ) }}</td>
                            </tr>
                            <tr>
                                <td>{{ Form::label('modals_backdrop', 'Permitir fechar a janela ao clicar fora', ['class' => 'control-label']) }}</td>
                                <td class="check">{{ Form::checkbox('modals_backdrop', 1, Setting::get('modals_backdrop'), ['class' => 'ios'] ) }}</td>
                            </tr>
                            <tr>
                                <td>{{ Form::label('fixed_menu', 'Fixar menu lateral', ['class' => 'control-label']) }}</td>
                                <td class="check">{{ Form::checkbox('fixed_menu', 1, Setting::get('fixed_menu'), ['class' => 'ios'] ) }}</td>
                            </tr>
                        </table>
                        <h4 class="section-title">Segurança</h4>
                        <table class="table table-condensed">
                            <tr>
                                <td>{{ Form::label('multiple_logins', 'Permitir multiplos logins na mesma conta', ['class' => 'control-label']) }} {!! tip('Ativando a opção, o sistema autoriza que mais do que um utilizador possa estar a usar a mesma conta em simultâneo.') !!}</td>
                                <td class="check">{{ Form::checkbox('multiple_logins', 1, Setting::get('multiple_logins'), ['class' => 'ios'] ) }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <h4 class="section-title">Documentos</h4>
                <div class="row row-15">
                    <div class="col-sm-5">
                        <div class="m-t-5 fileinput {{ @$pdfBgVertical ? 'fileinput-exists' : 'fileinput-new'}}" data-provides="fileinput">
                            <div class="fileinput-new thumbnail" style="max-height: 205px;">
                                <img src="{{ asset('assets/img/default/pdf_bg_empty.svg') }}" class="w-100">
                            </div>
                            <div class="fileinput-preview fileinput-exists thumbnail" style="max-height: 205px;">
                                @if(@$pdfBgVertical)
                                    <img src="{{ asset($pdfBgVertical) }}?v={{ time() }}" class="w-100">
                                @endif
                            </div>
                            <div>
                                <span class="btn btn-default btn-block btn-sm btn-file">
                                    <span class="fileinput-new">Procurar...</span>
                                    <span class="fileinput-exists"><i class="fas fa-sync-alt"></i> Alterar</span>
                                    <input type="file" name="pdf_bg">
                                </span>
                                <a href="#" class="btn btn-danger btn-block btn-sm fileinput-exists btn-remove-bg-img" data-dismiss="fileinput">
                                    <i class="fas fa-close"></i> Remover
                                </a>
                            </div>
                            {{ Form::hidden('delete_pdf_bg') }}
                        </div>
                    </div>
                    <div class="col-sm-7">
                        {{ Form::label('image', 'Papel timbrado', array('class' => 'form-label m-t-10')) }}<br/>
                        <small class="text-muted">
                            Tamanho recomendado: <br/>.PNG, 2480 x 3508 pixeis.
                        </small>
                        <hr style="margin: 10px 0"/>
                        {{ Form::label('print', 'Impressão automática:', array('class' => 'form-label')) }}<br/>
                        <small class="text-muted">
                            Ao gerar o documento será aberta a janela de impressão.
                        </small>
                        <table class="table table-condensed">
                            <tr>
                                <td>{{ Form::label('open_print_dialog_docs', 'Documentos A4', ['class' => 'control-label']) }}</td>
                                <td class="check">{{ Form::checkbox('open_print_dialog_docs', 1, Setting::get('open_print_dialog_docs'), ['class' => 'ios'] ) }}</td>
                            </tr>
                            <tr>
                                <td>{{ Form::label('open_print_dialog_labels', 'Etiquetas', ['class' => 'control-label']) }}</td>
                                <td class="check">{{ Form::checkbox('open_print_dialog_labels', 1, Setting::get('open_print_dialog_labels'), ['class' => 'ios'] ) }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <hr/>
                {{ Form::submit('Gravar', array('class' => 'btn btn-primary' ))}}
            </div>
        </div>
    </div>
</div>

