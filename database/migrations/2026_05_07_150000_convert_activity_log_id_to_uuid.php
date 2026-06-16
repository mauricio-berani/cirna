<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        $table = config('activitylog.table_name');
        $connection = config('activitylog.database_connection');

        // SQLite does not support dropping/renaming primary keys natively.
        // Recreate the table with UUID primary key in both drivers.
        $rows = DB::connection($connection)->table($table)->get();

        Schema::connection($connection)->drop($table);

        Schema::connection($connection)->create($table, function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('log_name')->nullable();
            $table->string('event')->nullable();
            $table->text('description');
            $table->nullableUuidMorphs('subject', 'subject');
            $table->nullableUuidMorphs('causer', 'causer');
            $table->json('properties')->nullable();
            $table->uuid('batch_uuid')->nullable();
            $table->timestamps();
            $table->index('log_name');
        });

        foreach ($rows as $row) {
            DB::connection($connection)->table($table)->insert([
                'id' => Str::uuid()->toString(),
                'log_name' => $row->log_name ?? null,
                'event' => $row->event ?? null,
                'description' => $row->description,
                'subject_type' => $row->subject_type ?? null,
                'subject_id' => $row->subject_id ?? null,
                'causer_type' => $row->causer_type ?? null,
                'causer_id' => $row->causer_id ?? null,
                'properties' => $row->properties ?? null,
                'batch_uuid' => $row->batch_uuid ?? null,
                'created_at' => $row->created_at ?? null,
                'updated_at' => $row->updated_at ?? null,
            ]);
        }
    }

    public function down(): void
    {
        $table = config('activitylog.table_name');
        $connection = config('activitylog.database_connection');

        $rows = DB::connection($connection)->table($table)->get();

        Schema::connection($connection)->drop($table);

        Schema::connection($connection)->create($table, function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('log_name')->nullable();
            $table->string('event')->nullable();
            $table->text('description');
            $table->nullableUuidMorphs('subject', 'subject');
            $table->nullableUuidMorphs('causer', 'causer');
            $table->json('properties')->nullable();
            $table->uuid('batch_uuid')->nullable();
            $table->timestamps();
            $table->index('log_name');
        });

        $sequence = 1;
        foreach ($rows as $row) {
            DB::connection($connection)->table($table)->insert([
                'id' => $sequence++,
                'log_name' => $row->log_name ?? null,
                'event' => $row->event ?? null,
                'description' => $row->description,
                'subject_type' => $row->subject_type ?? null,
                'subject_id' => $row->subject_id ?? null,
                'causer_type' => $row->causer_type ?? null,
                'causer_id' => $row->causer_id ?? null,
                'properties' => $row->properties ?? null,
                'batch_uuid' => $row->batch_uuid ?? null,
                'created_at' => $row->created_at ?? null,
                'updated_at' => $row->updated_at ?? null,
            ]);
        }
    }
};
