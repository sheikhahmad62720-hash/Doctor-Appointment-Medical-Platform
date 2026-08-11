<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->string('type', 20)->default('text')->after('message');
            $table->string('file_path')->nullable()->after('type');
            $table->string('file_name')->nullable()->after('file_path');
            $table->unsignedBigInteger('file_size')->nullable()->after('file_name');
            $table->string('mime')->nullable()->after('file_size');
            $table->unsignedBigInteger('reply_to_id')->nullable()->after('mime');
            $table->json('deleted_for')->nullable()->after('is_delivered');
            $table->timestamp('deleted_at')->nullable()->after('deleted_for');

            $table->foreign('reply_to_id')->references('id')->on('messages')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropForeign(['reply_to_id']);
            $table->dropColumn([
                'type',
                'file_path',
                'file_name',
                'file_size',
                'mime',
                'reply_to_id',
                'deleted_for',
                'deleted_at',
            ]);
        });
    }
};
