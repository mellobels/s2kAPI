<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Transactions;
use App\Models\laundrycategory;

class transactiondetails extends Model
{
    use HasFactory;
    protected $table = "transaction_details";
    protected $primaryKey = "Transacdet_id";
    public $incrementing = true;
    protected $keyType = "int";
    protected $fillable = [
        "Categ_ID",
        "Tracking_number",
        "Qty",
        "Weight",
        "Price"
    ];

    public function transaction()
    {
        return $this->belongsTo(Transactions::class, 'Tracking_number', 'Tracking_number');
        return $this->belongsTo(laundrycategory::class, 'Categ_ID', 'Categ_ID');
    }

    // public function laundryCategory()
    // {
    //     return $this->belongsTo(laundrycategory::class, 'categ_id', 'categ_id');
    // }
}
