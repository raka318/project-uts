<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dompet extends Model
{
    use HasFactory;

    protected $table = 'dompets';
    protected $primaryKey = 'id_dompet';

    protected $fillable = [
        'id_user',
        'nama_dompet',
        'jenis',
        'saldo',
        'mata_uang',
    ];

    protected $casts = [
        'saldo' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'id_dompet');
    }
}