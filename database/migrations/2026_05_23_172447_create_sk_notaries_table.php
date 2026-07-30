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
            Schema::create('sk_notaries', function (Blueprint $table) {
                $table->id();
                $table->string('sk_kemenkumham');
                $table->longText('alamat');
                $table->foreignId('perusahaan_id')
                    ->constrained('settings')
                    ->cascadeOnUpdate()
                    ->cascadeOnDelete();

                $table->foreignId('kota_id')
                    ->constrained('kotas')
                    ->cascadeOnUpdate()
                    ->restrictOnDelete();

                $table->date('tanggal_sk');

                $table->string('file')->nullable();
                $table->timestamps();
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::dropIfExists('sk_notaries');
        }
    };
