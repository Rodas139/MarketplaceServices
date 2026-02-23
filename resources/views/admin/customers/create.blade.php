@section('title')
    {{ $customer->is_prospect ? 'Prospectos' : 'Clientes' }}
@stop

@section('content-header')
    {{ $customer->is_prospect ? 'Prospectos' : 'Clientes' }}
<small>
    Novo {{ $customer->is_prospect ? 'Prospecto' : 'Cliente' }}
</small>
@stop

@section('breadcrumb')
<li>
    @if($customer->is_prospect)
    <a href="{{ route('admin.prospects.index') }}">
        Prospectos
    </a>
    @else
    <a href="{{ route('admin.customers.index') }}">
        Clientes
    </a>
    @endif
</li>
<li class="active">
    Novo {{ $customer->is_prospect ? 'Prospecto' : 'Cliente' }}
</li>
@stop

@section('content')
@include('admin.customers.partials.info')
@include('admin.partials.modals.vat_validation')
@stop

@section('scripts')
    <script src="{{ asset('vendor/fuzzyset/fuzzyset.js') }}"></script>
    <script>
        $('[name="bank_code"]').on('change', function(){
            var $this = $(this).find('option:selected');
            $('[name="bank_swift"]').val($this.data('swift'));
            $('[name="bank_name"]').val($this.data('name'));
        });

        $('[name="address"], [name="zip_code"], [name="city"]').on('change', function(){
            locateAddress(false);
        });

        function locateAddress(ignoreSimilarity) {
            var address  = $('[name="address"]').val();
            var zip_code = $('[name="zip_code"]').val();
            var city     = $('[name="city"]').val();

            if(address != '' && zip_code != '' && city != '') {
                var address = address + ' ' + zip_code + ' ' + city;

                if (ignoreSimilarity) {
                    findAddressLocation(address)
                } else {
                    //check similiarity
                    //only update marker location if similarity inferior to 90%
                    similarity = FuzzySet(["{{ $customer->address . ' ' . $customer->zip_code . ' ' . $customer->city }}"]);
                    similarity = similarity.get(address);

                    if (similarity != null) {
                        similarity = similarity[0][0];
                    }

                    if (similarity < 0.90) { //similarity inferior to 88%
                        findAddressLocation(address)
                    }
                }
            }
        }

        var platform;
        var pixelRatio = window.devicePixelRatio || 1;
        var defaultLayers = platform.createDefaultLayers({
            tileSize: pixelRatio === 1 ? 256 : 512,
            ppi: pixelRatio === 1 ? undefined : 320
        });
    </script>
    <script type="text/javascript">

        $('[name="yearly_payment_date"]').datepicker({
            format: 'yyyy-mm-dd',
            language: 'pt'
        })

        $('[name="yearly_payment_date"]').click(function () {
            $('[name="yearly_payment_date"]').datepicker("setDate","{{ Date::today()->addYear()->format('Y-m-d') }}")
        })

        $(document).on('click', '#random-password', function () {
            $('input[name=password]').val(Str.random(8));
        });

        $('[data-dismiss="fileinput"]').on('click', function () {
            $('[name=delete_photo]').val(1);
        })

        $(document).ready(function(){
            $('a[href="#tab-{{ Request::get("tab") }}"]').trigger('click');
        })

        var parentTab = $('a[href="#tab-{{ Request::get("tab") }}"]').data('parent-tab');
        $('a[href="' + parentTab + '"]').trigger('click');


        $('[name=category_id]').on('change', function(){
            var categoryId = $(this).val();
            if(categoryId == 1) {
                $('[name="source_name"]').prop('required', true).prop('disabled', false);
                $('[name="source_name"]').closest('.form-group').addClass('is-required')
            } else {
                $('[name="source_name"]').prop('required', false).prop('disabled', true);
                $('[name="source_name"]').closest('.form-group').removeClass('is-required')
            }
        })

        //Fill nif fields with search result
        $(document).on('click', '.vv-accept', function(){
            var $modal   = $(this).closest('.modal');
            var targetId = '#' + $modal.find('[name="vv-target"]').val();
            var $target  = $(targetId);

            $('#name').val($modal.find('[name="vv-name"]').val())
            $('#address').val($modal.find('[name="vv-address"]').val())
            $('#zip_code').val($modal.find('[name="vv-zip-code"]').val())
            $('#city').val($modal.find('[name="vv-city"]').val())
            $('.mark-on-map').trigger('click');
            $modal.modal('hide');
        });

        //Hide vat validation modal
        $(document).on('click', '#modal-vat-validation .vv-cancel', function(e){
            e.preventDefault();
            $('#modal-vat-validation').hide().removeClass('in');
        })
    </script>

@stop
