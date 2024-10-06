<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\transactiondetails;

class Transactions extends Model
{
    use HasFactory;
    protected $table = "transactions";
    protected $primaryKey = "transac_id";
    public $incrementing = true;
    protected $keyType = "int";
    protected $fillable = [
        "transac_id",
        "cust_id",
        "admin_id",
        "transac_date",
        "transac_status",
        "tracking_number",
        "pickup_datetime",
        "delivery_datetime"
    ];

    public function transactionDetails()
    {
        return $this->hasMany(transactiondetails::class, 'transac_id', 'transac_id');
    }
}
