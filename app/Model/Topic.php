<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    public $timestamps = false;
    protected $fillable = ['title','about','category'];

    protected function fetchSubjectStructure($topic_arr, $only_keys)
    {
        static $remove_keys = array();
        $structured_arr = array();

        foreach ($topic_arr as $id => $tarr) {
            if(in_array($id, $remove_keys) || !in_array($id, $only_keys)){
                continue;
            }

            $structured_arr[$id] = $tarr;

            if(!empty($tarr['children'])){
                $structured_arr[$id]['tp_child'] = $this->fetchSubjectStructure($topic_arr, $tarr['children']);
                $remove_keys = array_merge($remove_keys, $tarr['children']);
            }
        }

        return $structured_arr;
    }

    public function structuredSubject($sub)
    {
        $topic_arr = $this->linearSubjects($sub);
        return $this->fetchSubjectStructure($topic_arr, array_keys($topic_arr));
    }

    public function linearSubjects($sub)
    {
        $topics = Topic::where('sub','=',$sub)->orderBy('level','ASC')->orderBy('title','ASC')->get();
        $syll_arr = [];
        foreach ($topics as $topic) {
            $syll_arr[$topic->id] = [
                'title' => $topic->title,
                'about' => $topic->about,
                'category' => $topic->category,
                'level' => $topic->level,
                'parent' => [],
                'children' => []
            ];

            if($topic->parent){
                $syll_arr[$topic->id]['parent'] = array_merge($syll_arr[$topic->parent]['parent'], [$topic->parent]);
                array_push($syll_arr[$topic->parent]['children'], $topic->id);
            }
        }

        return $syll_arr;
    }
}
