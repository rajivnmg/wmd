<?php
include_once("../../Model/DBModel/DbModel.php");
include_once("../../Model/DBModel/Enum_Model.php");
include_once( "../../Model/Business_Action_Model/po_model.php");
include_once( "../../Model/Masters/BuyerMaster_Model.php");
include_once( "../../Model/Business_Action_Model/ma_model.php");
include_once( "../../Model/Param/param_model.php");
include_once( "../../Model/Masters/Event.php");
include_once("../../GlobalConfig.php");
//Logger::configure("../../config.xml");
if(isset($_SESSION['SITENAME']) && $_SESSION['SITENAME']=='GURGAON'){
		Logger::configure($root_path."config.gur.xml");
	}else if(isset($_SESSION['SITENAME']) && $_SESSION['SITENAME']=='RUDRAPUR'){
		Logger::configure($root_path."config.rudr.xml");
	}else if(isset($_SESSION['SITENAME']) && $_SESSION['SITENAME']=='MANESAR'){
		Logger::configure($root_path."config.mane.xml");
	}else if(isset($_SESSION['SITENAME']) && $_SESSION['SITENAME']=='HARIDWAR'){
		Logger::configure($root_path."config.hari.xml");
	}else{
		Logger::configure($root_path."config.xml");
	}
$logger = Logger::getLogger('PO_Controller');

class EventsController {
    
	public function actionProcessEvents() {
       
		$res =  Event::findAllEmail();	
		foreach($res as $row) {
			if($row['event_type'] == EVENT_MAIL_TYPE) {
				if($this->_sendUserEmail($row)){
						echo 'Your mail has been sent successfully.';
      
				}else{
						echo 'Unable to send email. Please try again.';
				}
			} elseif($row['event_type'] == EVENT_SMS_TYPE) {
				$this->_sendUserSms($row);
            } elseif($row['event_type'] == EVENT_ADMIN_ONLY_MAIL_TYPE) {
                $this->_sendAdminOnlyMail($row);
            }
		}
        echo "Done";
        exit;
	}
	
	private function _sendUserEmail($row) {
		$event=new Event();
        $event->updateEvent($row['id'], -1);
		$event_fields = unserialize($row['event_fields']);
		$eventName =$row['event_name'];
		$body = $event->findMailbody($eventName, $event_fields);
        if($this->sendMail ($eventName, $event_fields, $body)) {
            $event->updateEvent($row['id'], 1);
			return 1;
        }else{
			return 0;
		}
		
	}
	private function _sendAdminOnlyMail($row) {
		$event=new Event();
        $event->updateEvent($row['id'], -1);
		$event_fields = unserialize($row['event_fields']);
		$eventName =$row['event_name'];
		$body = $event->findMailbody($eventName, $event_fields);
        if($this->sendMail ($eventName, $event_fields, $body)) {
            $event->updateEvent($row['id'], 1);
        }
	}
    
    private function sendMail ($eventName, $eventFields, $body) {
        $email = null;
        if(isset($eventFields['receiver_email'])) {
            $email = $eventFields['receiver_email'];
        }
        if(empty($email)) {
            return false;
        }
        $subject = null;
        $subjectArr = unserialize(EMAIL_SUBJECTS);
        if(isset($subjectArr[$eventName])) {
            $subject = $subjectArr[$eventName];
        }
		return mail('rajiv@codefire.in', $subject, $body);
       
    }
    
	private function _sendUserSms($row) {
		$event=new Event();
        $event->updateEvent($row['id'], -1);
		$event_fields = unserialize($row['event_fields']);
		$eventName = $row['event_name'];
		$body = $event->findMsgbody($eventName, $event_fields);
        if($this->sendSms ($event_fields, $body)) {
            $event->updateEvent($row['id'], 1);
        }
	}
    private function sendSms($eventFields, $body) {
        $phone_number = null;
        if(isset($eventFields['phone_number'])) {
            $phone_number = $eventFields['phone_number'];
        } else if(isset($eventFields['receiver_email'])) {
            $user = User::findByUsername($eventFields['receiver_email']);
            if(!empty($user)) {
                $phone_number = $user->phone_number;
            }
        }
        
        if(empty($phone_number)) {
            return false;
        }
        
        $data = array("To" => $phone_number, "Message" => $body);
        $sentSms = Helper::sendsms($data);
        return $sentSms;
    }   
}
