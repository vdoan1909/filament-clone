<?php

use App\Models\City;
use App\Models\Country;
use App\Models\Shop\Customer;
use App\Models\State;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(Country::class)
            ->constrained()
            ->cascadeOnDelete();

            $table->foreignIdFor(State::class)
            ->constrained()
            ->cascadeOnDelete();

            $table->foreignIdFor(City::class)
            ->constrained()
            ->cascadeOnDelete();

            $table->string('name');
            $table->string('email')->unique();
            $table->string('photo')->nullable();

            $table->enum(
                'gender',
                [
                    Customer::GENDER['male'],
                    Customer::GENDER['female']
                ]
            )->default(Customer::GENDER['male']);

            $table->string('phone')->nullable();
            $table->date('birthday')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
