<div class="box no-border">
    <div class="box-body">
        <div class="row">
            <div class="col-sm-6">
                <h4 class="section-title">Configuração do e-mail</h4>
                <table class="table table-condensed">
                    <tr>
                        <td class="w-230px">{{ Form::label('tickets_mail', 'E-mail para recepção pedidos', ['class' => 'control-label']) }}</td>
                        <td>{{ Form::text('tickets_mail', Setting::get('tickets_mail'), ['class' =>'form-control']) }}</td>
                    </tr>
                    <tr>
                        <td>{{ Form::label('tickets_mail_password', 'Palavra-passe do e-mail', ['class' => 'control-label']) }}</td>
                        <td>{{ Form::text('tickets_mail_password', Setting::get('tickets_mail_password'), ['class' =>'form-control']) }}</td>
                    </tr>
                    <tr style="display: non">
                        <td>{{ Form::label('tickets_mail_host', 'Servidor IMAP', ['class' => 'control-label']) }}</td>
                        <td>{{ Form::text('tickets_mail_host', Setting::get('tickets_mail_host') ? Setting::get('tickets_mail_host') : 'cp127.webserver.pt', ['class' =>'form-control']) }}</td>
                    </tr>
                    <tr style="display: non">
                        <td>{{ Form::label('tickets_mail_port', 'Porta IMAP', ['class' => 'control-label']) }}</td>
                        <td>{{ Form::text('tickets_mail_port', '143', ['class' =>'form-control']) }}</td>
                    </tr>
                    <tr style="display: non">
                        <td>{{ Form::label('tickets_mail_encryption', 'Encriptação', ['class' => 'control-label']) }}</td>
                        <td>{{ Form::text('tickets_mail_encryption', 'tls', ['class' =>'form-control']) }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-sm-6">
                <h4 class="section-title">Resposta Automática</h4>
                <table class="table table-condensed m-b-0">
                    <tr>
                        <td class="w-70"><span class="m-t-5">Ao receber novo e-mail, responder automáticamente</span></td>
                        <td class="check">{{ Form::checkbox('tickets_mail_autoresponse_active', 1, Setting::get('tickets_mail_autoresponse_active'), ['class' => 'ios'] ) }}</td>
                    </tr>
                </table>
                <table class="table table-condensed m-0" style="border-top: 1px solid #eee">
                    <tr>
                        <td>{{ Form::label('tickets_mail_notification', 'Quando receber novo pedido, enviar aviso para', ['class' => 'control-label']) }}</td>
                        <td class="w-250px">{{ Form::text('tickets_mail_notification', Setting::get('tickets_mail_notification'), ['class' =>'form-control']) }}</td>
                    </tr>
                </table>
                <table class="table table-condensed no-border m-b-0">
                    <td colspan="2">
                        {{ Form::hidden('tickets_mail_autoresponse_html', 1) }}
                        {{ Form::textarea('tickets_mail_autoresponse', Setting::get('tickets_mail_autoresponse'), ['class' =>'form-control budget-auto-response', 'id' => 'budget-auto-response']) }}
                        <p>
                            <b>Códigos de texto</b><br/>
                            Use os códigos seguintes no texto para que estes sejam substituidos pelas variáveis indicadas.
                            <BR/>
                            <span class="label label-default">_NOME_</span> - Substitui pelo nome do cliente que solicitou<br/>
                            <span class="label label-default">_TICKETID_</span> - Substitui pelo número de ticket<br/>
                            <span class="label label-default">_DATAHORA_</span> - Substitui pela data e hora em que o ticket foi recebido
                        </p>
                    </td>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <h4 class="section-title">Assinatura do E-mail</h4>
                <table class="table table-condensed no-border m-b-0">
                    <td colspan="2">
                        {{ Form::hidden('tickets_mail_signature_html', 1) }}
                        {{ Form::textarea('tickets_mail_signature', Setting::get('tickets_mail_signature'), ['class' =>'form-control budget-signature', 'id' => 'budget-signature']) }}
                    </td>
                </table>
            </div>
            <div class="col-sm-12">
                <hr/>
                {{ Form::submit('Gravar', array('class' => 'btn btn-primary' ))}}
            </div>
        </div>
    </div>
</div>

