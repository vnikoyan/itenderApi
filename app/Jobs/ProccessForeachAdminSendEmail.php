<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class ProccessForeachAdminSendEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $data;
    protected $users;

    public function __construct($data, $users)
    {
        if(count($users) && $users[0] !== ""){
            $this->data = $data;
            $this->users = $users;
        } else {
            $this->users = [];
        }
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if(count($this->users)){
            foreach ($this->users as $user) {
                $this->data->email = $user->email;
                ProcessAdminSendEmail::dispatch($this->data);
            }
        }
    }
}
