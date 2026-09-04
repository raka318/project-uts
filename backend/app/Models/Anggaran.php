<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anggaran extends Model
{
    use HasFactory;

    protected $table = 'anggaran';

    protected $primaryKey = 'id_anggaran';

    protected $fillable = [
        'id_user',
        'id_kategori',
        'nama_anggaran',
        'jumlah',
        'tanggal_mulai',
        'tanggal_selesai',
    ];

    protected $casts = [
        'jumlah' => 'decimal:2',
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori');
    }
}