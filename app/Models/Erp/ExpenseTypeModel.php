<?php 

namespace App\Models\Erp;

use CodeIgniter\Model;
use PSpell\Config;

class ExpenseTypeModel extends Model{

    protected $table ="erp_expenses_categories";
    protected $primarykey ="id";
    protected $allowedFields =[
        'name',
        'description',
        'dateadded',
    ];

    protected $db;
    protected $logger;


    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->logger = \Config\Services::logger();   
    }

    public function fetch_expense_type(){
        return $this->builder()->select("id,name")->orderBy("dateadded","DESC")->get()->getResultArray();
    }

    public function addtype($data){
        if($this->builder()->insert($data)){
            return true;
        }else{
            return false;
        }
    }

}?>