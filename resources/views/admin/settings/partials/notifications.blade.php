<div class="box no-border">
    <div class="box-body">
        <div class="row">
            <div class="col-sm-4">
                <h4 class="section-title">
                    <a href="?tab=customization"
                       data-toggle="tooltip"
                       title="Altere o som da aplicação só para a sua conta sem afetar outros utilizadores."
                       style="margin: -4px;" class="btn btn-xs btn-primary pull-right">
                        Personalizar para mim
                    </a>
                    Som Notificação
                </h4>
                <table class="table table-condensed m-b-0" style="border-bottom: 1px solid #eee">
                    <tr>
                        <td>{{ Form::label('notification_sound', 'Som notificação', ['class' => 'control-label']) }}</td>
                        <td class="w-50px">{{ Form::select('notification_sound', @$notificationSounds,  Setting::get('notification_sound') ? Setting::get('notification_sound') : 'notification09', ['class' => 'select2'] ) }}</td>
                        <td class="w-1">
                            <button type="button" class="btn btn-sm btn-default btn-play-notification" data-toggle="tooltip" title="Clique para ouvir o som">
                                <i class="fas fa-volume-up"></i>
                            </button>
                        </td>
                    </tr>
                </table>
                <table class="table table-condensed">
                    <tr>
                        <td>{{ Form::label('disable_notification_sound', 'Desligar som de notificação', ['class' => 'control-label']) }}</td>
                        <td class="check">{{ Form::checkbox('disable_notification_sound', 1,  Setting::get('disable_notification_sound'), ['class' => 'ios'] ) }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-sm-4">
                <h4 class="section-title"><i class="fas fa-euro"></i> Faturação</h4>
                <table class="table table-condensed m-0" style="border-top: 1px solid #eee">
                    <tr>
                        <td>
                            {{ Form::label('billing_send_balance_auto', 'Enviar contas correntes automático', ['class' => 'control-label']) }}
                            {!! tip('Ative esta opção para o sistema enviar a conta corrente automáticamente aos seus clientes no dia 1 de cada mês.') !!}
                        </td>
                        <td class="check">{{ Form::checkbox('billing_send_balance_auto', 1, Setting::get('billing_send_balance_auto'), ['class' => 'ios'] ) }}</td>
                    </tr>
                    <tr>
                        <td>
                            {{ Form::label('billing_remember_duedate', 'E-mail de aviso vencimento faturas', ['class' => 'control-label']) }}
                            {!! tip('Esta opção envia um e-mail ao cliente a relembrar a data de vencimento das faturas.') !!}
                        </td>
                        <td class="check">{{ Form::checkbox('billing_remember_duedate', 1, Setting::get('billing_remember_duedate'), ['class' => 'ios'] ) }}</td>
                    </tr>
                </table>
                <table class="table table-condensed m-0" style="border-top: 1px solid #eee">
                    <table class="table table-condensed m-0" style="border-top: 1px solid #eee">
                        <tr>
                            <td>
                                {{ Form::label('billing_remember_duedate_days', 'Enviar e-mail de vencimentos ao faltarem...') }}
                                <div class="input-group">
                                    {{ Form::text('billing_remember_duedate_days', Setting::get('billing_remember_duedate_days'), ['class' =>'form-control', 'placeholder' => '10,5,3,2,1']) }}
                                    <div class="input-group-addon">dias</div>
                                </div>
                            </td>
                        </tr>
                    </table><br>
                </table>
            </div>

            <div class="col-sm-12">
                <hr/>
                {{ Form::submit('Gravar', array('class' => 'btn btn-primary' ))}}
            </div>
        </div>
    </div>
</div>

