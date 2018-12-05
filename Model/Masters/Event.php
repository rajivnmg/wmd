<?php
include_once("root.php");
include_once($root_path."Config.php");
Logger::configure($root_path."config.xml");

 class Event {
          /**
         * To specify the behaviors to use for this model
         * @return : behaviors to use for this model 
         */
      
        public static function addEvent($event_type, $event_name, $event_fields,$id, $sentSMS = true) {
			$logger = Logger::getLogger('Event Model');
            $Query="INSERT INTO events(event_type,event_name,event_fields,po_inv_id) VALUES('$event_type','$event_name','$event_fields','$id')";
			$logger->debug($Query); // function to write log file
			$Result = DBConnection::InsertQuery($Query);
			return $Result;
        }

        public function updateEvent($id, $processed = false) {
		    $logger = Logger::getLogger('Event Model');
			$dat = date('Y-m-d H:i:s');
            $Query="UPDATE events set processed =$processed ,modified='$dat' WHERE id = $id ";
			$logger->debug($Query); // function to write log file
			$Result = DBConnection::UpdateQuery($Query); 
        }
		 public function findAllEmail() {
			$logger = Logger::getLogger('Event Model');
            $Query = "SELECT * FROM events WHERE processed =0 OR processed = -1 ORDER BY id DESC"; 
			$logger->debug($Query); // function to write log file
			$Result = DBConnection::SelectQuery($Query);
			return $Result;
			
        }
        public function findMailbody($template_file_name, $event_fields) {
            $templetpath = SITE_URL.'emailEvent/email_templates/html/' . $template_file_name . '.html';
            $file = fopen($templetpath, 'r');
            $body = fread($file, 5400);
            $body = str_replace("%REGARDS_NAME%", REGARDS_NAME, $body);
            $body = str_replace('%SITE_URL%', SITE_URL, $body);
		  
            if ($template_file_name == EVENT_PO_ACKNOWLEDGE) {
                $body = str_replace("%TITLE%", 'MultiWeld PO Acknowledgement', $body);
				//$body = str_replace("%PONUMBER%", $event_fields->pon, $body);
				$format = 'd/m/Y';
				$date = DateTime::createFromFormat($format, $event_fields->pod);				
				$body = str_replace("%PO_DATE%", $date->format('j M Y'), $body);
				$body = str_replace("%ORDER_NUMBER%", $event_fields->pon, $body);
               // $body = str_replace('%REGISTRATION_AS%', $event_fields['role'], $body);
            } else if ($template_file_name == EVENT_INVOICE_OE) { // Borrower, Lender for both
                $body = str_replace("%TITLE%", 'Dispatch Information-'.$event_fields->oinvoice_No, $body);
				$body = str_replace("%INVOICE%", $event_fields->oinvoice_No, $body);
				$body = str_replace("%PONUMBER%", $event_fields->pono, $body);
            } else if ($template_file_name == EVENT_INVOICE_ONE) { // Borrower, Lender for both
                $body = str_replace("%TITLE%", 'Dispatch Information-'.$event_fields->oinvoice_No, $body);
				$body = str_replace("%INVOICE%", $event_fields->oinvoice_No, $body);
				$body = str_replace("%PONUMBER%", $event_fields->pono, $body);
            }else if($template_file_name == EVENT_ADMIN_MAIL){
				$body = str_replace("%TITLE%", 'MultiWeld PO For Approval', $body);				
				$format = 'd/m/Y';
				$date = DateTime::createFromFormat($format, $event_fields->pod);				
				$body = str_replace("%PO_DATE%", $date->format('j M Y'), $body);
				$body = str_replace("%ORDER_NUMBER%", $event_fields->pon, $body);
              
			}
            return $body;
        }
        
        
    public function findAdminMailbody($template_file_name, $event_fields,$reason) {
		 
            $templetpath = SITE_URL.'emailEvent'.DIRECTORY_SEPARATOR .'email_templates'.DIRECTORY_SEPARATOR .'html'.DIRECTORY_SEPARATOR .'' . $template_file_name . '.html';
   
            $file = fopen($templetpath, 'r');
            $json = json_decode($event_fields, true);
           
            $body = fread($file, 5400);
            $body = str_replace("%REGARDS_NAME%", REGARDS_NAME, $body);
            $body = str_replace('%SITE_URL%', SITE_URL, $body);
		  	$body = str_replace("%TITLE%", 'MultiWeld PO For Approval', $body);		
		  	$body = str_replace("%REASON%", $reason, $body);				
			//$format = 'd/m/Y';
			//$date = DateTime::createFromFormat($format, $event_fields->pod);				
			$body = str_replace("%PO_DATE%", $json['pod'], $body);
			$body = str_replace("%ORDER_NUMBER%", $json['pon'], $body);
            
            return $body;
        }
              

        public function findMsgbody($template_file_name, $event_fields) {
            $templetpath = 'email_templates/text/' . $template_file_name . '.txt';
            $file = fopen($templetpath, 'r');
            $body = fread($file, 5400);
            $body = str_replace("%REGARDS_NAME%", REGARDS_NAME, $body);
            if (in_array($template_file_name == EVENT_PO_ACKNOWLEDGE)) {
                $body = str_replace("%TITLE%", MAIL_TITLE, $body);
                $body = str_replace('%REGISTRATION_AS%', $event_fields['role'], $body);
            } else if ($template_file_name == EVENT_INVOICE) { // Borrower, Lender for both
                $body = str_replace('%REGISTRATION_AS%', $event_fields['role'], $body);
            } 
            return $body;
        }

    }
    
