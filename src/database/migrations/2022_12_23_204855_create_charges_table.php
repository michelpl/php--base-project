<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('charges', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('debt_id');
            $table->unique('debt_id');
            $table->string('name');
            $table->string('government_id');
            $table->string('email');
            $table->float('debt_amount');
            $table->float('paid_amount')->default(0)->nullable();
            $table->date('debt_due_date');
            $table->enum('status', ['created', 'sent', 'paid', 'underpaid', 'overpaid'])->default('created');
            $table->dateTime('paid_at')->nullable();
            $table->string('paid_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('charges');
    }
};
