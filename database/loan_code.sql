-- Jalankan di phpMyAdmin (InfinityFree) jika tidak bisa menjalankan "php artisan migrate".
-- Pilih database e_library lalu tab SQL, jalankan SEMUA baris di bawah ini sekali saja.

ALTER TABLE `loans`
    ADD COLUMN `loan_code` VARCHAR(30) NULL AFTER `id`,
    ADD UNIQUE KEY `loans_loan_code_unique` (`loan_code`);

-- Beri kode untuk peminjaman lama. Format: PJM-YYYYMMDD-XXXXXX
UPDATE `loans`
SET `loan_code` = CONCAT(
    'PJM-',
    DATE_FORMAT(`loan_date`, '%Y%m%d'),
    '-',
    UPPER(SUBSTRING(MD5(CONCAT(`id`, RAND())), 1, 6))
)
WHERE `loan_code` IS NULL;

-- (Opsional) Catat migration agar "php artisan migrate" tidak menjalankannya lagi:
-- INSERT INTO `migrations` (`migration`, `batch`)
-- VALUES ('2026_11_02_000001_add_loan_code_to_loans_table', (SELECT IFNULL(MAX(b.`batch`),0)+1 FROM (SELECT `batch` FROM `migrations`) b));
