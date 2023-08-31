<?php
namespace App\Model;

class DiscussionManager{
	/** @var \Nette\Database\Context */
	protected $database;

	public function __construct(\Nette\Database\Context $database){
        	$this->database = $database;
  	}

      public function getDisscussionItems(){
		$database = $this->database;
		$database->beginTransaction();		
		$rows = $database->query('SELECT id, prezdivka, email, data, pozitivni, negativni FROM prispevky')->fetchAll();
		$database->commit();
		//Debugger::barDump($rows);  je zajímavé zkusit odkomentovat
		return $rows; //Obvykle se vrací DTO - Data Transfer Object(y)
    }

    public function saveDiscussionItems($nick, $email, $data){
        $database = $this->database;
            $database->beginTransaction();
        try{		
              $database->query('INSERT INTO prispevky (prezdivka, email, data) VALUES (?, ?, ?)', $nick, $email, $data);
          $database->commit();
        } catch (\Exception $e){
          $database->rollback();
          throw $e;
        }
         
    }
        public function getDisscussionItem($id){
            $database = $this->database;
            $database->beginTransaction();		
            $row = $database->fetch('SELECT id, prezdivka, email, data FROM prispevky WHERE id = ?', $id);
            $database->commit();
            //Debugger::barDump($rows);  je zajímavé zkusit odkomentovat
            return $row; //Obvykle se vrací DTO - Data Transfer Object(y)
        }

        public function addPositive($id){
            $database = $this->database;
            $database->beginTransaction();
            $database->query('UPDATE prispevky SET pozitivni = pozitivni + 1 WHERE id = ?', $id);
            $database->commit();        
        }
        
        public function addNegative($id){
            $database = $this->database;
            $database->beginTransaction();
            $database->query('UPDATE prispevky SET negativni = negativni + 1 WHERE id = ?', $id);
            $database->commit();
        }

      

}
?>