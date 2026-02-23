{{ Form::model($user, $formOptions) }}
<div class="box no-border">
    <div class="box-body">
        <div class="row row-5">
            <div class="col-sm-12">
                <h4 class="form-divider no-border" style="margin-top: 0">Informação Salarial</h4>
                <div class="row row-5">
                    <div class="col-sm-6">
                        <div class="form-group">
                            {{ Form::label('salary_price', 'Salário Base') }}
                            <div class="input-group">
                                {{ Form::text('salary_price', null, ['class' => 'form-control decimal']) }}
                                <span class="input-group-addon">
                                    <i class="fas fa-euro-sign"></i>
                                </span>
                            </div>
                        </div>

                        <div class="form-group">
                            {{ Form::label('salary_food_allowance', 'Subsídio Alimentação') }}
                            <div class="input-group">
                                {{ Form::text('salary_food_allowance', null, ['class' => 'form-control decimal']) }}
                                <span class="input-group-addon">
                                    <i class="fas fa-euro-sign"></i>
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('salary_expenses', 'Despesas') }}
                            <div class="input-group">
                                {{ Form::text('salary_expenses', null, ['class' => 'form-control decimal']) }}
                                <span class="input-group-addon">
                                    <i class="fas fa-euro-sign"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            {{ Form::label('salary_value_hour', 'Valor/Hora') }}
                            <div class="input-group">
                                {{ Form::text('salary_value_hour', null, ['class' => 'form-control decimal']) }}
                                <span class="input-group-addon">
                                    <i class="fas fa-euro-sign"></i>
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('salary_working_time_exemption', 'Isenção Horário Trabalho') }}
                            <div class="input-group">
                                {{ Form::text('salary_working_time_exemption', null, ['class' => 'form-control decimal']) }}
                                <span class="input-group-addon">
                                    <i class="fas fa-euro-sign"></i>
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('comission_percent', 'Comissão') }}
                            @if(hasModule('prospection'))
                                <div class="input-group">
                                    {{ Form::text('comission_percent', null, array('class' =>'form-control decimal', 'maxlength' => 5)) }}
                                    <div class="input-group-addon">%</div>
                                </div>
                            @else
                                <div class="input-group" data-toggle="tooltip" title="Não possui o módulo de gestão comercial ativo.">
                                    {{ Form::text('comission_percent', null, array('class' =>'form-control', 'disabled')) }}
                                    <div class="input-group-addon">%</div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr/>
        <button type="submit" class="btn btn-primary pull-left">Gravar</button>
        <div class="clearfix"></div>
    </div>
</div>
{{ Form::hidden('active', $user->active) }}
{{ Form::close() }}
