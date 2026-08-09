<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proof extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function vendorProjectAssign()
    {
        return $this->belongsTo(
            VendorProjectAssign::class,
            '3ASProjectID',          // Foreign key on proofs table
            'three_as_project_id'    // Owner key on vendor_project_assigns table
        );
    }

    public static function projectPercentage(int $total, ?int $statusCount)
    {
        if ($total == 0) {
            return 0;
        }

        return round(($statusCount / $total) * 100, 2);
    }

}
