<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use DB;

class DeleteUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete users after specific number of days.';

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
     * @return mixed
     */
    public function handle()
    {
        $users = User::whereNotNull('deleted_at')->get();

        if(!$users->isEmpty())
        {
            foreach ($users as $user)
            {
                $user_deleted_date = Carbon::createFromTimeStamp(strtotime($user->deleted_at), "UTC")->addDays(settingValue('user_deletion_days'));
                $current_date = Carbon::now('UTC');

                if($current_date->gt($user_deleted_date))
                {    
                    // $user->delete();
                    $user->update([
                        'status' => 0,
                        'is_approved' => 0,
                    ]); 

                }
            }
        }
    }
}