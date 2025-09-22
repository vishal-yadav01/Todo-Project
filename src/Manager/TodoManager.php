<?php
namespace App\Manager;
use App\Database\DB;
use App\Model\Todo;

class TodoManager {
    private  $db;
    public function __construct(){
        $this->db = DB::get();
    }

    public function add(Todo $t){
        $stmt = $this->db->prepare("INSERT INTO todos(user_id,title,is_done) VALUES(?,?,0)");
        $stmt->bind_param("is",$t->user_id,$t->title);
        return $stmt->execute();
    }

    public function update(Todo $t){
        $stmt = $this->db->prepare("UPDATE todos SET title=? WHERE id=? AND user_id=?");
        $stmt->bind_param("sii",$t->title,$t->id,$t->user_id);
        return $stmt->execute();
    }

    public function delete(int $id,int $uid){
        $stmt = $this->db->prepare("DELETE FROM todos WHERE id=? AND user_id=?");
        $stmt->bind_param("ii",$id,$uid);
        return $stmt->execute();
    }

    public function markDone(int $id,int $uid){
        $stmt = $this->db->prepare("UPDATE todos SET is_done=1 WHERE id=? AND user_id=?");
        $stmt->bind_param("ii",$id,$uid);
        return $stmt->execute();
    }

    public function all(int $uid){
        $stmt = $this->db->prepare("SELECT * FROM todos WHERE user_id=? ORDER BY id DESC");
        $stmt->bind_param("i",$uid);
        $stmt->execute();
        $res = $stmt->get_result();
        $out=[];
        while($row=$res->fetch_assoc()){
            $out[] = new Todo($row);
        }
        return $out;
    }

    public function get(int $id,int $uid): ?Todo {
        $stmt = $this->db->prepare("SELECT * FROM todos WHERE id=? AND user_id=?");
        $stmt->bind_param("ii",$id,$uid);
        $stmt->execute();
        $res=$stmt->get_result();
        $row=$res->fetch_assoc();
        return $row? new Todo($row):null;
    }

    
}
