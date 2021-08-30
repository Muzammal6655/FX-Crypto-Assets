<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\EmailTemplate;
use Carbon\Carbon;
use App\Models\User;

class DisableUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'disable:users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Your account will be closed if account balance is less than 0.01 for more than 3 months.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $last_3_months= Carbon::now()->subDays(90)->timestamp;
        $users = User::where('account_balance_timestamp','<=', $last_3_months)->where('status','=', 1)->get();
        if(!$users->isEmpty())
        {    
            foreach ($users as $user)
            {  
                if($user->account_balance < 0.01 && $user->status == 1)
                {
                    $user->update([
                        'status' => 0,
                        'account_balance_timestamp' => null,
                    ]); 

                    /**
                    * Send Email to User
                    */
                    $name = $user->name;
                    $email = $user->email;
                    
                    $email_template = EmailTemplate::where('type','account_disabled')->first();

                    $subject = $email_template->subject;
                    $content = $email_template->content;

                    $search = array("{{name}}","{{email}}","{{app_name}}");
                    
                    $replace = array($name,$email,env('APP_NAME'));
                    $content  = str_replace($search,$replace,$content);

                    sendEmail($email, $subject, $content);
                }
            }
        }         
    }
}
