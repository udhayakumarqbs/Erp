<?php
namespace App\Libraires\Job;

use App\Libraries\Job\Job;

    class SafeCustomFieldDelete extends Job{

        protected  $db;
        public function __construct(){
            parent::__construct();
            $this->can_burry=true;
            $this->max_attempt=3;
            $this->priority=Job::HIGH;
            $this->can_notify=false;
            $this->time_gap_for_next_job=120;
            $this->system_job=true;
            $this->db = \Config\Database::connect();
        }
        
        public function getName(){
            return "safecustomfielddelete";
        }

        public function execute($config=array()){
            // $ci=&get_instance();
            $query="SELECT cf_id FROM custom_fields WHERE can_be_purged=1 ";
            $cfs=$this->db->query($query)->getResultArray();
            if(!empty($cfs)){
                $query="DELETE FROM custom_fields WHERE cf_id=? AND (SELECT COUNT(cfv_id) AS total FROM custom_field_values WHERE cf_id=?)=0 ";
                foreach($cfs as $cf){
                    $this->db->query($query,array($cf['cf_id'],$cf['cf_id']));
                }
            }
            return true;
        }

        public function getRunAt(){
            //30 Days Once
            return time()+(30*24*60*60);
        }

        public function getTryAfter(){
            //1 Day
            return time()+(1*24*60*60);
        }

    }
?>