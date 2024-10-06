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
            'Email' => 'required|email|exists:users',
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



    // home
    public function display(){
        $transactions = DB::table('transactions')
        ->join('transaction_details', 'transactions.Transac_id', '=', 'transaction_details.Transac_id')
        ->join('laundry_categorys', 'transaction_details.Categ_id', '=', 'laundry_categorys.Categ_id')
        ->select('transactions.*', 'transaction_details.*', 'laundry_categorys.*')
        ->get();

        return response()->json(['transaction' => $transactions], 200);
    }

    public function laundycateg(){
        return response()->json(laundrycategory::orderBy('Categ_id','desc')->get(), 200);
    }
}
