<?php
/**
 * Standalone PostgreSQL migration for Vercel serverless.
 * Creates all tables using raw PDO - no Laravel bootstrap needed.
 */

$host = $_ENV['DB_HOST'] ?? 'aws-1-us-east-1.pooler.supabase.com';
$port = $_ENV['DB_PORT'] ?? '6543';
$dbname = $_ENV['DB_DATABASE'] ?? 'postgres';
$user = $_ENV['DB_USERNAME'] ?? 'postgres.irdtskvrgeqwghthkxat';
$pass = $_ENV['DB_PASSWORD'] ?? '';

$dsn = "pgsql:host={$host};port={$port};dbname={$dbname};sslmode=require";
$pdo = new PDO($dsn, $user, $pass);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Check if migrations table exists and has entries
try {
    $check = $pdo->query("SELECT COUNT(*) FROM migrations");
    $count = $check->fetchColumn();
    if ($count > 0) {
        return; // Migrations already ran
    }
} catch (PDOException $e) {
    // Table doesn't exist, proceed with migration
}

// ── migrations table ──
$pdo->exec("CREATE TABLE IF NOT EXISTS migrations (
    id SERIAL PRIMARY KEY,
    migration VARCHAR(255) NOT NULL,
    batch INTEGER NOT NULL
)");

// ── users ──
$pdo->exec("CREATE TABLE IF NOT EXISTS users (
    id BIGSERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    role VARCHAR(50) NOT NULL DEFAULT 'petani',
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(255) NULL,
    address VARCHAR(255) NULL,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
)");

// ── password_reset_tokens ──
$pdo->exec("CREATE TABLE IF NOT EXISTS password_reset_tokens (
    email VARCHAR(255) PRIMARY KEY,
    token VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL
)");

// ── sessions ──
$pdo->exec("CREATE TABLE IF NOT EXISTS sessions (
    id VARCHAR(255) PRIMARY KEY,
    user_id BIGINT NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    payload TEXT NOT NULL,
    last_activity INTEGER NOT NULL
)");
$pdo->exec("CREATE INDEX IF NOT EXISTS idx_sessions_last_activity ON sessions(last_activity)");
$pdo->exec("CREATE INDEX IF NOT EXISTS idx_sessions_user_id ON sessions(user_id)");

// ── cache & cache_locks ──
$pdo->exec("CREATE TABLE IF NOT EXISTS cache (
    key VARCHAR(255) PRIMARY KEY,
    value TEXT NOT NULL,
    expiration BIGINT NOT NULL
)");
$pdo->exec("CREATE TABLE IF NOT EXISTS cache_locks (
    key VARCHAR(255) PRIMARY KEY,
    owner VARCHAR(255) NOT NULL,
    expiration BIGINT NOT NULL
)");

// ── jobs, job_batches, failed_jobs ──
$pdo->exec("CREATE TABLE IF NOT EXISTS jobs (
    id BIGSERIAL PRIMARY KEY,
    queue VARCHAR(255) NOT NULL,
    payload TEXT NOT NULL,
    attempts SMALLINT NOT NULL,
    reserved_at INTEGER NULL,
    available_at INTEGER NOT NULL,
    created_at INTEGER NOT NULL
)");
$pdo->exec("CREATE INDEX IF NOT EXISTS idx_jobs_queue ON jobs(queue)");

$pdo->exec("CREATE TABLE IF NOT EXISTS job_batches (
    id VARCHAR(255) PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    total_jobs INTEGER NOT NULL,
    pending_jobs INTEGER NOT NULL,
    failed_jobs INTEGER NOT NULL,
    failed_job_ids TEXT NOT NULL,
    options TEXT NULL,
    cancelled_at INTEGER NULL,
    created_at INTEGER NOT NULL,
    finished_at INTEGER NULL
)");

$pdo->exec("CREATE TABLE IF NOT EXISTS failed_jobs (
    id BIGSERIAL PRIMARY KEY,
    uuid VARCHAR(255) NOT NULL UNIQUE,
    connection TEXT NOT NULL,
    queue TEXT NOT NULL,
    payload TEXT NOT NULL,
    exception TEXT NOT NULL,
    failed_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
)");

// ── profil_lahans ──
$pdo->exec("CREATE TABLE IF NOT EXISTS profil_lahans (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    nama_lahan VARCHAR(255) NOT NULL,
    lokasi VARCHAR(255) NOT NULL,
    luas_lahan DECIMAL(10,2) NOT NULL,
    jenis_tanah VARCHAR(50) NOT NULL DEFAULT 'tanah_liat',
    deskripsi TEXT NULL,
    foto VARCHAR(255) NULL,
    is_active BOOLEAN NOT NULL DEFAULT true,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
)");

// ── riwayat_panens ──
$pdo->exec("CREATE TABLE IF NOT EXISTS riwayat_panens (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    profil_lahan_id BIGINT NOT NULL REFERENCES profil_lahans(id) ON DELETE CASCADE,
    tanggal_panen DATE NOT NULL,
    jenis_tanaman VARCHAR(255) NOT NULL,
    jumlah_hasil DECIMAL(10,2) NOT NULL,
    satuan VARCHAR(255) NOT NULL DEFAULT 'kg',
    harga_per_kg DECIMAL(10,2) NULL,
    total_pendapatan DECIMAL(15,2) NULL,
    catatan TEXT NULL,
    bukti_foto VARCHAR(255) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
)");

// ── setoran_penggilingan ──
$pdo->exec("CREATE TABLE IF NOT EXISTS setoran_penggilingan (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    tanggal_setoran DATE NOT NULL,
    jenis_hasil_panen VARCHAR(255) NOT NULL,
    jumlah_setoran DECIMAL(10,2) NOT NULL,
    biaya_penggilingan DECIMAL(10,2) NULL,
    hasil_bersih DECIMAL(10,2) NULL,
    total_pendapatan DECIMAL(15,2) NULL,
    bukti_nota VARCHAR(255) NULL,
    catatan TEXT NULL,
    status VARCHAR(50) NOT NULL DEFAULT 'pending',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
)");

// ── penerimaan_gabah ──
$pdo->exec("CREATE TABLE IF NOT EXISTS penerimaan_gabah (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    nama_petani VARCHAR(255) NOT NULL,
    asal_lahan VARCHAR(255) NULL,
    tanggal DATE NOT NULL,
    jumlah_gabah DECIMAL(10,2) NOT NULL,
    kualitas_gabah VARCHAR(50) NOT NULL DEFAULT 'kering',
    status VARCHAR(50) NOT NULL DEFAULT 'menunggu',
    bukti_foto VARCHAR(255) NULL,
    catatan TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
)");

// ── operasional_penggilingan ──
$pdo->exec("CREATE TABLE IF NOT EXISTS operasional_penggilingan (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    penerimaan_gabah_id BIGINT NULL REFERENCES penerimaan_gabah(id) ON DELETE SET NULL,
    batch_id VARCHAR(255) NOT NULL UNIQUE,
    tanggal_proses DATE NOT NULL,
    jumlah_gabah_masuk DECIMAL(10,2) NOT NULL,
    status VARCHAR(50) NOT NULL DEFAULT 'menunggu',
    catatan TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
)");

// ── riwayat_produksi ──
$pdo->exec("CREATE TABLE IF NOT EXISTS riwayat_produksi (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    operasional_id BIGINT NULL REFERENCES operasional_penggilingan(id) ON DELETE SET NULL,
    batch_id VARCHAR(255) NOT NULL,
    tanggal_proses DATE NOT NULL,
    jumlah_gabah DECIMAL(10,2) NOT NULL,
    jumlah_beras DECIMAL(10,2) NOT NULL,
    jenis_beras VARCHAR(255) NULL,
    notifikasi_rendemen_rendah BOOLEAN NOT NULL DEFAULT false,
    catatan TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
)");

// ── pengiriman_beras ──
$pdo->exec("CREATE TABLE IF NOT EXISTS pengiriman_beras (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    nama_packager VARCHAR(255) NOT NULL,
    jenis_beras VARCHAR(100) NOT NULL DEFAULT 'medium',
    jumlah_karung INTEGER NOT NULL,
    berat_per_karung DECIMAL(8,2) NULL,
    tanggal_kirim DATE NOT NULL,
    biaya_logistik DECIMAL(15,2) NULL,
    status VARCHAR(50) NOT NULL DEFAULT 'menunggu',
    bukti_kirim VARCHAR(255) NULL,
    catatan TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
)");

// ── keuangan_ricemill ──
$pdo->exec("CREATE TABLE IF NOT EXISTS keuangan_ricemill (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    tipe VARCHAR(50) NOT NULL,
    keterangan VARCHAR(255) NOT NULL,
    jumlah DECIMAL(15,2) NOT NULL,
    kategori VARCHAR(100) NOT NULL DEFAULT 'lainnya',
    tanggal DATE NOT NULL,
    catatan TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
)");

// ── penerimaan_beras ──
$pdo->exec("CREATE TABLE IF NOT EXISTS penerimaan_beras (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    pengiriman_beras_id BIGINT NULL REFERENCES pengiriman_beras(id) ON DELETE SET NULL,
    asal_penggilingan VARCHAR(255) NOT NULL,
    jenis_beras VARCHAR(100) NOT NULL DEFAULT 'medium',
    jumlah_beras DECIMAL(10,2) NOT NULL,
    tanggal DATE NOT NULL,
    status VARCHAR(50) NOT NULL DEFAULT 'menunggu',
    bukti_foto VARCHAR(255) NULL,
    catatan TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
)");

// ── hasil_pengemasan ──
$pdo->exec("CREATE TABLE IF NOT EXISTS hasil_pengemasan (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    penerimaan_beras_id BIGINT NULL REFERENCES penerimaan_beras(id) ON DELETE SET NULL,
    tanggal DATE NOT NULL,
    jenis_beras VARCHAR(100) NOT NULL DEFAULT 'medium',
    jenis_kemasan VARCHAR(50) NOT NULL DEFAULT '5kg',
    jumlah_kemasan INTEGER NOT NULL,
    kualitas VARCHAR(50) NOT NULL DEFAULT 'layak_jual',
    catatan TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
)");

// ── pesanan ──
$pdo->exec("CREATE TABLE IF NOT EXISTS pesanan (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    nama_pelanggan VARCHAR(255) NOT NULL,
    tanggal DATE NOT NULL,
    jenis_produk VARCHAR(100) NOT NULL,
    jumlah INTEGER NOT NULL,
    harga_satuan DECIMAL(15,2) NULL,
    total_harga DECIMAL(15,2) NULL,
    status VARCHAR(50) NOT NULL DEFAULT 'menunggu',
    catatan TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
)");

// ── Record all migrations as done ──
$allMigrations = [
    '0001_01_01_000000_create_users_table',
    '0001_01_01_000001_create_cache_table',
    '0001_01_01_000002_create_jobs_table',
    '2026_05_05_085132_create_profil_lahans_table',
    '2026_05_05_085138_create_riwayat_panens_table',
    '2026_05_05_085146_create_setoran_penggilingan_table',
    '2026_05_06_085359_add_role_to_users_table',
    '2026_05_06_150001_create_penerimaan_gabah_table',
    '2026_05_06_150002_create_operasional_penggilingan_table',
    '2026_05_06_150003_create_riwayat_produksi_table',
    '2026_05_06_150004_create_pengiriman_beras_table',
    '2026_05_06_150005_create_keuangan_ricemill_table',
    '2026_05_06_150006_create_penerimaan_beras_table',
    '2026_05_06_150007_create_hasil_pengemasan_table',
    '2026_05_06_150008_create_pesanan_table',
    '2026_05_24_090000_add_jenis_beras_to_riwayat_produksi_table',
    '2026_05_25_070000_fix_all_enum_mismatches',
    '2026_05_26_015100_fix_keuangan_kategori_enum',
];

$stmt = $pdo->prepare("INSERT INTO migrations (migration, batch) VALUES (?, 1)");
foreach ($allMigrations as $m) {
    $stmt->execute([$m]);
}
