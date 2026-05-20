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
        if (!Schema::hasTable('categorys')) {
            Schema::create('categorys', function (Blueprint $table) {
                $table->engine = 'InnoDB';

                $table->id();
                $table->string('name')->nullable();
                $table->string('description')->nullable();

                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->engine = 'InnoDB';

                $table->id();
                $table->string('name')->nullable();
                $table->string('email')->unique()->nullable(); 
                $table->string('password')->nullable();
                $table->enum('status', ['pending', 'active', 'inactive'])->default('pending')->nullable();

                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (!Schema::hasTable('services')) {
            Schema::create('services', function (Blueprint $table) {
                $table->engine = 'InnoDB';

                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->foreign('user_id')->references('id')->on('users');
                $table->unsignedBigInteger('category_id');
                $table->foreign('category_id')->references('id')->on('categorys');
                $table->string('name')->nullable();
                $table->decimal('price', 10, 2)->nullable();
                $table->enum('status', ['pending', 'active', 'inactive'])->default('pending');

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
                $table->string('name')->nullable();
                $table->string('email')->unique()->nullable();
                $table->string('address')->nullable();
                $table->string('phone', 9)->nullable();
                $table->string('password')->nullable();

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
                $table->unsignedBigInteger('sender_id')->nullable();
                $table->foreign('sender_id')->references('id')->on('users')->onDelete('set null');
                $table->string('title');
                $table->text('message');
                $table->string('type')->default('info');
                $table->string('source_class')->nullable();
                $table->integer('source_id')->nullable();
                $table->string('params')->nullable();
                $table->json('read_by_admin')->nullable();
                $table->json('read_by_customer')->nullable();
                $table->timestamp('alert_at')->useCurrent();
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


        if (!Schema::hasTable('orders')) {
            Schema::create('orders', function (Blueprint $table) {
                $table->engine = 'InnoDB';

                $table->id();
                $table->unsignedBigInteger('id_customer');
                $table->foreign('id_customer')->references('id')->on('customers');
                $table->unsignedBigInteger('user_id');
                $table->foreign('user_id')->references('id')->on('users');
                $table->unsignedBigInteger('service_id')->nullable();
                $table->foreign('service_id')->references('id')->on('services')->onDelete('set null');
                $table->integer('hour')->nullable();
                $table->date('date')->nullable();
                $table->string('description')->nullable();
                $table->enum('status', ['pending', 'accepted', 'declined', 'completed'])->default('pending');
                

                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (!Schema::hasTable('payments')) {
            Schema::create('payments', function (Blueprint $table) {
                $table->engine = 'InnoDB';

                $table->id();
                $table->unsignedBigInteger('id_order');
                $table->foreign('id_order')->references('id')->on('orders');
                $table->string('method')->nullable();
                $table->integer('total')->nullable();
                $table->enum('status', ['pending', 'paid'])->default('pending');
                

                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (!Schema::hasTable('comissions')) {
            Schema::create('comissions', function (Blueprint $table) {
                $table->engine = 'InnoDB';

                $table->id();
                $table->unsignedBigInteger('id_payment');
                $table->foreign('id_payment')->references('id')->on('payments');
                $table->decimal('value', 10, 2)->nullable();
                $table->decimal('gain', 10, 2)->nullable();
                

                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (!Schema::hasTable('comments')) {
            Schema::create('comments', function (Blueprint $table) {
                $table->engine = 'InnoDB';

                $table->id();
                $table->unsignedBigInteger('id_customer');
                $table->foreign('id_customer')->references('id')->on('customers');
                $table->unsignedBigInteger('id_order');
                $table->foreign('id_order')->references('id')->on('orders');
                $table->text('comment')->nullable();
                $table->string('image')->nullable();
                $table->integer('rating')->nullable();

                $table->timestamps();
                $table->softDeletes();
            });
        }

                
        if (!Schema::hasTable('chats')) {
            Schema::create('chats', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->id();
                $table->unsignedBigInteger('customer_id');
                $table->unsignedBigInteger('provider_id');
                $table->unsignedBigInteger('order_id')->nullable();
                $table->string('subject')->nullable();
                $table->timestamps();
                $table->softDeletes();

                $table->index('customer_id');
                $table->index('provider_id');
                $table->index('order_id');

                $table->foreign('customer_id')->references('id')->on('customers');
                $table->foreign('provider_id')->references('id')->on('users');
                $table->foreign('order_id')->references('id')->on('orders');
            });

        }

        if (!Schema::hasTable('chat_messages')) {
            Schema::create('chat_messages', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('chat_id');
                $table->enum('sender_type', ['customer', 'provider']);
                $table->unsignedBigInteger('sender_id');
                $table->text('message');
                $table->timestamps();
                $table->softDeletes();

                $table->index('chat_id');
                $table->index(['sender_type', 'sender_id']);

                $table->foreign('chat_id')
                    ->references('id')
                    ->on('chats')
                    ->onDelete('cascade');
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
        Schema::dropIfExists('comissions');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('comments');
        Schema::dropIfExists('orders');
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
        Schema::dropIfExists('categorys');
        Schema::dropIfExists('services');
        Schema::dropIfExists('chats');
        Schema::dropIfExists('chat_messages');
    }
};
