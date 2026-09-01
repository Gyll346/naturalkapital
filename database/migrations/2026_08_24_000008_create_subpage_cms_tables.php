<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabel Komponen Pendukung LGOS
        Schema::create('lgos_components', function (Blueprint $table) {
            $table->id();
            $table->string('component_name');
            $table->string('code')->nullable(); // misal: KOMPONEN 01
            $table->string('role')->nullable(); // misal: Mengapa Kami Ada
            $table->text('description');
            $table->string('document_pdf_path')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. Tabel Portfolio Program & Proyek YNKI
        Schema::create('portfolio_projects', function (Blueprint $table) {
            $table->id();
            $table->string('project_title');
            $table->string('slug')->unique();
            $table->string('category')->default('Landscape Governance'); // Landscape Governance, Natural Capital, Sustainable Commodity, dll
            $table->string('location')->nullable(); // misal: Kubu Raya, Kayong Utara, Kapuas Hulu
            $table->string('partner_donor')->nullable(); // misal: TFCA Kalimantan, UNDP, KLHK
            $table->string('period')->nullable(); // misal: 2023 - 2025
            $table->text('summary');
            $table->longText('description')->nullable();
            $table->string('image_cover_path')->nullable();
            $table->string('document_pdf_path')->nullable();
            $table->integer('sort_order')->default(0);
            $table->enum('status', ['ongoing', 'completed'])->default('ongoing');
            $table->timestamps();
        });

        // 3. Tabel Laporan Transparansi & Kebijakan
        Schema::create('transparency_reports', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->integer('report_year');
            $table->string('category')->default('Laporan Tahunan'); // Laporan Tahunan, Laporan Audit Keuangan, Dokumen Kebijakan
            $table->text('summary')->nullable();
            $table->string('file_pdf_path');
            $table->string('file_size')->nullable(); // misal: 3.4 MB
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 4. Tabel Story Foto & Video Lapangan
        Schema::create('media_stories', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('media_type', ['photo', 'video'])->default('photo');
            $table->string('category')->default('Restorasi Gambut'); // misal: Restorasi Gambut, Masyarakat Adat, dll
            $table->string('location')->nullable();
            $table->text('caption')->nullable();
            $table->string('image_path')->nullable();
            $table->string('youtube_url')->nullable();
            $table->string('photographer_credits')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media_stories');
        Schema::dropIfExists('transparency_reports');
        Schema::dropIfExists('portfolio_projects');
        Schema::dropIfExists('lgos_components');
    }
};
