<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

use function GuzzleHttp\default_ca_bundle;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->engine = 'InnoDB';

                $table->id();
                $table->string('source', 50)->nullable();
                $table->string('code', 25)->nullable();
                $table->string('code_abbrv', 6)->nullable();

                $table->string('name');
                $table->string('fullname')->nullable();
                $table->date('birthdate')->nullable()->format('Y/m/d')->nullable();
                $table->enum('gender', ['m', 'f'])->nullable();
                $table->string('nacionality')->nullable();
                $table->text('about')->nullable();

                $table->string('address')->nullable();
                $table->string('zip_code')->nullable();
                $table->string('city')->nullable();
                $table->string('country')->nullable();

                $table->string('personal_email')->nullable();
                $table->string('personal_phone', 15)->nullable();
                $table->string('personal_mobile', 15)->nullable();

                $table->string('professional_email')->nullable();
                $table->string('professional_phone', 15)->nullable();
                $table->string('professional_mobile', 15)->nullable();

                $table->string('emergency_name')->nullable();
                $table->string('emergency_kinship')->nullable();
                $table->string('emergency_phone', 15)->nullable();
                $table->string('emergency_mobile', 15)->nullable();

                $table->string('id_card', 20)->nullable();
                $table->string('ss_card', 20)->nullable();
                $table->string('vat', 20)->nullable();

                $table->string('facebook')->nullable();
                $table->string('twitter')->nullable();
                $table->string('linkedin')->nullable();

                $table->unsignedBigInteger('agency_id')->nullable();
                $table->unsignedBigInteger('provider_id')->nullable();
                $table->unsignedBigInteger('professional_chief_id')->nullable();
                $table->string('professional_role')->nullable();
                $table->text('professional_obs')->nullable();
                $table->date('admission_date')->format('Y/m/d')->nullable();

                $table->string('bank_name')->nullable();
                $table->string('bank_iban')->nullable();
                $table->string('bank_swift', 30)->nullable();

                $table->string('academic_degree')->nullable();
                $table->string('teaching_institution')->nullable();
                $table->string('course')->nullable();
                $table->string('course_avaliation', 5)->nullable();

                $table->string('fiscal_address')->nullable();
                $table->string('fiscal_zip_code')->nullable();
                $table->string('fiscal_city')->nullable();
                $table->string('fiscal_country')->nullable();
                $table->string('fiscal_deficiency', 20)->nullable();
                $table->integer('fiscal_titularity')->nullable();
                $table->integer('fiscal_dependents')->nullable();

                $table->decimal('salary_price', 10, 2)->nullable();
                $table->decimal('salary_value_hour', 10, 2)->nullable();
                $table->decimal('salary_food_allowance', 10, 2)->nullable();
                $table->decimal('salary_expenses', 10, 2)->nullable();
                $table->integer('salary_working_time_exemption')->nullable();
                $table->text('salary_obs')->nullable();

                $table->string('phone')->nullable();
                $table->string('mobile')->nullable();
                $table->text('obs')->nullable();

                $table->float('comission_percent', 10, 2)->nullable();
                $table->enum('civil_status', ['single', 'married', 'union', 'divorced', 'widower'])->nullable();

                $table->string('email')->unique();
                $table->string('password');
                $table->string('uncrypted_password')->nullable();

                $table->timestamp('last_login')->nullable();
                $table->integer('ip')->nullable();

                $table->string('session_id')->nullable();
                $table->string('filehost')->nullable();
                $table->string('filepath')->nullable();
                $table->string('filename')->nullable();

                $table->tinyInteger('login_app');
                $table->tinyInteger('active');

                $table->string('allowed_actions')->nullable();
                $table->rememberToken();
                $table->string('api_token', 16)->nullable();

                $table->tinyInteger('is_operator');
                $table->tinyInteger('is_developer')->nullable();
                $table->tinyInteger('login_admin')->nullable();

                $table->tinyInteger('location_enabled');
                $table->tinyInteger('location_denied');
                $table->string('location_lat', 20)->nullable();
                $table->string('location_lng', 20)->nullable();
                $table->string('location_marker')->nullable();
                $table->timestamp('location_last_update')->nullable();

                $table->smallInteger('count_notices')->nullable();
                $table->text('settings')->nullable();
                $table->longText('popup_notification')->nullable();

                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (!Schema::hasTable('password_resets')) {
            Schema::create('password_resets', function (Blueprint $table) {
                $table->engine = 'InnoDB';

                $table->string('email')->index();
                $table->string('token');
                $table->timestamp('created_at')->nullable();
            });
        }

        if (!Schema::hasTable('failed_jobs')) {
            Schema::create('failed_jobs', function (Blueprint $table) {
                $table->engine = 'InnoDB';

                $table->id();
                $table->string('uuid')->unique();
                $table->text('connection');
                $table->text('queue');
                $table->longText('payload');
                $table->longText('exception');
                $table->timestamp('failed_at')->useCurrent();
            });
        }

        if (!Schema::hasTable('personal_access_tokens')) {
            Schema::create('personal_access_tokens', function (Blueprint $table) {
                $table->engine = 'InnoDB';

                $table->id();
                $table->morphs('tokenable');
                $table->string('name');
                $table->string('token', 64)->unique();
                $table->text('abilities')->nullable();
                $table->timestamp('last_used_at')->nullable();
                $table->timestamps();
            });
        }
        
        if (!Schema::hasTable('customers')) {
            Schema::create('customers', function (Blueprint $table) {
                $table->engine = 'InnoDB';

                $table->id();
                $table->string('source')->default(config('app.source'));
                $table->string('code', 10)->nullable();

                $table->string('name')->nullable();
                $table->string('address')->nullable();
                $table->string('zip_code')->nullable();
                $table->string('city')->nullable();
                $table->string('country', 4)->nullable();

                $table->string('contact_email')->nullable();
                $table->string('phone')->nullable();
                $table->string('mobile')->nullable();
                $table->string('website')->nullable();
                $table->string('facebook')->nullable();

                $table->string('vat', 20)->nullable();
                $table->string('cae', 20)->nullable();
                $table->string('segsocial', 20)->nullable();

                $table->string('billing_code', 10)->nullable();
                $table->string('billing_name')->nullable();
                $table->string('billing_address')->nullable();
                $table->string('billing_zip_code')->nullable();
                $table->string('billing_city')->nullable();
                $table->string('billing_country')->nullable();
                $table->string('billing_email')->nullable();

                $table->string('payment_condition', 5)->nullable();
                $table->string('currency', 5)->default('€');

                $table->string('bank_code', 15)->nullable();
                $table->string('bank_name')->nullable();
                $table->string('bank_iban')->nullable();
                $table->string('bank_swift')->nullable();
                $table->string('bank_mandate', 25)->nullable();
                $table->string('bank_description')->nullable();

                $table->integer('balance_count_expired')->nullable();
                $table->integer('balance_count_unpaid')->nullable();
                $table->decimal('balance_total_unpaid', 10, 2)->nullable();
                $table->timestamp('balance_last_update')->nullable();
                $table->string('responsable')->nullable();

                $table->integer('manager_id')->nullable();
                $table->integer('server_id')->nullable();

                $table->string('map_lat', 30)->nullable();
                $table->string('map_lng', 30)->nullable();

                $table->date('contract_date')->format('Y/m/d')->nullable();
                $table->string('contract_plan', 30)->nullable();
                $table->string('source_name', 50)->nullable();
                $table->string('source_url')->nullable();
                $table->tinyInteger('source_blocked')->default(0);

                $table->integer('support_mins')->nullable();
                $table->integer('support_periods')->nullable();
                $table->decimal('support_price', 10, 2)->nullable();
                $table->text('obs')->nullable();

                $table->string('filepath')->nullable();
                $table->string('filename')->nullable();
                $table->string('display_name')->nullable();
                $table->string('email')->unique()->nullable();
                $table->string('password')->nullable();
                $table->string('remember_token')->nullable();

                $table->timestamp('last_login')->nullable();
                $table->integer('ip')->nullable();
                $table->tinyInteger('is_prospect')->default(0);
                $table->tinyInteger('is_particular')->default(0);
                $table->tinyInteger('is_active')->default(1);
                $table->tinyInteger('license_active')->default(1);
                $table->string(column: 'current_lang')->default('pt');

                $table->string('payment_note_email', 255)->nullable();
                $table->string('establishment_license', 255)->nullable();
                $table->string('quality_name', 255)->nullable();
                $table->string('responsible_quality_function', 255)->nullable();
                $table->string('quality_email', 255)->nullable();
                $table->string('quality_phone', 255)->nullable();
                $table->string('quality_mobile', 255)->nullable();
                $table->string('commercial_name', 255)->nullable();
                $table->string('responsible_commercial_function', 255)->nullable();
                $table->string('commercial_email', 255)->nullable();
                $table->string('commercial_phone', 255)->nullable();
                $table->string('commercial_mobile', 255)->nullable();
                $table->string('fiscal_name', 255)->nullable();

                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (!Schema::hasTable('files_repository')) {
            Schema::create('files_repository', function (Blueprint $table) {
                $table->engine = 'InnoDB';

                $table->id();
                $table->string('source')->default(config('app.source'));
                $table->unsignedBigInteger('parent_id');
                $table->string('name');
                $table->string('filepath');
                $table->string('filename');
                $table->string('filehost');
                $table->string('extension', 6)->nullable();
                $table->string('filesize')->nullable();
                $table->morphs('target');
                $table->morphs('source');
                $table->tinyInteger('is_folder')->default(0);
                $table->tinyInteger('is_static')->default(0);
                $table->tinyInteger('operator_visible')->default(0);
                $table->tinyInteger('customer_visible')->default(0);
                $table->integer('count_folders');
                $table->integer('count_files');
                $table->text('obs');
                $table->integer('sort')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (!Schema::hasTable('login_logs')) {
            Schema::create('login_logs', function (Blueprint $table) {
                $table->engine = 'InnoDB';

                $table->id();
                $table->string('source')->default(config('app.source'));
                $table->unsignedBigInteger('user_id');
                $table->string('target')->nullable();
                $table->tinyInteger('remember')->default(0);
                $table->string('ip');
                $table->tinyInteger('success')->default(0);
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (!Schema::hasTable('notifications')) {
            Schema::create('notifications', function (Blueprint $table) {
                $table->engine = 'InnoDB';

                $table->id();
                $table->timestamp('alert_at');
                $table->tinyInteger('read')->default(0);
                $table->unsignedInteger('sender')->nullable();
                $table->unsignedInteger('recipient');
                $table->string('type');
                $table->string('params');
                $table->string('title')->nullable();
                $table->string('message');
                $table->string('source_class');
                $table->integer('source_id');
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (!Schema::hasTable('change_logs')) {
            Schema::create('change_logs', function (Blueprint $table) {
                $table->engine = 'InnoDB';

                $table->id();
                $table->string('source')->default(config('app.source'));
                $table->unsignedBigInteger('source_id');
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('customer_id');
                $table->string('action');
                $table->text('old')->nullable();
                $table->text('new')->nullable();
                $table->tinyInteger('is_api')->default(0);
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('change_logs');
        Schema::dropIfExists('servers');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('login_logs');
        Schema::dropIfExists('files_repository');
        Schema::dropIfExists('customers');
        Schema::dropIfExists('personal_access_tokens');
        Schema::dropIfExists('failed_jobs');
        Schema::dropIfExists('password_resets');
        Schema::dropIfExists('users');
    }
};
