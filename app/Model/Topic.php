<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    public $timestamps = false;
    protected $fillable = ['title','about','category'];

    protected function fetchSubjectStructure($topic_arr)
    {
        $structured_arr = array();

        $last_level = 0;
        foreach($topic_arr as $topic){

            if($last_level < $topic['level']){
                $last_level = $topic['level'];
            }

            $structured_arr[$topic['level']][$topic['id']] = $topic;
        }

        for($i = $last_level; $i > 0; $i--){
            foreach ($structured_arr[$i] as $curr_level) {
                if(array_key_exists($curr_level['parent'], $structured_arr[$i-1]) ){
                    $structured_arr[$i-1][$curr_level['parent']]['children'][] = $curr_level;
                }
            }
        }
        
        return empty($structured_arr) ? $structured_arr : $structured_arr[0];

    }

    public function structuredSubject($sub)
    {
        $topic_arr = self::where('sub','=',$sub)->orderBy('level','ASC')->orderBy('id','ASC')->get()->toArray();
        // return $topic_arr;
        return $this->fetchSubjectStructure($topic_arr);
    }
}
