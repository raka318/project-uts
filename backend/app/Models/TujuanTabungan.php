<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TujuanTabungan extends Model
{
    use HasFactory;

    protected $table = 'tujuan_tabungan';

    protected $primaryKey = 'id_tujuan';

    protected $fillable = [
        'id_user',
        'nama_tujuan',
        'target_jumlah',
        'jumlah_terkumpul',
        'deadline',
        'deskripsi',
    ];

    protected $casts = [
        'target_jumlah' => 'decimal:2',
        'jumlah_terkumpul' => 'decimal:2',
        'deadline' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}