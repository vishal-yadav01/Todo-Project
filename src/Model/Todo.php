<?php
namespace App\Model;

class Todo {
    public  $id;
    public  $user_id;
    public  $title;
    public  $is_done;

    public function __construct(array $data=[]){
        $this->id = $data['id'] ?? null;
        $this->user_id = $data['user_id'] ?? 0;
        $this->title = $data['title'] ?? '';
        $this->is_done = $data['is_done'] ?? 0;
    }
}


// jis data pr ham kaam kar rhe hia usko ek strcutre format me  dall ne ke liye banaya hai 