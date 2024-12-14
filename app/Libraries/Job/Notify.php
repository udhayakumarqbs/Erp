<?php
    // defined('BASEPATH') or exit("No direct script access allowed");

namespace App\Libraries\Job;
use App\Libraries\Job\Job;

    class Notify extends Job{

        public function __construct(){
            parent::__construct();
            $this->can_burry=true;
            $this->max_attempt=3;
            $this->priority=Job::HIGHER;
            $this->can_notify=false;
            $this->time_gap_for_next_job=120;
            $this->system_job=false;
        }
        
        public function getName(){
            return "notify";
        }

        public function execute($config=array()){
            
            return true;
        }

        public function getRunAt(){
            //Deceided by users
            return 0;
        }

        public function getTryAfter(){
            //1 Minute
            return time()+(60);
        }

    }
?>