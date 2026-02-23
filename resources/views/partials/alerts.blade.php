{{-- Success --}}
@if(Session::has('success'))
    <div class="alert alert-success alert-dismissable margin-bottom-30">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <i class="fa fa-check bigger-160 vertical-align-middle"></i>
        {{ Session::get('success') }}
    </div>
@endif

{{-- Error --}}
@if(Session::has('error'))
    <div class="alert alert-danger alert-dismissable margin-bottom-30">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <i class="fa fa-exclamation-circle bigger-160 vertical-align-middle"></i>
        {{ Session::get('error') }}
    </div>
@endif

{{-- Warning --}}
@if(Session::has('warning'))
    <div class="alert alert-warning alert-dismissable margin-bottom-30">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <i class="fa fa-warning bigger-160 vertical-align-middle"></i>
        {{ Session::get('warning') }}
    </div>
@endif

{{-- Info --}}
@if(Session::has('info'))
    <div class="alert alert-info alert-dismissable margin-bottom-30">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <i class="fa fa-info bigger-160 vertical-align-middle"></i>
        {{ Session::get('info') }}
    </div>
@endif

{{-- Validator --}}
@if(Session::get('errors'))
    <div class="alert alert-danger margin-bottom-30">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="fa fa-exclamation-circle"></i> Formulário inválido! Corrija os erros abaixo.</h4>
        @foreach($errors->all() as $error)
            {{ $error }} <br>
        @endforeach
    </div>
@endif