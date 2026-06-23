<?php

namespace App\Models;

use App\Models\User;
use App\Models\DetailTransaksi;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksi';

    public const UPDATED_AT = null;

    /**
     * Mass assignable attributes
     */
    protected $fillable = [
    'nomor_invoice',
    'user_id',
    'sumber',
    'status',
    'metode_bayar',   
    'kode_unik',      
    'total_harga',
    'bayar',
    'kembalian',
    'tanggal_transaksi',
    'created_at',
    ];

    /**
     * Type casting
     */
    protected $casts = [
        'total_harga' => 'decimal:2',
        'bayar' => 'decimal:2',
        'kembalian' => 'decimal:2',
        'tanggal_transaksi' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Transaksi dibuat oleh User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Transaksi memiliki banyak detail item
     */
    public function detailTransaksi()
    {
        return $this->hasMany(DetailTransaksi::class, 'transaksi_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Helper (optional tapi sangat berguna)
    |--------------------------------------------------------------------------
    */

    /**
     * Total item dalam transaksi
     */
    public function getTotalItemAttribute()
    {
        return $this->detailTransaksi()->sum('qty');
    }
}