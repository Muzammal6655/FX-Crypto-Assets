<?php
namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\PoolInvestment;
use App\Models\Referral;
use App\Models\Balance; 
use App\Models\Transaction;
use Carbon\Carbon;

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
                    $profit = $investment->deposit_amount * ($row[5] / 100); 
                    $management_fee = $profit * ($investment->management_fee_percentage / 100);
                    $actual_profit = $profit - $management_fee;
                    $commission = 0;
                    
                    if(!empty($user->referral_code) && !empty($user->referrer_account_id))
                    {
                        $referral_account = $user->referrerAccount;
                        $referral_balance = $referral_account->account_balance;

                        $referral = Referral::where(['referrer_id' => $user->referrer_account_id, 'refer_member_id' => $user->id])->first();
                        
                        //referral account balance greater than 0.01

                        if($referral_balance > 0.01 && $referral_account->status == 1)
                        {
                            $commission = $investment->management_fee_percentage * (10 /100);

                            /**
                             * User Account balance Update in referral case
                             */
 
                            $referral_account->update([
                                'account_balance' => $referral_account->account_balance + $commission,
                                'commission_total' =>  $referral_account->commission_total + $commission,
                                'account_balance_timestamp' =>Carbon::now('UTC')->timestamp,
                            ]);

                            /**
                             * Commission Update in referrals table
                             */

                            $referral->update([
                                'commission' =>  $referral->commission + $commission,
                            ]);

                            /**
                             * Balances table entry in referral case
                             */

                            Balance::create([
                                'user_id' => $referral_account->id,
                                'type' => 'commission',
                                'amount' => $commission,
                            ]);

                            /**
                             * Transactions table entry in referral case
                             */

                            $transaction_message =   "Referral commission earned from " . $user->name . ' (' . $user->email. ')';

                            Transaction::create([
                                'user_id' => $referral_account->id,
                                'type' => 'commission',
                                'amount' => $commission,
                                'actual_amount' => $commission,
                                'description' => $transaction_message
                            ]);
                        }
                    }

                    /**
                     * Pool Investment table update
                     */
              
                    $investment->update([
                       'user_id' =>  $user->id,
                       'profit' =>  $actual_profit,
                       'management_fee' => $management_fee - $commission,
                       'commission' => $commission,
                    ]); 

                    /**
                     * User table balance Update
                     */
                    
                    $user->update([
                       'account_balance' => $user->account_balance + $actual_profit + $investment->deposit_amount,
                       'profit_total' => $user->profit_total + $actual_profit,
                       'account_balance_timestamp' =>Carbon::now('UTC')->timestamp,
                    ]);
                    
                    /**
                     * Balance table entry
                     */

                    Balance::create([
                        'user_id' => $user->id,
                        'type' => 'profit',
                        'amount' => $actual_profit + $investment->deposit_amount,
                    ]);

                    /**
                     * Transaction table entry
                     */

                    $transaction_message =  "Profit earned from " . $investment->pool->name . ' Pool';
                   
                    Transaction::create([
                        'user_id' => $user->id,
                        'type' => 'profit',
                        'amount' => $profit,
                        'actual_amount' => $actual_profit,
                        'description' => $transaction_message,
                        'fee_percentage' => $investment->management_fee_percentage,
                        'fee_amount' => $management_fee - $commission,
                        'commission' => $commission,
                    ]);
                }   
            }
        }
    }
}