<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\transactiondetails;

class Transactions extends Model
{
    use HasFactory;
    protected $table = "transactions";
    protected $primaryKey = "Tracking_number";
    public $incrementing = true;
    protected $keyType = "int";
    protected $fillable = [
        "Tracking_number",
        "Cust_ID",
        "Admin_ID",
        "Transac_date",
        "Transac_status",
        "Pickup_datetime",
        "Delivery_datetime"
    ];

    public function transactionDetails()
    {
        return $this->hasMany(transactiondetails::class, 'Tracking_number', 'Tracking_number');
    }
}
