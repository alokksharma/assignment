<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Partner extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $hidden = ['supplier_token']; // never expose in JSON responses

    protected $casts = [
        'expired_at' => 'date',
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($supplier) {
            if (empty($supplier->supplier_id)) {
                $supplier->supplier_id = 'SUP-' . strtoupper(Str::random(8));
            }

            if (empty($supplier->supplier_token)) {
                $supplier->supplier_token = Str::random(40);
            }
        });
    }

    public function proofs()
    {
        return $this->hasMany(Proof::class, 'supplier_id');
    }

}
