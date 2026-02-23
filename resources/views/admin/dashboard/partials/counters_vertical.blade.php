<?php $currencySymbol = Setting::get('app_currency') ?>
<div class="row row-10">
    <div class="col-sm-6 col-md-3 col-lg-12">
        <div class="info-box info-box-xs">
            <span class="info-box-icon bg-green">
                <i class="fas fa-box-open"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Novos utilizadores</span>
                <span class="info-box-number">
                    {{$usersLast24hours}}
                    <br>
                    <small><a href="{{ route('admin.users.index') }}" target="_blank">Aceder</a></small>
                </span>
            </div>
        </div>
    </div>
</div>
