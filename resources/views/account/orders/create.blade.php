@section('title')
    Novo Pedido
@stop

@section('account-content')
<div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Novo Pedido</h3>
                </div>
                <div class="box-body">
                    {{ Form::open(['route' => 'account.orders.store', 'method' => 'POST']) }}

                    <div class="form-group">
                        {{ Form::label('category_id', 'Categoria *') }}
                        {{ Form::select('category_id', ['' => 'Selecione uma categoria'] + $categories->pluck('name', 'id')->toArray(), null, ['class' => 'form-control', 'id' => 'category_id', 'required']) }}
                        @error('category_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        {{ Form::label('service_id', 'Serviço *') }}
                        {{ Form::select('service_id', ['' => 'Selecione um serviço'], null, ['class' => 'form-control', 'id' => 'service_id', 'required', 'disabled']) }}
                        @error('service_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        {{ Form::label('service_price', 'Preço do Serviço por Hora') }}
                        <div class="input-group">
                            <input type="text" class="form-control" id="service_price" readonly>
                            <span class="input-group-addon">€</span>
                        </div>
                    </div>

                    <div class="form-group">
                        {{ Form::label('user_id', 'Prestador de Serviço *') }}
                        {{ Form::select('user_id', ['' => 'Selecione um prestador'] , null, ['class' => 'form-control', 'id' => 'user_id', 'required', 'disabled']) }}
                        @error('user_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        {{ Form::label('date', 'Data do Pedido *') }}
                        {{ Form::date('date', null, ['class' => 'form-control', 'required']) }}
                        @error('date')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        {{ Form::label('hour', 'Horas') }}
                        {{ Form::number('hour', null, ['class' => 'form-control', 'min' => 0, 'max' => 23, 'placeholder' => '0-23']) }}
                        @error('hour')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        {{ Form::label('description', 'Descrição') }}
                        {{ Form::textarea('description', null, ['class' => 'form-control', 'rows' => 5, 'placeholder' => 'Descreva o seu pedido...']) }}
                        @error('description')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <a href="{{ route('account.index') }}" class="btn btn-default">Cancelar</a>
                        {{ Form::submit('Criar Pedido', ['class' => 'btn btn-primary pull-right']) }}
                    </div>

                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const categorySelect = document.getElementById('category_id');
    const serviceSelect = document.getElementById('service_id');
    const servicePriceInput = document.getElementById('service_price');
    const userSelect = document.getElementById('user_id');


    categorySelect.addEventListener('change', async function() {
        if (!this.value) {
            serviceSelect.disabled = true;
            serviceSelect.innerHTML = '<option value="">Selecione um serviço</option>';
            userSelect.disabled = true;
            userSelect.innerHTML = '<option value="">Selecione um prestador</option>';
            servicePriceInput.value = '';
            return;
        }

        try {
            const response = await fetch(`/api/services/by-category/${this.value}`);
            const services = await response.json();
            
            serviceSelect.innerHTML = '<option value="">Selecione um serviço</option>';
            services.forEach(service => {
                const option = document.createElement('option');
                option.value = service.id;
                option.textContent = `${service.name}`;
                serviceSelect.appendChild(option);
            });
            
            serviceSelect.disabled = false;
            userSelect.disabled = true;
            userSelect.innerHTML = '<option value="">Selecione um prestador</option>';
            servicePriceInput.value = '';
        } catch (error) {
            console.error('Erro ao carregar serviços:', error);
        }
    });


    serviceSelect.addEventListener('change', async function() {
        if (!this.value) {
            userSelect.disabled = true;
            userSelect.innerHTML = '<option value="">Selecione um prestador</option>';
            servicePriceInput.value = '';
            return;
        }

        try {

            const categoryId = categorySelect.value;
            const servicesResponse = await fetch(`/api/services/by-category/${categoryId}`);
            const services = await servicesResponse.json();
            const selectedService = services.find(s => s.id == this.value);
            
            if (selectedService) {
                servicePriceInput.value = parseFloat(selectedService.price).toFixed(2);
            }


            const providersResponse = await fetch(`/api/providers/by-service/${this.value}`);
            const providers = await providersResponse.json();
            
            userSelect.innerHTML = '<option value="">Selecione um prestador</option>';
            providers.forEach(provider => {
                const option = document.createElement('option');
                option.value = provider.id;
                const avgRating = provider.avg_rating;
                const totalReviews = provider.total_reviews || 0;
                const ratingLabel = avgRating ? ` (${avgRating}/5 - ${totalReviews} avaliacoes)` : ' (Sem avaliacao)';
                option.textContent = `${provider.name}${ratingLabel}`;
                userSelect.appendChild(option);
            });
            
            userSelect.disabled = false;
        } catch (error) {
            console.error('Erro ao carregar prestadores:', error);
        }
    });
});
</script>
@stop


