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
        "categ_id",
        "transac_id",
        "qty",
        "weight",
        "price"
    ];

    public function transaction()
    {
        return $this->belongsTo(Transactions::class, 'transac_id', 'transac_id');
        return $this->belongsTo(laundrycategory::class, 'categ_id', 'categ_id');
    }

    // public function laundryCategory()
    // {
    //     return $this->belongsTo(laundrycategory::class, 'categ_id', 'categ_id');
    // }
}
