<?php

class TextStream {

      const EVENT_HEADING = 1; # event: noupdate \n

      const ID_HEADING = 2; # id: 3299029492 \n

      const RETRY_HEADING = 3; # retry: 15000 \n

      private static $instance = NULL;

      private $streamText;

      private $eventDataBlocks;

      private $eventDataBlockSize; // a.k.a number of rows in table


      private function __construct(){

          $this->streamText = '';

          $this->eventDataBlocks = array();

          $this->eventDataBlockSize = 16;

          $this->lineSize = 12;

          $this->columns = array('subject', 'verb', 'object');
      
      }

      public static function createInstance(){

          if(static::$instance == NULL){
               static::$instance = new TextStream();
               return static::$instance;
          }  
      }

      public function setForAjax(){

          set_time_limit(0); // removes time limit for script execution

          ignore_user_abort(FALSE); // enable/disbale automatic script exit if user disconnects
      }

      public static function getEvents(Model $activities, $noUpdateCount, $lastEventId){

         $maxCheckCount = 4;

         $events = new \stdClcass();

         $events->noupdate = TRUE;

         $this->includeStreamHeading('-');

         $events->data = $this->streamText;

          while((!connection_aborted() || !connection_timeout())){

                if(Cache::has('app_activities')){ // 
                
                     static::$instance->setEventDataBlocks(Cache::get('app_activities'));
                } 

                if(!static::$instance->isUpdateAvailable()){ 

                     static::$instance->setEventDataBlocks($activities->get($this->columns, array('id'=> array('>', $lastEventId)))->exec(FALSE, $this->eventDataBlockSize));
                }     

                if(static::$instance->isUpdateAvailable()){

                      $events->noupdate = FALSE;

                      $events->data = static::$instance->prepareEventPayload();

                      break;

                }else{

                      --$maxCheckCount;

                      if($maxCheckCount === 0){
                  
                           break;
                      }
                     
                      sleep(3); // wait for 3 seconds
                }
          }

          return $events;

      } 

      private function includeStreamHeading($line, $type = -1){
          
          $heading = '';

          switch($type){
             case 1:
                $heading = 'event: ';
             break;
             case 3:
                $heading = 'retry: ';
             break;
             case 2:
               $heading = 'id: ';
             break;
             default:
               $heading = 'data: ';
             break;
          }

          $this->streamText .=  $heading . $line . PHP_EOL;
      }

      public function setEventDataBlocks(array $eventData){

          if(count($eventData) > 0){

                $this->eventDataBlocks[] = $eventData;

           }     
      }

      public function isUpdateAvailable(){

            return count($this->eventDataBlocks) > 0;
      }

      public function prepareEventPayload(){

             $this->streamText = '';

             $lines = array();

             for($x = 0; $x < $this->eventDataBlockSize; $x++){

                  $lines[$x] = json_encode($this->eventDataBlocks[$x]);

                  $this->includeStreamHeading(/*array_slash(*/$lnes[$x]/*, $this->lineSize)*/);

             }

      }

}


?>