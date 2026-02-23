@section('title')
{{trans('account/orders.orders')}}
@stop

@section('account-content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box no-border">
                <div class="box-body">
                    <ul class="datatable-filters list-inline hide pull-left" data-target="#datatable">
                        <li>
                            <div class="btn-group btn-group-sm" role="group">
                                <button type="button" class="btn btn-filter-datatable btn-default">
                                    <i class="fas fa-filter"></i> {{trans('account/orders.filter')}} <i class="fas fa-angle-down"></i>
                                </button>
                            </div>
                        </li>
                    </ul>
                    <div class="datatable-filters-extended m-t-5 hide {{ Request::has('filter') ? 'active' : '' }}"
                        data-target="#datatable">
                        <ul class="list-inline pull-left">

                            <li style="width: 120px" class="input-sm">
                                <strong>{{trans('account/orders.status')}}:</strong><br />
                                {{ Form::select('status', ['' => trans('account/orders.all')] + $status, Request::has('status') ? Request::get('status') : null, ['class' => 'form-control filter-datatable select2']) }}
                            </li>
                            <li class="form-group-sm hidden-xs input-sm">
                                <strong>{{trans('account/orders.registration-date')}}:</strong><br/>
                                <div class="input-group input-sm w-220px">
                                    {{ Form::text('registration_date_min', fltr_val(Request::all(), 'registration_date_min'), ['class' => 'form-control datepicker filter-datatable w-20px', 'placeholder' => trans('account/orders.begin'), 'autocomplete' => 'field-1']) }}
                                    <span class="input-group-addon">{{trans('account/orders.until')}}</span>
                                    {{ Form::text('registration_date_max', fltr_val(Request::all(), 'registration_date_max'), ['class' => 'form-control datepicker filter-datatable w-20px', 'placeholder' => trans('account/orders.end'), 'autocomplete' => 'field-1']) }}
                                </div>
                            </li>
                            <li class="form-group-sm hidden-xs input-sm">
                                <strong>{{trans('account/orders.delivery-date')}}:</strong><br/>
                                <div class="input-group input-sm w-220px">
                                    {{ Form::text('delivery_date_min', fltr_val(Request::all(), 'delivery_date_min'), ['class' => 'form-control datepicker filter-datatable w-20px', 'placeholder' => trans('account/orders.begin'), 'autocomplete' => 'field-1']) }}
                                    <span class="input-group-addon">{{trans('account/orders.until')}}</span>
                                    {{ Form::text('delivery_date_max', fltr_val(Request::all(), 'delivery_date_max'), ['class' => 'form-control datepicker filter-datatable w-20px', 'placeholder' => trans('account/orders.end'), 'autocomplete' => 'field-1']) }}
                                </div>
                            </li>

                            <li style="width: 120px" class="input-sm">
                                <strong>{{trans('account/orders.approved')}}:</strong><br />
                                {{ Form::select('approved_by_customer', array('' => trans('account/orders.all'), '1' => trans('account/orders.yes'), '0' => trans('account/orders.no')), fltr_val(Request::all(), 'approved_by_customer'), array('class' => 'form-control input-sm filter-datatable select2')) }}
                            </li>
                            <li>
                                <div class="checkbox">
                                    <label>
                                        {{ Form::checkbox('unread_messages', 1, Request::has('unread_messages') ? Request::get('unread_messages') : 0) }}
                                        {{trans('account/orders.filter-unread-message')}}
                                    </label>
                                </div>
                            </li>

                            
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    

                    <div class="table-responsive">
                        <table id="datatable" class="table table-striped table-dashed table-hover table-condensed">
                            <thead>
                                <tr>
                                    <th class="w-1">{{ Form::checkbox('select-all', '') }}</th>
                                    <th class=""></th>
                                    <th class="w-2">{{trans('account/orders.serie')}}</th>
                                    <th class="w-2">{{trans('account/orders.code')}}</th>
                                    <th class="w-6">{{trans('account/orders.registration-date')}}</th>
                                    <th class="w-6">{{trans('account/orders.delivery-date')}}</th>
                                    <th class="w-5">Chat</th>
                                    <th class="w-1">{{trans('account/orders.approved')}}</th>
                                    <th class="w-1">{{trans('account/orders.status')}}</th>
                                    <th class="w-2">{{trans('account/orders.actions')}}</th>
                                    {{-- <th class="w-2">{{trans('account/orders.approve')}}</th> --}}
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('scripts')
    <script type="text/javascript">
        var oTable;
        $(document).ready(function() {
            oTable = $('#datatable').DataTable({
                columns: [
                    {data: 'select', name: 'select', orderable: false, searchable: false},
                    {data: 'id', name: 'id', visible: false},
                    {data: 'serie_code',name: 'serie_code',orderable: false},
                    {data: 'code',name: 'code'},
                    {data: 'registration_date',name: 'registration_date'},
                    {data: 'delivery_date',name: 'delivery_date'},
                    {data: 'chat',name: 'chat'},
                    {data: 'approved_by_customer',name: 'approved_by_customer'},
                    {data: 'status',name: 'status'},
                    {data: 'actions', name: 'actions', orderable: false, searchable: false}, 
                    // {data: 'approve',name: 'approve'},                   
                ],
                order: [
                    [3, "desc"]
                ],
                pageLength: 10,
                ajax: {
                    url: "{{ route('account.orders.datatable') }}",
                    type: "POST",
                    data: function(d) {
                        d.status                       = $('select[name=status]').val();
                        d.approved_by_customer         = $('select[name=approved_by_customer]').val();
                        d.unread_messages              = $('input[name=unread_messages]:checked').length;

                        d.registration_date_min                          = $('input[name=registration_date_min]').val();
                        d.registration_date_max                          = $('input[name=registration_date_max]').val();

                        d.delivery_date_min                             = $('input[name=delivery_date_min]').val();
                        d.delivery_date_max                             = $('input[name=delivery_date_max]').val();
                    },
                    beforeSend: function() {
                        Datatables.cancelDatatableRequest(oTable)
                    },
                    complete: function() {
                        Datatables.complete()
                    }
                }
            });

            $('.filter-datatable').on('change', function(e) {
                oTable.draw();
                e.preventDefault();

                let exportUrl = Url.removeQueryString($('[data-toggle="export-url"]').attr('href'));
                exportUrl = exportUrl + '?' + Url.getQueryString(Url.current())
                $('[data-toggle="export-url"]').attr('href', exportUrl);
            });

             
        //show concluded reservations
        $(document).on('ifChanged', '[name="unread_messages"]', function (e) {
           
           oTable.draw();
           e.preventDefault();

           var name = $(this).attr('name');
           var value = $(this).is(':checked');
           value = value == false ? 0 : 1;

           newUrl = Url.updateParameter(Url.current(), name, value)
           Url.change(newUrl);
       });


        });
    </script>
@stop
