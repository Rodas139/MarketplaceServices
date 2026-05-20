<?php $currencySymbol = Setting::get('app_currency') ?>
<div class="row row-10">
    <h4 class="col-sm-12 m-b-10"><strong>Dashboard Estatístico</strong></h4>
    
    <div class="col-sm-6 col-md-3 col-lg-12">
        <div class="info-box info-box-xs">
            <span class="info-box-icon bg-blue">
                <i class="fas fa-users"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Total de Utilizadores</span>
                <span class="info-box-number">
                    {{$totalUsers}}
                    <br>
                    <small><a href="{{ route('admin.users.index') }}" target="_blank">Aceder</a></small>
                </span>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-md-3 col-lg-12">
        <div class="info-box info-box-xs">
            <span class="info-box-icon bg-green">
                <i class="fas fa-cog"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Serviços Ativos</span>
                <span class="info-box-number">
                    {{$activeServices}}
                    <br>
                    <small><a href="{{ route('admin.services.index') }}" target="_blank">Aceder</a></small>
                </span>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-md-3 col-lg-12">
        <div class="info-box info-box-xs">
            <span class="info-box-icon bg-yellow">
                <i class="fas fa-shopping-cart"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Total de Pedidos</span>
                <span class="info-box-number">
                    {{$totalOrders}}
                    <br>
                    <small><a href="{{ route('admin.orders.index') }}" target="_blank">Aceder</a></small>
                </span>
            </div>
        </div>
    </div>
</div>
