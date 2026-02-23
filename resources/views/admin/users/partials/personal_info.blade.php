{{ Form::model($user, $formOptions) }}
<div class="box no-border">
    <div class="box-body">
        <div class="row row-5">
            <div class="col-sm-4 col-lg-9">
                <div class="row row-5">
                    <div class="col-sm-7">
                        <div class="row row-5">
                            <div class="col-sm-2">
                                <div class="form-group is-required">
                                    {{ Form::label('code', 'Código')}} {!! tip('Número ou código interno do colaborador') !!}
                                    {{ Form::text('code', null, array('class' =>'form-control nospace uppercase', 'maxlength' => 5, 'required')) }}
                                </div>
                            </div>
                            <div class="col-sm-10">
                                <div class="form-group is-required">
                                    {{ Form::label('fullname', 'Nome Completo') }}
                                    {{ Form::text('fullname', null, ['class' => 'form-control', 'required']) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group is-required">
                            {{ Form::label('role_id', 'Perfíl da Conta')}}
                            @if(Auth::user()->perm('admin_roles'))
                                <a href="{{ route('admin.roles.index') }}" class="pull-right m-l-10" target="_blank">Gerir Perfis</a>
                            @endif
                            @if(Auth::user()->id == $user->id)
                                {{ Form::select('role_id', [@$user->roles->first()->id => @$user->roles->first()->display_name], @$user->roles->first()->id, array('class' =>'form-control select2 roles-selection', 'required', 'disabled')) }}
                            @else
                                {{ Form::select('role_id', ['' => ''] + $roles, @$user->roles->first()->id, array('class' =>'form-control select2 roles-selection', 'required')) }}
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-1 check">
                        <div class="form-group" >
                            {{ Form::label('active', 'Ativo') }}
                            <div class="sp-3"></div>
                            {{ Form::checkbox('active', 1, $user->exists ? null : 1, ['class' => 'ios', Auth::user()->id == $user->id ? 'disabled' : ''] ) }}
                        </div>
                    </div>
                </div>
                <div class="row row-5">
                    <div class="col-sm-8">
                        <div class="row row-5">
                            <div class="col-sm-2">
                                <div class="form-group">
                                    {{ Form::label('code_abbrv', 'Cod. Abrv') }} {!! tip('A abreviatura permite identificar de forma mais clara o colaborador nas listagens do sistema.') !!}
                                    {{ Form::text('code_abbrv', null, ['class' => 'form-control nospace uppercase', 'maxlength' => 6]) }}
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    {{ Form::label('gender', 'Género') }}
                                    {{ Form::select('gender', ['' => ''] + trans('admin/users.gender'), null, ['class' => 'form-control select2']) }}
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    {{ Form::label('birthdate', 'Data Nascimento') }}
                                    <div class="input-group">
                                        {{ Form::text('birthdate', null, ['class' => 'form-control datepicker']) }}
                                        <div class="input-group-addon">
                                            <i class="fas fa-calendar-alt"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    {{ Form::label('civil_status', 'Estado Civíl') }}
                                    {{ Form::select('civil_status', ['' => ''] + trans('admin/users.civil-status'), null, ['class' => 'form-control select2']) }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-group">
                            {{ Form::label('nacionality', 'Nacionalidade') }}
                            {{ Form::text('nacionality', null, ['class' => 'form-control']) }}
                        </div>
                    </div>
                </div>
                <h4 class="form-divider"><i class="fas fa-user-tie"></i> Informação Profissional</h4>
                <div class="row row-5">
                    <div class="col-sm-5">
                        <div class="row row-5">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    {{ Form::label('admission_date', 'Data Admissão')}}
                                    <div class="input-group">
                                        {{ Form::text('admission_date', null, array('class' =>'form-control datepicker')) }}
                                        <div class="input-group-addon">
                                            <i class="fas fa-calendar-alt"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            {{ Form::label('professional_role', 'Cargo ou Função')}}
                            {{ Form::text('professional_role', null, array('class' =>'form-control')) }}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {{ Form::label('professional_chief_id', 'Chefe/Responsável') }}
                            {{ Form::select('professional_chief_id', ['' => ''] + $operatorsList, null, ['class' => 'form-control select2']) }}
                        </div>
                    </div>
                </div>
                <div class="row row-5">
                    <div class="col-md-8">
                        <div class="form-group">
                            {{ Form::label('professional_email', 'E-mail Empresa') }}
                            {{ Form::text('professional_email', null, ['class' => 'form-control nospace lowercase email']) }}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {{ Form::label('professional_mobile', 'Telemóvel Empresa') }}
                            {{ Form::text('professional_mobile', null, ['class' => 'form-control phone']) }}
                        </div>
                    </div>
                </div>

                <div class="row row-5">
                    <div class="col-sm-8" style="padding-right: 15px">
                        <h4 class="form-divider"><i class="fas fa-phone"></i> Contactos Pessoais/Residência</h4>
                        <div class="form-group">
                            {{ Form::label('address', 'Morada Residência') }}
                            {{ Form::text('address', null, ['class' => 'form-control']) }}
                        </div>
                        <div class="row row-5">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    {{ Form::label('zip_code', 'Código Postal') }}
                                    {{ Form::text('zip_code', null, ['class' => 'form-control trim']) }}
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    {{ Form::label('city', 'Localidade') }}
                                    {{ Form::text('city', null, ['class' => 'form-control']) }}
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    {{ Form::label('country', 'País') }}
                                    {{ Form::select('country', ['' => ''] + trans('country'), $user->exists ? null : 'pt', ['class' => 'form-control select2']) }}
                                </div>
                            </div>
                        </div>
                        <div class="row row-5">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    {{ Form::label('personal_email', 'E-mail pessoal') }}
                                    {{ Form::text('personal_email', null, ['class' => 'form-control nospace lowercase email']) }}
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    {{ Form::label('personal_mobile', 'Telemóvel pessoal') }}
                                    {{ Form::text('personal_mobile', null, ['class' => 'form-control phone']) }}
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    {{ Form::label('personal_phone', 'Telefone') }}
                                    {{ Form::text('personal_phone', null, ['class' => 'form-control phone']) }}
                                </div>
                            </div>
                        </div>
                        <div class="row row-5">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label><i class="fas fa-heartbeat"></i> Pessoa Contacto Emergência</label>
                                    {{ Form::text('emergency_name', null, ['class' => 'form-control']) }}
                                </div>
                            </div>
                           {{-- <div class="col-sm-12">
                                <div class="form-group">
                                    {{ Form::label('emergency_kinship', 'Parentesco') }}
                                    {{ Form::text('emergency_kinship', null, ['class' => 'form-control']) }}
                                </div>
                            </div>--}}
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label><i class="fas fa-heartbeat"></i> Telemóvel SOS</label>
                                    {{ Form::text('emergency_mobile', null, ['class' => 'form-control phone']) }}
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label><i class="fas fa-heartbeat"></i> Telefone SOS</label>
                                    {{ Form::text('emergency_phone', null, ['class' => 'form-control phone']) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4" style="padding-left: 15px">
                        <h4 class="form-divider"><i class="fas fa-landmark"></i> Dados Fiscais</h4>
                       {{-- <div class="form-group">
                            {{ Form::label('fiscal_address', 'Morada') }}
                            {{ Form::text('fiscal_address', null, ['class' => 'form-control']) }}
                        </div>
                        <div class="row row-5">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    {{ Form::label('fiscal_zip_code', 'C. Postal') }}
                                    {{ Form::text('fiscal_zip_code', null, ['class' => 'form-control trim']) }}
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    {{ Form::label('fiscal_city', 'Localidade') }}
                                    {{ Form::text('fiscal_city', null, ['class' => 'form-control']) }}
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    {{ Form::label('fiscal_country', 'País') }}
                                    {{ Form::select('fiscal_country', ['' => ''] + trans('country'), $user->exists ? null : 'pt', ['class' => 'form-control select2']) }}
                                </div>
                            </div>
                        </div>--}}
                        <div class="row row-5">
                            <div class="col-md-12">
                                <div class="form-group">
                                    {{ Form::label('id_card', 'Cartão Cidadão') }}
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fas fa-id-card"></i>
                                        </div>
                                        {{ Form::text('id_card', null, ['class' => 'form-control']) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    {{ Form::label('vat', 'Contribuinte') }}
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fas fa-id-card"></i>
                                        </div>
                                        {{ Form::text('vat', null, ['class' => 'form-control']) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    {{ Form::label('ss_card', 'Nº Seg. Social') }}
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fas fa-id-card"></i>
                                        </div>
                                        {{ Form::text('ss_card', null, ['class' => 'form-control']) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row row-5">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    {{ Form::label('fiscal_dependents', 'Dependentes') }}
                                    {{ Form::select('fiscal_dependents', range(0,10), null, ['class' => 'form-control select2']) }}
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    {{ Form::label('fiscal_titularity', 'Rendimentos') }}
                                    {{ Form::select('fiscal_titularity', ['' => '', '1' => '1 Titular', '2' => '2 Titulares'], null, ['class' => 'form-control select2']) }}
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    {{ Form::label('fiscal_deficiency', 'Deficiencia') }}
                                    {{ Form::select('fiscal_deficiency', ['' => 'Não'] + trans('admin/users.deficiencies'), null, ['class' => 'form-control select2']) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 col-lg-3">
                <div class="p-l-30">
                    <h4 >Notas e Observações</h4>
                    <div class="row row-5">
                        <div class="col-md-12">
                            <div class="form-group">
                                {{ Form::textarea('about', null, ['class' => 'form-control', 'rows' => 16]) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row row-5">
            <div class="col-sm-8">
                <h4 class="form-divider"><i class="fas fa-graduation-cap"></i> Formação Académica</h4>
                <div class="row row-5">
                    <div class="col-sm-3">
                        <div class="form-group">
                            {{ Form::label('academic_degree', 'Grau Académico') }}
                            {{ Form::select('academic_degree', ['' => ''] + trans('admin/users.academic-degrees'), null, ['class' => 'form-control select2']) }}
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            {{ Form::label('teaching_institution', 'Instituição de Ensino') }}
                            {{ Form::text('teaching_institution', null, ['class' => 'form-control']) }}
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            {{ Form::label('course', 'Curso/Formação') }}
                            {{ Form::text('course', null, ['class' => 'form-control']) }}
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            {{ Form::label('course_avaliation', 'Avaliação') }}
                            {{ Form::text('course_avaliation', null, ['class' => 'form-control']) }}
                        </div>
                    </div>
                </div>
                <h4 class="form-divider">Informação Bancária</h4>
                <div class="row row-5">
                    <div class="col-sm-4">
                        <div class="form-group">
                            {{ Form::label('bank_name', 'Nome Banco') }}
                            {{ Form::text('bank_name', null, ['class' => 'form-control']) }}
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            {{ Form::label('bank_iban', 'IBAN') }}
                            {{ Form::text('bank_iban', null, ['class' => 'form-control iban']) }}
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            {{ Form::label('bank_swift', 'BIC/SWIFT') }}
                            {{ Form::text('bank_swift', null, ['class' => 'form-control uppercase']) }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="p-l-30">
                    <h4 class="form-divider">Redes Sociais</h4>
                    <div class="form-group">
                        {{ Form::label('facebook', 'Facebook') }}
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fab fa-facebook-square"></i>
                            </div>
                            {{ Form::url('facebook', null, ['class' => 'form-control url nospace lowercase']) }}
                        </div>
                    </div>
                    <div class="form-group">
                        {{ Form::label('linkedin', 'Linkedin') }}
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fab fa-linkedin"></i>
                            </div>
                            {{ Form::url('linkedin', null, ['class' => 'form-control url nospace lowercase']) }}
                        </div>
                    </div>
                    <div class="form-group m-0">
                        {{ Form::label('twitter', 'Twitter') }}
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fab fa-twitter"></i>
                            </div>
                            {{ Form::url('twitter', null, ['class' => 'form-control url nospace lowercase']) }}
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
{{ Form::hidden('delete_photo') }}
{{ Form::close() }}
