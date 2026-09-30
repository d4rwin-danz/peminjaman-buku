<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Library Settings
    |--------------------------------------------------------------------------
    */

    // Maksimal jumlah buku yang dapat dipinjam dalam satu transaksi
    'max_books_per_loan' => 3,

    // Lama peminjaman dalam hari
    'loan_duration_days' => 7,

    // Denda keterlambatan per buku per hari
    'fine_per_book_per_day' => 1000,

];