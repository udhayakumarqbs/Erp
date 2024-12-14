<?php
namespace App\Libraries\Job;


    abstract class Job{
        /*
            JOB PRIORITY
        */
        const LOW=0;
        const MEDIUM=1;
        const HIGH=2;
        const HIGHER=3;
        const HIGHEST=4;

        /*
            JOB STATUS
        */
        const PENDING=5;
        const PROCESSING=6;
        const FAILED=7;
        const COMPELETED=8;

        protected $config;
        protected $priority;
        protected $can_burry;
        protected $max_attempt;
        protected $can_notify;
        protected $system_job;
        protected $time_gap_for_next_job;

        public function __construct(){
            $this->config=array();
            $this->priority=self::MEDIUM;
            $this->can_burry=false;
            $this->can_notify=false;
            $this->max_attempt=1;
            $this->system_job=false;
            $this->time_gap_for_next_job=60;
        }

        public abstract function execute($config=array());
        public abstract function getName();

        public function getRunAt(){
            return time()+(30);
        }

        public function getTryAfter(){
            return time()+(10*60);
        }

        public function getNotifyText(){
            return "";
        }

        public function getTimeForNextJob(){
            return $this->time_gap_for_next_job;
        }
        
        public function getPriority(){
            return $this->priority;
        }

        public function canBurry(){
            return $this->can_burry;
        }

        public function getMaxAttempt(){
            return $this->max_attempt;
        }

        public function canNotify(){
            return $this->can_notify;
        }

        public function isSystemJob(){
            return $this->system_job;
        }

    }
?>