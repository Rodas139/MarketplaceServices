{{ Form::model($customer, $formOptions) }}
<div class="box no-border">
    <div class="box-body">
        <div class="row row-5">
            <div class="col-sm-8">
                <div class="row row-5">
                    <div class="col-sm-7">
                        <div class="row row-5">
                            <div class="col-sm-2">
                                <div class="form-group is-required">
                                    {{ Form::label('code', 'Código') }}
                                    {{ Form::text('code', $customer->is_prospect ? 'N/A' : null, ['class' => 'form-control', 'required', $customer->id ? 'disabled' : '', 'maxlength' => 10,]) }}
                                </div>
                            </div>
                            <div class="col-sm-10">
                                <div class="form-group is-required">
                                    {{ Form::label('name', 'Designação') }}
                                    {{ Form::text('name', null, ['class' => 'form-control', 'required', $customer->final_consumer ? 'readonly' : '']) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-5">
                        <div class="row row-5">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    {{ Form::label('manager_id', 'Gestor') }}
                                    {{ Form::select('manager_id', ['' => ''] + $managers, null, ['class' => 'form-control select2']) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row row-5">
                    <div class="col-sm-12">
                        <div class="form-group">
                            {{ Form::label('address', 'Morada') }}
                            {{ Form::text('address', null, ['class' => 'form-control']) }}
                        </div>
                    </div>
                </div>
                <div class="row row-5">
                    <div class="col-sm-2">
                        {{ Form::label('zip_code', 'Código Postal') }}
                        {{ Form::text('zip_code', null, ['class' => 'form-control']) }}
                    </div>
                    <div class="col-sm-6">
                        {{ Form::label('city', 'Localidade') }}
                        {{ Form::text('city', null, ['class' => 'form-control']) }}
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            {{ Form::label('country', 'País') }}
                            {{ Form::select('country', ['' => ''] + trans('country'), $customer->exists ? null : 'pt', ['class' => 'form-control select2']) }}
                        </div>
                    </div>
                </div>
                <div class="row row-5">
                    <div class="col-sm-6">
                        <div class="form-group">
                            {{ Form::label('contact_email', 'E-mail Comunicações') }}
                            {{ Form::text('contact_email', null, ['class' => 'form-control email nospace']) }}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {{ Form::label('mobile', 'Telemóvel') }}
                            {{ Form::text('mobile', null, ['class' => 'form-control phone nospace']) }}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {{ Form::label('phone', 'Telefone') }}
                            {{ Form::text('phone', null, ['class' => 'form-control phone nospace']) }}
                        </div>
                    </div>
                </div>
                <div class="row row-5">
                    <div class="col-sm-6">
                        <div class="form-group">
                            {{ Form::label('shipment_email', 'E-mail Encomendas') }}
                            {{ Form::text('shipment_email', null, ['class' => 'form-control email nospace']) }}
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            {{ Form::label('payment_note_email', 'E-mail Notas de Pagamento') }}
                            {{ Form::text('payment_note_email', null, ['class' => 'form-control email nospace']) }}
                        </div>
                    </div>
                </div>
                
                {{-- <h4 class="form-divider">Dados fiscais</h4>
                
                <div class="row row-5 p-b-20">
                    <div class="col-sm-5">
                        <div class="form-group m-b-0">
                            {{ Form::label('fiscal_name', 'Nome Fiscal') }}
                            {{ Form::text('fiscal_name', null, ['class' => 'form-control']) }}
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group m-b-0">
                            {{ Form::label('vat', 'Nif') }}
                            {{ Form::text('vat', null, ['class' => 'form-control']) }}
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group m-b-0">
                            {{ Form::label('payment_condition', 'Condição de Pagamento') }}
                            {{ Form::text('payment_condition', null, ['class' => 'form-control']) }}
                        </div>
                    </div>
                </div>

                <div class="row row-5 p-b-20">
                    
                    <div class="col-sm-5">
                        <div class="form-group m-b-0">
                            {{ Form::label('segsocial', 'Seg. Social') }}
                            {{ Form::text('segsocial', null, ['class' => 'form-control']) }}
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group m-b-0">
                            {{ Form::label('bank_iban', 'IBAN') }}
                            {{ Form::text('bank_iban', null, ['class' => 'form-control']) }}
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group m-b-0">
                            {{ Form::label('bank_swift', 'SWIFT') }}
                            {{ Form::text('bank_swift', null, ['class' => 'form-control']) }}
                        </div>
                    </div>
                </div>

                <h4 class="form-divider">Dados Qualidade</h4>
                <div class="row row-5 p-b-20">
                    <div class="col-sm-7">
                        <div class="form-group m-b-0">
                            {{ Form::label('quality_name', 'Nome Responsável') }}
                            {{ Form::text('quality_name', null, ['class' => 'form-control']) }}
                        </div>
                    </div>
                    <div class="col-sm-5">
                        <div class="form-group m-b-0">
                            {{ Form::label('responsible_quality_function', 'Função') }}
                            {{ Form::text('responsible_quality_function', null, ['class' => 'form-control']) }}
                        </div>
                    </div>
                </div>
                <div class="row row-5">
                    <div class="col-sm-6">
                        <div class="form-group">
                            {{ Form::label('quality_email', 'E-mail') }}
                            {{ Form::text('quality_email', null, ['class' => 'form-control email nospace']) }}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {{ Form::label('quality_phone', 'Telemóvel') }}
                            {{ Form::text('quality_phone', null, ['class' => 'form-control phone nospace']) }}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {{ Form::label('quality_mobile', 'Telemóvel') }}
                            {{ Form::text('quality_mobile', null, ['class' => 'form-control phone nospace']) }}
                        </div>
                    </div>
                </div>

                <h4 class="form-divider">Dados Comercial</h4>
                <div class="row row-5 p-b-20">
                    <div class="col-sm-7">
                        <div class="form-group m-b-0">
                            {{ Form::label('commercial_name', 'Nome Responsável') }}
                            {{ Form::text('commercial_name', null, ['class' => 'form-control']) }}
                        </div>
                    </div>
                    <div class="col-sm-5">
                        <div class="form-group m-b-0">
                            {{ Form::label('responsible_commercial_function', 'Função') }}
                            {{ Form::text('responsible_commercial_function', null, ['class' => 'form-control']) }}
                        </div>
                    </div>
                </div>
                <div class="row row-5">
                    <div class="col-sm-6">
                        <div class="form-group">
                            {{ Form::label('commercial_email', 'E-mail') }}
                            {{ Form::text('commercial_email', null, ['class' => 'form-control email nospace']) }}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {{ Form::label('commercial_phone', 'Telemóvel') }}
                            {{ Form::text('commercial_phone', null, ['class' => 'form-control phone nospace']) }}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {{ Form::label('commercial_mobile', 'Telemóvel') }}
                            {{ Form::text('commercial_mobile', null, ['class' => 'form-control phone nospace']) }}
                        </div>
                    </div>
                </div>

                <h4 class="form-divider">Outros dados</h4>
                <div class="row row-5">
                    <div class="col-sm-4">
                        <div class="form-group m-b-0">
                            {{ Form::label('website', 'Website') }}
                            {{ Form::text('website', null, ['class' => 'form-control']) }}
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group m-b-0">
                            {{ Form::label('facebook', 'Facebook') }}
                            {{ Form::text('facebook', null, ['class' => 'form-control']) }}
                        </div>
                    </div>

                </div> --}}
            </div>
            <div class="col-sm-4">
                <div class="p-l-30">

                    <h4 class="form-divider">Anotações</h4>
                    <div class="form-group m-b-0">
                        {{ Form::textarea('obs', null, ['class' => 'form-control', 'rows' => 15, 'style' => 'min-height:50px']) }}
                    </div>
                </div>
            </div>
        </div>
        <hr />
        <button type="submit" class="btn btn-primary pull-left">Gravar</button>
        <div class="clearfix"></div>
    </div>
</div>
@if($customer->is_prospect)
{{ Form::hidden('is_prospect', 1) }}
@endif
{{ Form::close() }}
