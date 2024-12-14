<?php
namespace App\Libraries\Job;
use App\Libraries\Job\Job;

    class RfqSend extends Job{

        public function __construct(){
            parent::__construct();
            $this->can_burry=true;
            $this->max_attempt=3;
            $this->priority=Job::HIGHER;
            $this->can_notify=false;
            $this->time_gap_for_next_job=180;
            $this->system_job=false;
        }
        
        public function getName(){
            return "rfqsend";
        }


        public function execute($config=array()){
            // $ci=&get_instance();

            return true;
        }

        public function getRunAt(){
            return parent::getRunAt();
        }

        public function getTryAfter(){
            //20 Minutes
            return time()+(60*20);
        }

    }
?>