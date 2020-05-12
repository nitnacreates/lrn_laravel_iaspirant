<?php

namespace App\Model\Message;

use Illuminate\Database\Eloquent\Model;

use App\Model\Account;
use Illuminate\Support\Carbon;

class Message extends Model
{
    protected $fillable = ['id'];
    public $timestamps = false;

    /*
    * TODO: log values if failes
    */
    public function createNotification(array $msg_data)
    {
        $needed_keys = ['from_account_id', 'to_account_id', 'type', 'message', 'link'];

        $needed_exists = array_diff_key(array_flip($needed_keys), $msg_data);
        if(!count($needed_exists)){
            $user_account = Account::find($msg_data['from_account_id']);

            if(!empty($user_account)){
                $now = Carbon::now();
                $create_dt = $now->format('Y-m-d');

                $msg = new Message;
                $msg->from_account_id = $user_account->id;
                $msg->from_user_type = $user_account->user_type;
                $msg->to_account_id = $msg_data['to_account_id'];
                $msg->type = $msg_data['type'];
                $msg->message = $msg_data['message'];
                $msg->link = $msg_data['link'];
                $msg->created = $create_dt;
                $msg->seen = 0;
                $msg->save();

                return true;
            }
        }
        return false;
    }
}
