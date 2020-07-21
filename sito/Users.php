<?php
require_once './vendor/autoload.php';
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
class Users {
   protected $database;
   protected $dbname = 'users';
   public function __construct(){
       $acc = ServiceAccount::fromJsonFile(__DIR__ . '/secret/prova-c70b0-cac33e45f601.json');
       $firebase = (new Factory)->withServiceAccount($acc)->create();
       $this->database = $firebase->getDatabase();
   }
   public function get($userID = NULL){
       if (empty($userID) || !isset($userID)) { return FALSE; }
       return $this->database->getReference()->getChild($this->dbname)->getChild($userID)->getValue();

   }
   public function insert(array $data) {
       if (empty($data) || !isset($data)) { return FALSE; }
       foreach ($data as $key => $value){
           $this->database->getReference()->getChild($this->dbname)->getChild($key)->set($value);
       }
       return TRUE;
   }
   public function delete(int $userID) {
       if (empty($userID) || !isset($userID)) { return FALSE; }
       if ($this->database->getReference($this->dbname)->getChild($userID)->remove()){

           return TRUE;
       } else {
           return FALSE;
       }
   }
}
$users=new Users();
var_dump($users->get(7));