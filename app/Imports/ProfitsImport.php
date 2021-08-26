<?php
namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\PoolInvestment;
use App\Models\Referral;
use App\Models\Balance; 
use App\Models\Transaction;

class ProfitsImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        $arr = [];
        foreach ($rows as $key => $row)
        {
            if($key > 0)
            {   
                $investment = PoolInvestment::find($row[3]);
                
                if(!empty($investment))
                {
                    $user = $investment->user;
                    $profit = $investment->deposit_amount * $row[5] / 100; 
                    $management_fee = $profit * $investment->management_fee_percentage / 100;
                    $actual_profit = $profit - $management_fee; 
                    
                    if(!empty($user->referral_code))
                    {
                        $referral_account = $user->referrerAccount;
                        $referral_balance = $referral_account->account_balance;
                        
                        $referral_account = $user->referrer_account_id;
                        
                        //referral account balance greater than 0.01

                         if($referral_account > 0.01)
                         {
                            $commission = $investment->management_fee_percentage *10 /100;

                            // ******************************************   //
                            // User Account balance Update in referral case //
                            // ******************************************  //
 
                            $referral_account->update([
                                'account_balance' => $referral_account->account_balance + $commission,
                                'commission_total' =>  $referral_account->commission_total + $commission,
                            ]);
                           
                            // ******************************************   //
                            // Commission Update in referral case table     //
                            // ******************************************  //

                            $referral->update([
                                'commission' =>  $referral->commission + $commission,
                            ]);

                            // ******************************************   //
                            // Balance Table  entry in referral case         //
                            // ******************************************  //

                             $balance_response = Balance::create([
                                'user_id' => $referral_account->id,
                                'type' => 'Refferal commission',
                                'amount' => $commission,
                            ]);
                            dd($balance_response);
                            // ***********************************************   //
                            // Transaction Table new  entry in referral case     //
                            // **********************************************  //
                             $transaction_message =   "Referral commission earned from " . $user->name . ' (' . $user->email. ')';

                             Transaction::create([
                                'user_id' => $user->id,
                                'type' => 'Refferal commission',
                                'amount' => $commission,
                                'actual_amount' => $commission,
                                'description' => $transaction_message,
                            ]);
                         }
                         //Balance Condition are close
                     }
                    //referral condition are close


                     // ******************************************   //
                    //          Pool Investment Table Update        //
                    // ******************************************  //

                    $investment->update([
                       'user_id  ' =>  $user->id,
                       'profit ' =>  $investment->profit + $profit,
                       'management_fee' =>  $investment->management_fee + $management_fee,
                       'commission' =>  $investment->commission + $commission,
                    ]); 

                    // ******************************************   //
                    //          User Table balance Update          //
                    // ******************************************  //
               
                    $user->update([
                       'account_balance ' => $user->account_balance + 
                        $actual_profit +
                        $investment->deposit_amoun,
                    ]);   
                    // ******************************************   //
                    //           Balance Table  entry in           //
                    // ******************************************  //

                     Balance::create([
                        'user_id' => $user->id,
                        'type' => 'Profit',
                        'amount' => $profit + $investment->deposit_amoun,
                    ]);

                    // ***********************************************   //
                    //       Transaction Table new  entry in             //
                    // **********************************************  //
                     $transaction_message = "Profit from this Pool by this Month on this investment" ;

                     Transaction::create([
                        'user_id' => $user->id,
                        'type' => 'Profit',
                        'amount' => $profit,
                        'actual_amount' => $profit + $management_fee,
                        'description' => $transaction_message,
                    ]);

                    dd($actual_profit);
                }   
            }
        }
    }
}