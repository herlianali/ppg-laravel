<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        Schema::create('lapor_diris', function (Blueprint $table) {
            $table->id();

            // Step 1: Biodata Pribadi
            $table->string('simpkb_id');
            $table->string('nama_lengkap');
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin', ['Laki-Laki', 'Perempuan']);
            $table->string('agama')->nullable();
            $table->string('no_kk', 16)->nullable();
            $table->string('nuptk')->nullable();
            $table->string('asal_pt')->nullable();
            $table->string('asal_prodi')->nullable();
            $table->string('bidang_studi')->nullable();
            $table->string('abk')->nullable();
            $table->decimal('ipk', 3, 2)->nullable();
            $table->string('no_hp')->nullable();
            $table->string('email')->nullable();

            // Step 2: Alamat
            $table->text('alamat')->nullable();
            $table->string('kelurahan')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('kabupaten')->nullable();
            $table->string('provinsi')->nullable();
            $table->string('kode_pos')->nullable();

            // Step 3: Data Orang Tua
            $table->string('nama_ayah')->nullable();
            $table->string('nama_ibu')->nullable();
            $table->string('nik_ayah')->nullable();
            $table->string('nik_ibu')->nullable();
            $table->string('gaji_ayah')->nullable();
            $table->string('gaji_ibu')->nullable();
            $table->string('pekerjaan_ayah')->nullable();
            $table->string('pekerjaan_ibu')->nullable();
            $table->date('tgl_lahir_ayah')->nullable();
            $table->date('tgl_lahir_ibu')->nullable();
            $table->string('pendidikan_ayah')->nullable();
            $table->string('pendidikan_ibu')->nullable();

            // Step 4: Upload Dokumen (nama file disimpan, file fisik di storage)
            $table->string('file_pakta_integritas')->nullable();
            $table->string('file_biodata_mahasiswa')->nullable();
            $table->string('file_ijazah')->nullable();
            $table->string('file_transkrip')->nullable();
            $table->string('file_ktp_sim')->nullable();
            $table->string('file_surat_sehat')->nullable();
            $table->string('file_skck')->nullable();
            $table->string('file_npwp')->nullable();
            $table->string('file_napza')->nullable();
            $table->string('file_ijin_ks')->nullable();
            $table->string('file_foto')->nullable();
            $table->string('file_surat_ket_mengajar')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('lapor_diris');
    }
};
