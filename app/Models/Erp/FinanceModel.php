<?php

namespace App\Models\Erp;

use CodeIgniter\Model;
use PSpell\Config;

class FinanceModel extends Model{
    protected $table = "account_groups";
    protected $primaryKey = "acc_group_id";
    protected $allowedFields = [
        "base_id",
        "group_name",
        "profit_loss",
        "created_by"
    ];

    protected $usedTimestamps = true;
    protected $createdField = "created_at";
    protected $updatedField  = null;

    private $cashflow=array(
        0=>"No effect on cash flow",
        1=>"Operating activity",
        2=>"Investing activity",
        3=>"Financing activity",
        4=>"Cash or cash equivalent"
    );

    private $cashflow_bg=array(
        0=>"st_danger",
        1=>"st_primary",
        2=>"st_dark",
        3=>"st_violet",
        4=>"st_success"
    );


    //Account Group
    public function insert_update_accgroup($accgroup_id, $group_name, $base_id, $profit_loss){        
        if(empty($accgroup_id)){
            $this->insert_accgroup($group_name, $base_id, $profit_loss);
        }else{
            $this->update_accgroup($accgroup_id, $group_name, $base_id, $profit_loss);
        }
    }

    private function insert_accgroup($group_name, $base_id, $profit_loss){
        $created_by=get_user_id();
        $session = \Config\Services::session();
        $query="INSERT INTO account_groups(group_name,base_id,profit_loss,created_at,created_by) VALUES(?,?,?,?,?)";
        $this->db->query($query,array($group_name,$base_id,$profit_loss,time(),$created_by));
        $config['title']="Account Group Insert";
        if(!empty($this->db->insertID())){
            $config['log_text']="[ Account Group successfully created ]";
            $config['ref_link']='erp/finance/accountgroup/';
            $session->setFlashdata("op_success","Account Group successfully created");
        }else{
            $config['log_text']="[ Account Group failed to create ]";
            $config['ref_link']="";
            $session->setFlashdata("op_error","Account Group failed to create");
        }   
        log_activity($config);
    }

    private function update_accgroup($accgroup_id, $group_name, $base_id, $profit_loss){
        $session = \Config\Services::session();
        $query="UPDATE account_groups SET group_name=? , base_id=? , profit_loss=? WHERE acc_group_id=$accgroup_id ";
        $this->db->query($query,array($group_name,$base_id,$profit_loss));
        $config['title']="Account Group Update";
        if($this->db->affectedRows() > 0){
            $config['log_text']="[ Account Group successfully updated ]";
            $config['ref_link']='erp/finance/accountgroups/';
            $session->setFlashdata("op_success","Account Group successfully updated");
        }else{
            $config['log_text']="[ Account Group failed to update ]";
            $config['ref_link']="";
            $session->setFlashdata("op_error","Account Group failed to update");
        }   
        log_activity($config);
    }

    public function get_dtconfig_accgroups(){
        $config=array(
            "columnNames"=>["sno","group name","base name","profit-loss","action"],
            "sortable"=>[0,1,0,1,0],
        );
        return json_encode($config);
    }

    public function get_account_bases(){
        $query="SELECT base_id,base_name FROM accountbase ";
        $result=$this->db->query($query)->getResultArray();
        return $result;
    }

    public function getAccgroupsDatatable($limit, $offset, $search, $orderby, $ordercol)
    {
        $query = $this->db->table('account_groups')
            ->select('acc_group_id, group_name, base_name, profit_loss')
            ->join('accountbase', 'account_groups.base_id=accountbase.base_id');
    
        $count = clone $query;
        $where = false;
    
        if (!empty($search)) {
            if (!$where) {
                $query->like('group_name', $search);
                $count->like('group_name', $search);
                $where = true;
            } else {
                $query->like('group_name', $search);
                $count->like('group_name', $search);
            }
        }
    
        if (!empty($ordercol) && !empty($orderby)) {
            $query->orderBy($ordercol, $orderby);
        }
    
        $query->limit($limit, $offset);
    
        $result = $query->get()->getResultArray();
        $mResult = [];
        $sno = $offset + 1;
    
        foreach ($result as $r) {
            $action = '<div class="dropdown tableAction">
                            <a type="button" class="dropBtn HoverA "><i class="fa fa-ellipsis-v"></i></a>
                            <div class="dropdown_container">
                                <ul class="BB_UL flex">
                                    <li><a href="#" data-ajax-url="' . url_to('erp.finance.ajax', $r['acc_group_id']) . '" title="Edit" class="bg-success modalBtn"><i class="fa fa-pencil"></i> </a></li>
                                    <li>
                                    <a href="' . url_to('erp.finance.delete', $r['acc_group_id']) . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a>
                                    </li>
                                </ul>
                            </div>
                        </div>';
    
            $profitLoss = '<span class="st ' . ($r['profit_loss'] == 1 ? 'st_violet" >Yes</span>' : 'st_dark" >No</span>');
    
            $mResult[] = [
                $r['acc_group_id'],
                $sno,
                $r['group_name'],
                $r['base_name'],
                $profitLoss,
                $action
            ];
    
            $sno++;
        }
        $response = [
            'total_rows' => $count->countAllResults(),
            'data' => $mResult
        ];
    
        return $response;
    }
    
    public function deleteAccgroup($accgroupId)
    {
        $deleted=false;
        $check=$this->db->query("SELECT COUNT(acc_group_id) AS total FROM gl_accounts WHERE acc_group_id=$accgroupId ")->getRow();
        // var_dump($check);
        // exit();

        if ($check->total <= 0) {
            $query="DELETE FROM account_groups WHERE acc_group_id=$accgroupId";
            $this->db->query($query);
            if ($this->db->affectedRows() > 0) {    
                $deleted=true;
            }
        }
        $config['title']="Account Group Delete";
        if($deleted){
            $config['log_text']="[ Account Group successfully deleted ]";
            $config['ref_link']='erp/finance/accountgroups/';
            session()->setFlashdata("op_success","Account Group successfully deleted");
        }else{
            $config['log_text']="[ Account Group failed to delete ]";
            $config['ref_link']="erp/finance/accountgroups/";
            session()->setFlashdata("op_error","Account Group failed to delete");
        }   
        log_activity($config);
    }

    public function getAccgroupsExport($type)
    {
        $columns = [
            "profit-loss" => "profit_loss",
            "group name" => "group_name"
        ];
    
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";
    
        $builder = $this->db->table('account_groups');
        $builder->select('group_name, base_name');
        $builder->join('accountbase', 'account_groups.base_id = accountbase.base_id', 'left');
    
        $builder->select('(CASE WHEN profit_loss = 1 THEN "Yes" ELSE "No" END) AS profit_loss', false);
    
        if (!empty($search)) {
            $builder->like('group_name', $search);
        }
    
        if (!empty($ordercol) && !empty($orderby)) {
            $builder->orderBy($ordercol, $orderby);
        }
    
        $result = $builder->get()->getResultArray();
    
        return $result;
    }
        
    //GL Accounts 
    public function insertUpdateGlaccounts($data){
        if(empty($data['gl_acc_id'])){
            $this->insertGlaccount($data);
        }else{
            $this->updateGlaccount($data);
        }
    }

    private function insertGlaccount($data){
        $session = \Config\Services::session();
        $this->db->transBegin();
        $query="INSERT INTO gl_accounts(account_code,account_name,acc_group_id,cash_flow,order_num,created_at,created_by) VALUES(?,?,?,?,?,?,?) ";
        $this->db->query($query,array($data['acc_code'], $data['acc_name'], $data['acc_group'], $data['cashflow'], $data['ordernum'], time(), $data['created_by']));
        $gl_acc_id=$this->db->insertId();
        if(!empty($gl_acc_id)){
            $query="INSERT INTO general_ledger(gl_acc_id,period,actual_amt,balance_fwd) VALUES(?,?,?,?) ";
            $this->db->query($query,array($gl_acc_id,$data['period'],0.00,$data['balance_fwd']));
            if(!empty($this->db->insertId())){
                $inserted=true;
            }else{
                $this->db->transRollback();
            }
        }
        $this->db->transComplete();
        if($this->db->transStatus()===FALSE){
            $inserted=false;
            $this->db->transRollback();
        }else{
            if($inserted){
                $this->db->transCommit();
            }else{  
                $this->db->transRollback();
            }
        }
        $config['title']="GL Account Insert";
        if($inserted){
            $config['log_text']="[ GL Account successfully created ]";
            $config['ref_link']='erp/finance/glaccounts/';
            $session->setFlashdata("op_success","GL Account successfully created");
        }else{
            $config['log_text']="[ GL Account failed to create ]";
            $config['ref_link']="erp/finance/glaccounts/";
            $session->setFlashdata("op_error","GL Account failed to create");
        }
        log_activity($config);
    }

    private function updateGlaccount($data){
        $session = \Config\Services::session();
        $query="UPDATE gl_accounts SET account_code=? , account_name=? , acc_group_id=? , cash_flow=? , order_num=? WHERE gl_acc_id=".$data['gl_acc_id'];
        $this->db->query($query,array($data['acc_code'],$data['acc_name'],$data['acc_group'],$data['cashflow'],$data['ordernum']));
        $config['title']="GL Account Update";
        if($this->db->affectedRows() > 0){
            $config['log_text']="[ GL Account successfully updated ]";
            $config['ref_link']='erp/finance/glaccounts/';
            $session->setFlashdata("op_success","GL Account successfully updated");
        }else{
            $config['log_text']="[ GL Account failed to update ]";
            $config['ref_link']="erp/finance/glaccounts/";
            $session->setFlashdata("op_error","GL Account failed to update");
        }
        log_activity($config);
    }

    public function getGlAccountsDatatable($data){
        $query = $this->db->table('gl_accounts')
            ->select('gl_acc_id, account_code, account_name, account_groups.group_name, cash_flow')
            ->join('account_groups', 'gl_accounts.acc_group_id=account_groups.acc_group_id', 'left');
    
        $count = $this->db->table('gl_accounts')
            ->selectCount('gl_acc_id', 'total')
            ->join('account_groups', 'gl_accounts.acc_group_id=account_groups.acc_group_id', 'left');
    
        if (!empty($data['group'])) {
            $filter1Col = $data['columns']['group'];
            $filter1Val = $data['group'];
            $query->where($filter1Col, $filter1Val);
            $count->where($filter1Col, $filter1Val);
        }
    
        if (!empty($data['search'])) {
            $query->groupStart()
                ->like('account_code', $data['search'])
                ->orLike('account_name', $data['search'])
                ->groupEnd();
    
            $count->groupStart()
                ->like('account_code', $data['search'])
                ->orLike('account_name', $data['search'])
                ->groupEnd();
        }
    
        if (!empty($data['ordercol']) && !empty($data['orderby'])) {
            $query->orderBy($data['ordercol'], $data['orderby']);
        }
    
        $query->limit($data['limit'], $data['offset']);
    
        $result = $query->get()->getResultArray();
        $m_result = [];
        $sno = $data['offset'] + 1;
   
        foreach ($result as $r) {
            $action = '<div class="dropdown tableAction">
                        <a type="button" class="dropBtn HoverA "><i class="fa fa-ellipsis-v"></i></a>
                        <div class="dropdown_container">
                            <ul class="BB_UL flex">
                                <li><a href="#" data-ajax-url="' . url_to('erp.finance.ajaxglaccount', $r['gl_acc_id']) . '" title="Edit" class="bg-success modalBtn"><i class="fa fa-pencil"></i> </a></li>
                                <li><a href="' . url_to('erp.finance.glaccountdelete', $r['gl_acc_id']) . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
                            </ul>
                        </div>
                    </div>';
            $cashflow = '<span class="st ' . $this->cashflow_bg[$r['cash_flow']] . '" >' . $this->cashflow[$r['cash_flow']] . '</span>';
            array_push($m_result, [
                $r['gl_acc_id'],
                $sno,
                $r['account_code'],
                $r['account_name'],
                $r['group_name'],
                $cashflow,
                $action
            ]);
    
            $sno++;
        }
    
        $response = [
            'total_rows' => $count->get()->getRow()->total,
            'data' => $m_result
        ];
    
        return $response;
    }

    public function deleteGlAccount($gl_acc_id)
    {
        $db = \Config\Database::connect();
        $session = \Config\Services::session();
        $deleted = false;
    
        $check1 = $db->query("SELECT COUNT(gl_acc_id) AS total FROM journal_entry WHERE gl_acc_id=$gl_acc_id")->getRow();
        $check2 = $db->query("SELECT COUNT(gl_acc_id) AS total FROM bankaccounts WHERE gl_acc_id=$gl_acc_id")->getRow();
    
        if ($check1->total <= 0 && $check2->total <= 0) {
            $db->transBegin();
    
            $db->table('gl_accounts')->delete(['gl_acc_id' => $gl_acc_id]);
            $db->table('general_ledger')->delete(['gl_acc_id' => $gl_acc_id]);
    
            if ($db->transStatus() === false) {
                $db->transRollback();
            } else {
                $db->transCommit();
                $deleted = true;
            }
        }
    
        $config = [
            'title' => 'GL Account Delete',
            'log_text' => $deleted ? '[ GL Account successfully deleted ]' : '[ GL Account failed to delete ]',
            'ref_link' => $deleted ? 'erp/finance/glaccounts/' : 'erp/finance/glaccounts/',
        ];
    
        $session->setFlashdata($deleted ? 'op_success' : 'op_error', $deleted ? 'GL Account successfully deleted' : 'GL Account failed to delete');
        log_activity($config);
    }
    
    public function getDtconfigGlaccounts(){
        $config=array(
            "columnNames"=>["sno","account code","account name","account group","cashflow","action"],
            "sortable"=>[0,1,1,0,0,0],
            "filters"=>["group"]
        );
        return json_encode($config);
    }
    
    public function getCashFlow(){
        return $this->cashflow;
    }

    public function getAccountGroups(){
        $query="SELECT acc_group_id,group_name FROM account_groups ";
        $result=$this->db->query($query)->getResultArray();
        return $result;
    }

    public function getGlaccountsExport($type)
    {
        $columns = [
            "account code" => "account_code",
            "account name" => "account_name",
            "group" => "account_groups.acc_group_id"
        ];
    
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";
    
        $builder = $this->db->table('gl_accounts');
        $builder->select('account_code, account_name, account_groups.group_name');
        $builder->join('account_groups', 'gl_accounts.acc_group_id = account_groups.acc_group_id', 'left');
    
        $builder->select('(CASE ' . implode(' ', array_map(function ($key, $value) {
            return 'WHEN cash_flow = ' . $key . ' THEN ' . $this->db->escape($value) . ' ';
        }, array_keys($this->cashflow), $this->cashflow)) . 'END) AS cashflow', false);
    
        if (!empty($_GET['group'])) {
            $builder->where('account_groups.acc_group_id', $_GET['group']);
        }
    
        if (!empty($search)) {
            $builder->groupStart();
            $builder->like('account_code', $search);
            $builder->orLike('account_name', $search);
            $builder->groupEnd();
        }
    
        if (!empty($ordercol) && !empty($orderby)) {
            $builder->orderBy($ordercol, $orderby);
        }
    
        $result = $builder->get()->getResultArray();
    
        return $result;
    }
    

    #Bank Accounts
    public function getDtconfigBankaccounts(){
        $config=array(
            "columnNames"=>["sno","bank name","bank account no","bank code","gl account","branch","action"],
            "sortable"=>[0,1,1,0,0,0],
        );
        return json_encode($config);
    }

    public function insertUpdateBankaccounts($data){
        if(empty($data['bank_id'])){
            $this->insertBankaccount($data);
        }else{
            $this->updateBankaccount($data);
        }
    }

    private function insertBankaccount($data){
        $session = \Config\Services::session();
        $query="INSERT INTO bankaccounts(gl_acc_id,bank_name,bank_acc_no,bank_code,branch,address,created_at,created_by) VALUES(?,?,?,?,?,?,?,?) ";
        $this->db->query($query,array($data['gl_acc_id'],$data['bank_name'],$data['bank_acc_no'],$data['bank_code'],$data['branch'],$data['address'],time(),$data['created_by']));
        $config['title']="Bank Account Insert";
        if(!empty($this->db->insertID())){
            $config['log_text']="[ Bank Account successfully created ]";
            $config['ref_link']='erp/finance/bankaccounts/';
            $session->setFlashdata("op_success","Bank Account successfully created");
        }else{
            $config['log_text']="[ Bank Account failed to create ]";
            $config['ref_link']="erp/finance/bankaccounts/";
            $session->setFlashdata("op_error","Bank Account failed to create");
        }
        log_activity($config);
    }

    private function updateBankaccount($data){
        $session = \config\Services::session();
         $query="UPDATE bankaccounts SET gl_acc_id=? , bank_name=? , bank_acc_no=? , bank_code=? , branch=? , address=? WHERE bank_id=". $data['bank_id'];
        $this->db->query($query,array($data['gl_acc_id'],$data['bank_name'],$data['bank_acc_no'],$data['bank_code'],$data['branch'],$data['address']));
        $config['title']="Bank Account Update";
        if($this->db->affectedRows() > 0){
            $config['log_text']="[ Bank Account successfully updated ]";
            $config['ref_link']='erp/finance/bankaccounts/';
            $session->setFlashdata("op_success","Bank Account successfully updated");
        }else{
            $config['log_text']="[ Bank Account failed to update ]";
            $config['ref_link']="erp/finance/bankaccounts/";
            $session->setFlashdata("op_error","Bank Account failed to update");
        }
        log_activity($config);
    }

    public function getBankaccountsDatatable()
    {
        $columns = [
            "bank code" => "bank_code",
            "bank name" => "bank_name",
        ];
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";
    
        $query = $this->db->table('bankaccounts')
            ->select('bank_id, bank_name, bank_acc_no, bank_code, CONCAT(gl_accounts.account_code, " ", gl_accounts.account_name) AS gl_account, branch')
            ->join('gl_accounts', 'bankaccounts.gl_acc_id = gl_accounts.gl_acc_id');
    
        $countQuery = $this->db->table('bankaccounts')
            ->selectCount('bank_id')
            ->join('gl_accounts', 'bankaccounts.gl_acc_id = gl_accounts.gl_acc_id');
    
        $where = false;
    
        if (!empty($search)) {
            if (!$where) {
                $query->like('bank_code', $search)->orLike('bank_name', $search);
                $countQuery->like('bank_code', $search)->orLike('bank_name', $search);
                $where = true;
            } else {
                $query->groupStart()->like('bank_code', $search)->orLike('bank_name', $search)->groupEnd();
                $countQuery->groupStart()->like('bank_code', $search)->orLike('bank_name', $search)->groupEnd();
            }
        }
    
        if (!empty($ordercol) && !empty($orderby)) {
            $query->orderBy($ordercol, $orderby);
        }
    
        $query->limit($limit, $offset);
    
        $result = $query->get()->getResultArray();
        $m_result = [];
        $sno = $offset + 1;
    
        foreach ($result as $r) {
            $action = '<div class="dropdown tableAction">
                            <a type="button" class="dropBtn HoverA "><i class="fa fa-ellipsis-v"></i></a>
                            <div class="dropdown_container">
                                <ul class="BB_UL flex">
                                    <li><a href="#" data-ajax-url="' . url_to('erp.finance.ajaxfetchbankaccount', $r['bank_id']) . '" title="Edit" class="bg-success modalBtn"><i class="fa fa-pencil"></i> </a></li>
                                    <li><a href="' . url_to('erp.finance.bankaccountdelete', $r['bank_id']) . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
                                </ul>
                            </div>
                        </div>';
    
            array_push(
                $m_result,
                [
                    $r['bank_id'],
                    $sno,
                    $r['bank_name'],
                    $r['bank_acc_no'],
                    $r['bank_code'],
                    $r['gl_account'],
                    $r['branch'],
                    $action,
                ]
            );
            $sno++;
        }
     
        $response = [
            'total_rows' => $countQuery->get()->getRow(),
            'data' => $m_result,
        ];
    
        return $response;
    }

    public function getBankaccountsExport($type, $data)
    {
        $columns = [
            "bank code" => "bank_code",
            "bank name" => "bank_name",
        ];

        $builder = $this->db->table('bankaccounts');
        $builder->select('bank_name, bank_acc_no, bank_code, CONCAT(gl_accounts.account_code, " ", gl_accounts.account_name) AS gl_account, branch, address');
        $builder->join('gl_accounts', 'bankaccounts.gl_acc_id = gl_accounts.gl_acc_id', 'left');

        if (!empty($data['search'])) {
            $builder->groupStart();
            $builder->like('bank_code', $data['search']);
            $builder->orLike('bank_name', $data['search']);
            $builder->groupEnd();
        }

        if (!empty($data['ordercol']) && !empty($data['orderby'])) {
            $builder->orderBy($data['ordercol'], $data['orderby']);
        }

        if (!empty($data['limit'])) {
            $builder->limit($data['limit'], $data['offset']);
        }

        return $builder->get()->getResultArray();
    }

    
   

}