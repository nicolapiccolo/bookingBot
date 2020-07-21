<?php

	require __DIR__.'/vendor/autoload.php';
	use Kreait\Firebase\Factory;
	use Kreait\Firebase\ServiceAccount;
	class Manager{
		protected $database;
	   	protected $dbname = 'users';
	   	public function __construct(){
	       $serviceAccount = ServiceAccount::fromJsonFile(__DIR__ . '/secret/user.json');
	       $firebase = (new Factory)
		    ->withServiceAccount($serviceAccount)
		    ->withDatabaseUri('https://booking-1091b.firebaseio.com/')
		    ->create();
	       $this->database = $firebase->getDatabase();
   		}

   		public function getTodayReservations(){
   			//return $this->database->getReference('pren')->getValue();
   			$today = date('y-m-d');
   			$array = array();
   			$result = $this->database->getReference('pren')->getValue();

   			foreach ($result as $key => $value) {
   				$data = substr($value['orario'], 2,8);
   				if ($data == $today) {
   					$array[$key] = $value; 
   				}
   			}
   			return $array ;
   		}

   		private function convertDate($string, $start, $end){
   			return (int)substr($string, $start, $end);
   		}

   		public function getHistoryReservations(){
   			$today = date('y-m-d');
   			$array = array();
   			$result = $this->database->getReference('pren')->getValue();
   			foreach ($result as $key => $value) {
   				$data = substr($value['orario'], 2,8);
   				if($this->convertDate($data,0,2) <= $this->convertDate($today,0,2)){
   					if($this->convertDate($data,3,2) <= $this->convertDate($today,3,2)){
   						if($this->convertDate($data,6,2) < $this->convertDate($today,6,2)){
   							$array[$key] = $value; 
   						}
   					}
   				}
   			}
   			return $array;
   		}

   		public function getFutureReservations(){
   			$today = date('y-m-d');
   			$array = array();
   			$result = $this->database->getReference('pren')->getValue();
   			foreach ($result as $key => $value) {
   				$data = substr($value['orario'], 2,8);
   				if($this->convertDate($data,0,2) >= $this->convertDate($today,0,2)){
   					if($this->convertDate($data,3,2) >= $this->convertDate($today,3,2)){
   						if($this->convertDate($data,6,2) > $this->convertDate($today,6,2)){
   							$array[$key] = $value; 
   						}
   					}
   				}
   			}
   			return $array;
   		}

   		public function getReservation($key){

   			return $this->database->getReference('pren/')->getChild($key)->getValue();

   		}

   		public function verifyDisponibility($data,$time,$n_posti,$range,$tot){
   			$result = $this->database->getReference('pren')->getValue();
   			$occuped=0;
  			$range = (int)$range;
   			$tm = (int)$time;
   			foreach ($result as $key => $value) {
   				$d = substr($value['orario'], 0,10);
   				$t = (int)substr($value['orario'], 11,2);
   				if ($value['status'] != 'cancelled') {
   					if($d == $data){
   						if(($tm-$range) <= $t && $t <= $tm){
   							$occuped = $occuped + $value['n_posti'];
   						}
   					}
   				}
   			}
   			if(($occuped + $n_posti)>$tot){
   				return false;
   			}
   			else{
   				return true;
   			}
   		}

   		public function getTotalSeats(){
   			return $this->database->getReference('tot_seat')->getValue();
   		}

   		public function getSpan(){
   			return $this->database->getReference('span')->getValue();
   		}

      public function getTimeOpening(){
        return $this->database->getReference('time')->getChild('opening')->getValue();
      }

      public function getTimeClosing(){
        return $this->database->getReference('time')->getChild('closing')->getValue();
      }

      public function numPersonTime($time){

      }

      public function getStats(){
        $result = $this->getTodayReservations();
        $start = $this->getTimeOpening();
        
      }

   		public function setReservation($key,$data,$time,$n_posti,$status){
   			$tot = $this->getTotalSeats();
   			$range = $this->getSpan();
   			if($this->verifyDisponibility($data,$time,$n_posti,$range,$tot) == true){
				$reservation = $this->database->getReference('pren/')->getChild($key)->getValue();
	   			$orario = $data."T".$time.":00+02:00";
	   			$reservation['orario'] = $orario;
	   			$reservation['n_posti'] = $n_posti;
	   			$reservation['status'] = $status;
	   			$this->database->getReference('pren/')->getChild($key)->set($reservation);
          if(mail('mr.alfred.bot@gmail.com', 'oggetto', 'contenuto', 'stefanotria1912@gmail.com'))
              echo "<script language='javascript'>alert('Email sent correctly!');</script>";
         else echo "<script language='javascript'>alert('PROBLEM, Email not sent!');</script>";
            
          
	   			echo "<meta http-equiv='refresh' content='0'>";
   			}
   			else{
   				echo "<script language='javascript'>alert('Not enough seats!');</script>";
   			}
   			
   		}

      public function getTimePassed($time)
      {
        $today = new DateTime(date('y-m-d'));
        $data = new DateTime($time);
        $passed = $today->diff($data);
        return $passed->format('%a');
      }

      public function saveSettings($timeOpening, $timeClosing, $tot_posti, $span){
        $this->database->getReference('time')->getChild('opening')->set($timeOpening);
        $this->database->getReference('time')->getChild('closing')->set($timeClosing);
        $this->database->getReference('span')->set($span);
        $this->database->getReference('tot_seat')->set($tot_posti);
        echo "<meta http-equiv='refresh' content='0'>";
      }

   		public function dateStamp($string){
            $month = "";
            switch(substr($string, 5,2)){
                case '01':
                    $month = "January";
                    break;
                case '02':
                    $month = "February";
                    break;
                case '03':
                    $month = "March";
                    break;
                case '04':
                    $month = "April";
                    break;
                case '05':
                    $month = "May";
                    break;
                case '06':
                    $month = "June";
                    break;
                case '07':
                    $month = "July";
                    break;
                case '08':
                    $month = "August";
                    break;
                case '09':
                    $month = "September";
                    break;
                case '10':
                    $month = "October";
                    break;
                case '11':
                    $month = "November";
                    break;
                case '12':
                    $month = "December";
                    break;

            }
            return substr($string, 8,2)." ".$month." ".substr($string, 0,4);

        }

	}

	//$manager = new Manager();
	//print_r($manager->setReservation('-MBrXfKUcuE5Rq4trZIF','2020-07-23','21:00','41','pending'));
?>