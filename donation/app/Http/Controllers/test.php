<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use App\Models\Stkrequest;

class PaymentController extends Controller
{

    public function index(){
        return view('donate.donate');
    }

    
    public function token(){

        $consumerKey='AfpekwX1GS7AFIpwA7HOncXf4lCUW818';

        $consumerSecret='A3vtjnKKAvVgMmJJ';
        $url='https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';

        $response=Http::withBasicAuth($consumerKey,$consumerSecret)->get($url);
        return $response['access_token'];
    }

    public function initiateStkPush()
    {
        $accessToken = $this->token();
    
        $url = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';
        $PassKey = 'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919';
    
        $BusinessShortCode = 174379;
    
        $Timestamp = Carbon::now()->format('YmdHis');
        $password = base64_encode($BusinessShortCode . $PassKey . $Timestamp);
        $TransactionType = 'CustomerPayBillOnline';
        $Amount = 1;
    
        $PartyA = 2543051520;
        $PartyB = 174379;
        $PhoneNumber = 254743051520;
        $CallbackUrl = 'https://bc15-41-90-70-24.ngrok-free.app/payment/stkPushCallback';
        $AccountReference = 'Donation for goods';
        $TransactionDesc = 'payment for goods';
    
        try {
            $response = Http::withToken($accessToken)->post($url, [
                'BusinessShortCode' => $BusinessShortCode,
                'Password' => $password,
                'Timestamp' => $Timestamp,
                'TransactionType' => $TransactionType,
                'Amount' => $Amount,
                'PartyA' => $PartyA,
                'PartyB' => $PartyB,
                'PhoneNumber' => $PhoneNumber,
                'CallBackURL' => $CallbackUrl,
                'AccountReference' => $AccountReference,
                'TransactionDesc' => $TransactionDesc
            ]);
        } catch (Throwable $e) {
            return $e->getMessage();
        }
    
        $res = json_decode($response->body(), true);
    
        if (isset($res['ResponseCode']) && $res['ResponseCode'] == 0) {
            $MerchantRequestID = $res['MerchantRequestID'];
            $CheckoutRequestID = $res['CheckoutRequestID'];
            $CustomerMessage = $res['CustomerMessage'];
    
            // Save to database
            $payment = new Stkrequest;
            $payment->phone = $PhoneNumber;
            $payment->amount = $Amount;
            $payment->reference = $AccountReference;
            $payment->description = $TransactionDesc;
            $payment->MerchantRequestID = $MerchantRequestID;
            $payment->CheckoutRequestID = $CheckoutRequestID;
            $payment->status = 'Requested';
            $payment->save();
    
            return $CustomerMessage;
        }
    }
    



// public function stkCallback( )
// {
//     $data = file_get_contents('php://input');
//     Storage::disk('local')->put('stk.txt', $data);

//     $response = json_decode($data);

//     if (isset($response->Body->stkCallback->ResultCode) && $response->Body->stkCallback->ResultCode == 0) {
//         $MerchantRequestID = $response->Body->stkCallback->MerchantRequestID;
//         $CheckoutRequestID = $response->Body->stkCallback->CheckoutRequestID;
//         $ResultDesc = $response->Body->stkCallback->ResultDesc;
//         $CallbackMetadata = $response->Body->stkCallback->CallbackMetadata;

//         $Amount = null;
//         $MpesaReceiptNumber = null;
//         $TransactionDate = null;

//         foreach ($CallbackMetadata->Item as $item) {
//             if ($item->Name == 'Amount') {
//                 $Amount = $item->Value;
//             } elseif ($item->Name == 'MpesaReceiptNumber') {
//                 $MpesaReceiptNumber = $item->Value;
//             } elseif ($item->Name == 'TransactionDate') {
//                 $TransactionDate = $item->Value;
//             }
//         }

//         // Debug statements
//         echo "Amount: $Amount<br>";
//         echo "MpesaReceiptNumber: $MpesaReceiptNumber<br>";
//         echo "TransactionDate: $TransactionDate<br>";

//         $payment = Stkrequest::where('CheckoutRequestID', $CheckoutRequestID)->first();

//         if ($payment) {
//             $payment->status = 'Paid';
//             $payment->TransactionDate = $TransactionDate;
//             $payment->MpesaReceiptNumber = $MpesaReceiptNumber;
//             $payment->ResultDesc = $ResultDesc;
//             $payment->save();
//             echo "Payment record updated successfully.";
//         } else {
//             echo "Payment record not found.";
//         }
//     } else {
//         $CheckoutRequestID = $response->Body->stkCallback->CheckoutRequestID;
//         $ResultDesc = $response->Body->stkCallback->ResultDesc;
//         $payment = Stkrequest::where('CheckoutRequestID', $CheckoutRequestID)->first();

//         if ($payment) {
//             $payment->ResultDesc = $ResultDesc;
//             $payment->status = 'Failed';
//             $payment->save();
//             echo "Payment record updated successfully.";
//         } else {
//             echo "Payment record not found.";
//         }
//     }
// }

public function stkCallback(){
    $data=file_get_contents('php://input');
    Storage::disk('local')->put('stk.txt',$data);

    $response=json_decode($data);

    $ResultCode=$response->Body->stkCallback->ResultCode;

    if($ResultCode==0){
        $MerchantRequestID=$response->Body->stkCallback->MerchantRequestID;
        $CheckoutRequestID=$response->Body->stkCallback->CheckoutRequestID;
        $ResultDesc=$response->Body->stkCallback->ResultDesc;
        $Amount=$response->Body->stkCallback->CallbackMetadata->Item[0]->Value;
        $MpesaReceiptNumber=$response->Body->stkCallback->CallbackMetadata->Item[1]->Value;
        //$Balance=$response->Body->stkCallback->CallbackMetadata->Item[2]->Value;
        $TransactionDate=$response->Body->stkCallback->CallbackMetadata->Item[3]->Value;
        $PhoneNumber=$response->Body->stkCallback->CallbackMetadata->Item[3]->Value;


        $payment=Stkrequest::where('CheckoutRequestID',$CheckoutRequestID)->firstOrFail();
        $payment->status='Paid';
        $payment->TransactionDate=$TransactionDate;
        $payment->MpesaReceiptNumber=$MpesaReceiptNumber;
        $payment->ResultDesc=$ResultDesc;
        $payment->save();

    }else{

    $CheckoutRequestID=$response->Body->stkCallback->CheckoutRequestID;
    $ResultDesc=$response->Body->stkCallback->ResultDesc;
    $payment=Stkrequest::where('CheckoutRequestID',$CheckoutRequestID)->firstOrFail();


    
    $payment->ResultDesc=$ResultDesc;
    $payment->status='Failed';
    $payment->save();

    }

}

}

   