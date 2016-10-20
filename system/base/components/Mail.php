<?php

class Mail {

     private static $instance = NULL;

     protected $options;

     protected $isDriverLoadable = FALSE;

     private function __construct(array $options){

          $driver = $options['mail_driver'];
         /* 
          switch($driver){
          	  case 'php-mailer':
                 $this->isDriverLoadable = class_exists('PHPMailer');
                 $this->mailer = ($this->isDriverLoadable? new PHPMailer : NULL);
          	  break;
          	  case 'mailgun':
                 $this->isDriverLoadable = class_exists('Mailgun\Mailgun');
                 $this->mailer = ($this->isDriverLoadable? new \Mailgun\Mailgun("key-example") : NULL);
          	  break;
          	  case 'native':
                 ;
          	  break;
          	  default:

          	  break;
          }*/

          unset($options['mail_driver']);

          $this->options = $options;

          $this->setMailCredentials();
     }

     public static function createInstance(array $options){
          if(static::$instance == NULL){
               static::$instance = new Mail($options);
               return static::$instance;
          } 
     }

     public static function send(){

        // PHPMailer

        /*
        if(!$this->mailer->send()){

       	    throw new \Exception($this->mailer->ErrorInfo);
        }

        */	

     }  

     private function setMailCredentials(){

        // PHPMailer
        
        /*

        $this->mailer->Host = $this->options['mail_server'];

        if($this->options['protocol'] === "SMTP"){
             $this->mailer->isSMTP();
             $this->mailer->SMTPAuth = TRUE;
        }  

        if($this->options['encryption']){
             // requires TLS Encryption
             $this->mailer->SMTPSecure = $this->options['encryption_type'];
        }       

        $this->mailer->Username = $this->options['username'];
        $this->mailer->Password = $this->options['password'];
        $this->mailer->Port = $this->options['port']; 

        */

     }

     public static function setMailParameters(array $from, array $to, array $body, array $params){

         // PHPMailer
         
      /*
         $this->mailer->From = $from['email'];
         $this->mailer->FromName = $from['name'];

         $this->mailer->addAddress($to['email'], $to['name']);

         //$this->mailer->addCC("<email>");
         //$this->mailer->addBCC("<email>");
         //$this->mailer->addAttachment("<file_path>", "<file_id_text>");

         $this->mailer->isHTML($params['html_ok']);

         $this->mailer->Subject = $params['subject'];

         $this->mailer->Body = $body['main'];

         $this->mailer->AltBody = $body['alt'];

         if(array_key_exists('lang', $params)){
             $this->mailer->addLanguage($params['lang']); 
         }

      */
     }

}

?>