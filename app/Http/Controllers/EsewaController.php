<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Esewa;
use Carbon\Carbon;

class EsewaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $pid = $request->product;
        $price = $request->price;
        //Insert Data in Database
        $order = new Esewa;
        $order->user_id = $request->user_id;
        $order->name = $request->name;
        $order->email = $request->email;
        $order->phone = $request->phone;
        $order->product = $pid;
        $order->price = $request->price;
        $order->status = 'unverified';
        $order->save();
        $url = "https://uat.esewa.com.np/epay/main";
        $data =[
            'amt'=> $request->price,
            'pdc'=> 0,
            'psc'=> 0,
            'txAmt'=> 0,
            'tAmt'=> $request->price,
            'pid'=>$pid,
            'scd'=> 'EPAYTEST',
            'su'=>url('/success'),
            'fu'=>url('/failure')
        ];
    }
    
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }
    
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // $curl = curl_init($url);
        // curl_setopt($curl, CURLOPT_POST, true);
        // curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        // curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        // $response = curl_exec($curl);
        // curl_close($curl);
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function success()
    {
        $pid=$_GET['oid'];
        $refId=$_GET['refId'];
        $amt=$_GET['amt'];

        $order = Esewa::where('product', $pid)->first();
        if ($order) {
            $order->status = 'verified';
            $order->updated_at = Carbon::now();
            $order->update();
            return 'Payment Success';
        } else {
            return 'Order not found';
        }
    }

    public function failure()
    {
        $pid = $_GET['pid'];
        $order = Esewa::where('product', $pid)->first();
        if ($order) {
            $order->status = 'failed';
            $order->updated_at = Carbon::now();
            $order->update();
            return 'Payment Fail';
        } else {
            return 'Order not found';
        }
    }
}
