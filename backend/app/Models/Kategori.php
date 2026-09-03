<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    protected $table = 'kategoris';

    protected $primaryKey = 'id_kategori';

    protected $fillable = [
        'id_user',
        'nama_kategori',
        'jenis',
        'ikon',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'id_kategori');
    }

    public function anggaran()
    {
        return $this->hasMany(Anggaran::class, 'id_kategori');
    }
}