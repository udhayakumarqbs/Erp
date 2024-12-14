<?php

namespace App\Libraries;

use App\Libraires\Job\RfqSend;
use App\Libraires\Job\SafeCustomFieldDelete;
use App\Libraires\Job\SafeRoleDelete;
use App\Libraires\Job\TestJob;
use App\Libraries\Job\Job;
use App\Libraries\Job\Notify;
use App\Libraries\Job\RfqSend as JobRfqSend;

class Scheduler
{
    private static function getJobByName($jobname)
    {
        $job = null;
        switch ($jobname) {
            case "testjob":
                $job = new TestJob();
                break;
            case "saferoledelete":
                $job = new SafeRoleDelete();
                break;
            case "notify":
                $job = new Notify();
                break;
            case "safecustomfielddelete":
                $job = new SafeCustomFieldDelete();
                break;
            case "rfqsend":
                $job = new JobRfqSend();
                break;
            default:
                break;
        }
        return $job;
    }

    public static function dispatch(Job $job, $config = array(), $timeadd = 0)
    {
        $db = \Config\Database::connect();
        $jobname = $job->getName();
        $max_attempt = $job->getMaxAttempt();
        $params = json_encode($config);
        $status = Job::PENDING;
        $runat = $job->getRunAt() + $timeadd;
        $priority = $job->getPriority();
        $system = $job->isSystemJob() ? 1 : 0;

        $query = "INSERT INTO erp_jobqueue(job_name,job_params,attempt,status,priority,run_at,system) VALUES(?,?,?,?,?,?,?) ";
        $db->query($query, array($jobname, $params, $max_attempt, $status, $priority, $runat, $system));
        $insert_id = $db->insertID();
        return $insert_id;
    }

    public static function changeRunAt($jobid, $run_at)
    {
        $status = Job::PENDING;
        $db = \Config\Database::connect();
        $query = "UPDATE erp_jobqueue SET run_at=? WHERE job_id=$jobid AND status=$status";
        $db->query($query, array($run_at));
        if ($db->affectedRows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public static function replaceOldJob($jobid, Job $job, $config = array(), $timeadd = 0)
    {
        $db = \Config\Database::connect();

        $status = Job::PENDING;
        $query = "DELETE FROM erp_jobqueue WHERE job_id=$jobid AND status=$status";
        $db->query($query);
        return self::dispatch($job, $config, $timeadd);
    }

    public static function safeDelete($jobid)
    {
        $db = \Config\Database::connect();

        $status = Job::PENDING;
        $query = "DELETE FROM erp_jobqueue WHERE job_id=$jobid AND status=$status";
        $db->query($query);
    }

    public static function run()
    {
        $db = \Config\Database::connect();

        $status = Job::PENDING;
        $time = time();
        $query = "SELECT * FROM erp_jobqueue WHERE status=$status AND run_at <= $time ORDER BY priority DESC LIMIT 1";
        $jobresult = $db->query($query)->getRow();
        if (!empty($jobresult)) {
            $status = Job::PROCESSING;
            $query = "UPDATE erp_jobqueue SET status=$status , attempt=attempt-1 WHERE job_id=" . $jobresult->job_id;
            $job = self::getJobByName($jobresult->job_name);
            if (isset($job)) {
                $config = json_decode($jobresult->job_params, true);
                if ($job->execute($config)) {
                    if ($job->canNotify()) {
                        self::notify($job);
                    }
                    if ($job->isSystemJob()) {
                        $status = Job::PENDING;
                        $runat = $job->getRunAt();
                        $max_attempt = $job->getMaxAttempt();
                        $query = "UPDATE erp_jobqueue SET status=$status , run_at=$runat , attempt=$max_attempt WHERE job_id=" . $jobresult->job_id;
                        $db->query($query);
                    } else {
                        self::destroy($jobresult->job_id);
                    }
                } else {
                    self::burry($job, $jobresult->job_id);
                }
            } else {
                self::destroy($jobresult->job_id);
            }
        }
    }

    private static function destroy($job_id)
    {
        $db = \Config\Database::connect();

        $query = "DELETE FROM erp_jobqueue WHERE job_id=$job_id";
        $db->query($query);
    }

    private static function burry($job, $job_id)
    {
        $db = \Config\Database::connect();

        if ($job->canBurry()) {
            $query = "SELECT attempt FROM erp_jobqueue WHERE job_id=$job_id AND attempt<>0";
            $check = $db->query($query)->getRow();
            if (!empty($check)) {
                $tryafter = $job->getTryAfter();
                $status = Job::PENDING;
                $query = "UPDATE erp_jobqueue SET status=$status , run_at='$tryafter' WHERE job_id=$job_id ";
                $db->query($query);
            } else {
                self::destroy($job_id);
            }
        } else {
            self::destroy($job_id);
        }
    }

    private static function notify($job)
    {
        $db = \Config\Database::connect();

        $completed = time();
        $query = "INSERT INTO erp_log(title,log_text,ref_link,done_by,created_at) VALUES(?,?,?,?,?) ";
        $db->query($query, array("Scheduler Run", $job->getNotifyText(), "", "SYSTEM", $completed));
    }
}
