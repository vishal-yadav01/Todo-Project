<?php
namespace App\Model;

class Todo {
    public ?int $id;
    public int $user_id;
    public string $title;
    public int $is_done;

    public function __construct(array $data=[]){
        $this->id = $data['id'] ?? null;
        $this->user_id = $data['user_id'] ?? 0;
        $this->title = $data['title'] ?? '';
        $this->is_done = $data['is_done'] ?? 0;
    }
}
