<?php
// database/migrations/..._create_documents_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('type'); // Contoh: ktp, npwp, sk_terbaru, dll.
            $table->string('file_path');
            $table->string('original_name');
            $table->enum('status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->foreignId('verified_by')->nullable()->constrained('users'); // ID Admin yang memverifikasi
            $table->timestamp('verified_at')->nullable();
            $table->text('rejection_note')->nullable(); // Catatan jika ditolak
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};