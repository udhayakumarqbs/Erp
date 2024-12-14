<?php
namespace App\Libraires\Job;
use App\Libraries\Job\Job;

    class TestJob extends Job{

        public function __construct(){
            parent::__construct();
            $this->can_burry=true;
            $this->max_attempt=2;
            $this->priority=Job::HIGH;
            $this->can_notify=true;
            $this->time_gap_for_next_job=120;
        }
        
        public function getName(){
            return "testjob";
        }

        public function execute($config=array()){
            $path=FCPATH."testjob/log.txt";
            $handle=fopen($path,"a+");
            fwrite($handle,"test hello ".date("y-m-d h:i:s")."\n");
            fclose($handle);
            echo "hello";
            return true;
        }

        public function getRunAt(){
            return parent::getRunAt();
        }

        public function getTryAfter(){
            return parent::getTryAfter();
        }

        public function getNotifyText(){
            return "TestJob successfully completed";
        }

    }
?>