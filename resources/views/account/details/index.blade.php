@section('title')
    Perfil
@stop

@section('account-content')
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="box">
            <div class="box-header with-border">
                <a href="{{ route('account.index') }}" class="btn btn-default btn-sm" style="margin-right: 10px;">
                    <i class="fa fa-arrow-left"></i> Voltar
                </a>
                <h3 class="box-title">Meu Perfil</h3>
            </div>
            <div class="box-body">
                <form method="POST" action="{{ route('account.profile.update') }}">
                    @csrf

                    <div class="form-group">
                        <label for="name">Nome</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $customer->name) }}" placeholder="Nome">
                        @error('name')
                            <span class="text-danger" style="font-size: 12px;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Email</label>
                        <input type="text" class="form-control" value="{{ $customer->email }}" readonly>
                    </div>

                    <div class="form-group">
                        <label for="address">Morada</label>
                        <input type="text" name="address" id="address" class="form-control" value="{{ old('address', $customer->address) }}" placeholder="Morada">
                        @error('address')
                            <span class="text-danger" style="font-size: 12px;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="phone">Telefone</label>
                        <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone', $customer->phone) }}" placeholder="912345678" maxlength="9" minlength="9" pattern="\d{9}" inputmode="numeric">
                        @error('phone')
                            <span class="text-danger" style="font-size: 12px;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save"></i> Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop
