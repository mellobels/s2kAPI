<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Transactions;
use App\Models\laundrycategory;
use App\Models\transactiondetails;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function register(Request $request){
        $formField = $request -> validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
        ]);

        User::create($formField);

        return 'Registered';    
    }
    public function login(Request $request){
        $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required'
        ]);
        $user = User::where('email',$request->email)->first();
        if(!$user || !Hash::check($request->password, $user->password)){
            return [
                'message' => 'The provided credentials are incorrect'
            ];
        }
        $token = $user-> createToken($user->name);
        return [
            'user' => $user,
            'token' => $token->plainTextToken
        ];
        // return $user;
    }
    public function logout(Request $request){
        // auth()->logout();
        // Auth::guard('web')->logout();
        $request->user()->tokens()->delete();
        return [
            'message' => 'You are logged out'
        ];
    }


    public function cancelTrans(Request $request, $id){
        $transactions = DB::table('transactions')
            ->join('customers', 'transactions.Cust_ID', '=', 'customers.Cust_ID')
            ->join('transaction_details', 'transactions.Tracking_number', '=', 'transaction_details.Tracking_number')
            ->join('laundry_categorys', 'transaction_details.Categ_ID', '=', 'laundry_categorys.Categ_ID')
            ->select(
                'transactions.Tracking_number',
                'transactions.Transac_date',
                'transactions.Transac_status',
                'transactions.Pickup_datetime',
                'transactions.Delivery_datetime',
                'customers.Cust_fname', 
                'customers.Cust_lname', 
                DB::raw('GROUP_CONCAT(laundry_categorys.Category SEPARATOR ", ") as Category'),
                DB::raw('SUM(transaction_details.Price) as totalprice'),
                DB::raw('SUM(transaction_details.Qty) as totalQty'),
                DB::raw('SUM(transaction_details.Weight) as totalWeight')
            )
            ->groupBy(
                'transactions.Tracking_number',
                'transactions.Transac_date',
                'transactions.Transac_status',
                'transactions.Pickup_datetime',
                'transactions.Delivery_datetime',
                'customers.Cust_fname', 
                'customers.Cust_lname', 
            )
            ->get();

            Transactions::where('Tracking_number', $id)
                ->update(['Transac_status' => 'cancel']);

        return response()->json(['transaction' => $transactions], 200);
    }


    // home
    public function display() {
        $transactions = DB::table('transactions')
            ->join('customers', 'transactions.Cust_ID', '=', 'customers.Cust_ID')
            ->join('transaction_details', 'transactions.Tracking_number', '=', 'transaction_details.Tracking_number')
            // ->join('admins', 'admins.Admin_ID', '=', 'transactions.Admin_ID')
            ->join('laundry_categorys', 'transaction_details.Categ_ID', '=', 'laundry_categorys.Categ_ID')
            ->select(
                'transactions.Tracking_number',
                'transactions.Transac_date',
                'transactions.Transac_status',
                'transactions.Pickup_datetime',
                'transactions.Delivery_datetime',
                // 'transactions.Staffincharge',
                'customers.Cust_fname', 
                'customers.Cust_lname', 
                // 'admins.Admin_fname',
                // 'admins.Admin_mname',
                // 'admins.Admin_lname',
                DB::raw('GROUP_CONCAT(laundry_categorys.Category SEPARATOR ", ") as Category'),
                DB::raw('SUM(transaction_details.Price) as totalprice'),
                DB::raw('SUM(transaction_details.Qty) as totalQty'),
                DB::raw('SUM(transaction_details.Weight) as totalWeight')
            )
            ->groupBy(
                'transactions.Tracking_number',
                'transactions.Transac_date',
                'transactions.Transac_status',
                'transactions.Pickup_datetime',
                'transactions.Delivery_datetime',
                // 'transactions.Staffincharge',
                'customers.Cust_fname', 
                'customers.Cust_lname', 
                // 'admins.Admin_fname',
                // 'admins.Admin_mname',
                // 'admins.Admin_lname'
            )
            ->get();

            // Transactions::where('Tracking_number', $id)
            //     ->update(['Transac_status' => 'recieve']);

        return response()->json(['transaction' => $transactions], 200);
    }

    public function laundycateg(){
        return response()->json(laundrycategory::orderBy('Categ_ID','desc')->get(), 200);
    }

    public function addtrans(Request $request) {
        // Validate the incoming request data
        $request->validate([
            'Tracking_number' => 'required|string|max:20',
            'Qty' => 'required|array',
            'Qty.*' => 'required|integer',
            'Weight' => 'nullable|numeric',
            'Categ_ID' => 'required|array',
            'Categ_ID.*' => 'nullable|string|max:255',
            'Cust_ID' => 'required|string|max:255',
            'Transac_status.*' => 'nullable|string|max:255',
            // Note: Removed Transac_date validation as it's filled by now()
        ]);
    
        foreach ($request->Qty as $index => $qty) {
            $data = [
                'Tracking_number' => $request->Tracking_number,
                'Qty' => $qty,
                'Weight' => $request->Weight,
                'Categ_ID' => $request->Categ_ID[$index],
            ];
    
            TransactionDetails::create($data);
        }
    
        // Create the transaction with Cust_ID and the current date
        Transactions::create(array_merge(
            $request->only(['Tracking_number', 'Cust_ID', 'Transac_status']),
            ['Transac_date' => now()]  // Add the current date
        ));
    
        return response()->json([
            'message' => 'Transaction details added successfully',
            "date" => $data,
            "form" => $request
        ], 201);
    }
}
