<?php

namespace App\Controllers\Erp;

use App\Controllers\BaseController;

use App\Models\Erp\ContractModel;
use App\Models\Erp\ContractorPaymentsModel;
use App\Models\Erp\ExpensesModel;
use App\Models\Erp\FinanceModel;
use App\Libraries\Exporter;
use App\Libraries\Exporter\ExporterConst;
use App\Libraries\Importer;
use App\Libraries\Importer\AbstractImporter;
use App\Models\Erp\AccountBaseModel;
use App\Models\Erp\AccountGroupsModel;
use App\Models\Erp\AutoTransactionModel;
use App\Models\Erp\BankAccountsModel;
use App\Models\Erp\CurrenciesModel;
use App\Models\Erp\GLAccountsModel;
use App\Models\Erp\JournalEntryModel;
use App\Models\Erp\PaymentModesModel;
use App\Models\Erp\Procurement\ProcurementModel;
use App\Models\Erp\TaxesModel;

class FinanceController extends BaseController
{
    protected $data;
    
    protected $db;
    protected $session;
    protected $TaxesModel;
    protected $CurrenciesModel;
    protected $AccountGroupsModel;
    protected $AccountBaseModel;
    protected $GLAccountsModel;
    protected $BankAccountsModel;
    protected $JournalEntryModel;
    protected $AutoTransactionModel;
    protected $PaymentModesModel;

    public function __construct()
    {
        $this->TaxesModel = new TaxesModel();
        $this->CurrenciesModel = new CurrenciesModel();
        $this->AccountGroupsModel = new AccountGroupsModel();
        $this->AccountBaseModel = new AccountBaseModel();
        $this->GLAccountsModel = new GLAccountsModel();
        $this->BankAccountsModel = new BankAccountsModel();
        $this->JournalEntryModel = new JournalEntryModel();
        $this->AutoTransactionModel = new AutoTransactionModel();
        $this->PaymentModesModel = new PaymentModesModel();
        $this->session = \Config\Services::session();
        $this->db = \Config\Database::connect();
        helper(['form', 'erp']);
    }

    #Account Group Menu
    public function AccountGroup()
    {
        $financeModel = new FinanceModel();
        $data['accgroup_datatable_config'] = $financeModel->get_dtconfig_accgroups();
        $data['accountbase'] = $this->AccountBaseModel->get_account_bases();
        $data['page'] = "finance/accountgroup";
        $data['menu'] = "finance";
        $data['submenu'] = "accountgroup";
        return view("erp/index", $data);
    }

    public function AccountgroupAdd()
    {
        $financeModel = new FinanceModel();
        if ($this->request->getPost("group_name")) {
            $acc_id = $this->request->getPost("acc_group_id");
            $group_name = $this->request->getPost("group_name");
            $base_id = $this->request->getPost("base_id");
            $profit_loss = $this->request->getPost("profit_loss");
            $financeModel->insert_update_accgroup($acc_id, $group_name, $base_id, $profit_loss);
            return $this->response->redirect(url_to('erp.finance.accountgroup'));
        }
        $data['page'] = "finance/accountgroup";
        $data['menu'] = "finance";
        $data['submenu'] = "accountgroup";
        return view("erp/index", $data);
    }

    public function Ajax_accgroup_response()
    {
        $financeModel = new FinanceModel();
        $response = array();
        $columns = [
            "profit-loss" => "profit_loss",
            "group name" => "group_name"
        ];
        $limit = $this->request->getGet('limit');
        $offset = $this->request->getGet('offset');
        $search = $this->request->getGet('search') ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";
        $response = $financeModel->getAccgroupsDatatable($limit, $offset, $search, $orderby, $ordercol);
        return $this->response->setJSON($response);
    }

    public function Ajax_fetch_accgroup($accgroup_id)
    {
        $response = array();
        $db = \Config\Database::connect();
        $query = "SELECT acc_group_id,group_name,base_name,accountbase.base_id,profit_loss FROM account_groups JOIN accountbase ON account_groups.base_id=accountbase.base_id WHERE acc_group_id=$accgroup_id";
        $result = $db->query($query)->getRow();
        if (!empty($result)) {
            $response['error'] = 0;
            $response['data'] = $result;
        } else {
            $response['error'] = 1;
        }
        return $this->response->setJSON($response);
    }

    public function AccgroupDelete($accgroup_id)
    {
        $financeModel = new FinanceModel();
        $financeModel->deleteAccgroup($accgroup_id);
        return $this->response->redirect(url_to("erp.finance.accountgroup"));
    }

    public function AccgroupExport()
    {
        $financeModel = new FinanceModel();
        $type = $_GET['export'];
        $config = array();
        $config['title'] = "Account Groups";
        if ($type == "pdf") {
            $config['column'] = array("Group Name", "Base Name", "Profit \ Loss");
        } else {
            $config['column'] = array("Group Name", "Base Name", "Profit \ Loss");
            $config['column_data_type'] = array(ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING);
        }
        $config['data'] = $financeModel->getAccgroupsExport($type);
        if ($type == "pdf") {
            Exporter::export(ExporterConst::PDF, $config);
        } else if ($type == "excel") {
            Exporter::export(ExporterConst::EXCEL, $config);
        } else if ($type == "csv") {
            Exporter::export(ExporterConst::CSV, $config);
        }
    }


    #GL Accounts Menu
    public function GlAccounts()
    {
        $financeModel = new FinanceModel();
        $data['glaccount_datatable_config'] = $financeModel->getDtconfigGlaccounts();
        $data['cashflow'] = $financeModel->getCashFlow();
        $data['account_groups'] = $financeModel->getAccountGroups();
        $data['page'] = "finance/glaccount";
        $data['menu'] = "finance";
        $data['submenu'] = "glaccount";
        return view("erp/index", $data);
    }

    public function GlAccountsAdd()
    {
        $financeModel = new FinanceModel();
        if ($this->request->getPost("account_code")) {
            $data['gl_acc_id'] = $this->request->getPost('gl_acc_id');
            $data['period'] = date('Y-m-d', mktime(0, 0, 0, date('m'), 1, date('Y')));
            $data['acc_code'] = $this->request->getPost("account_code");
            $data['acc_name'] = $this->request->getPost("account_name");
            $data['acc_group'] = $this->request->getPost("account_group");
            $data['cashflow'] = $this->request->getPost("cash_flow");
            $data['ordernum'] = $this->request->getPost("order_num");
            $data['balance_fwd'] = $this->request->getPost("balance_fwd");
            $data['created_by'] = get_user_id();
            $financeModel->insertUpdateGlaccounts($data);
            return $this->response->redirect(url_to("erp.finance.glaccounts"));
        }
    }

    public function AjaxAccountcodeUnique()
    {
        $db = \Config\Database::connect();
        $gl_acc_id = $_GET['gl_acc_id'] ?? 0;
        $acc_code = $_GET['data'];
        if (is_numeric($acc_code)) {
            $query = "SELECT account_code FROM gl_accounts WHERE account_code=$acc_code ";
            if (!empty($gl_acc_id)) {
                $query = "SELECT account_code FROM gl_accounts WHERE account_code=$acc_code AND gl_acc_id<>$gl_acc_id";
            }
            $check = $db->query($query)->getRow();
            $response = array();
            if (empty($check)) {
                $response['valid'] = 1;
            } else {
                $response['valid'] = 0;
                $response['msg'] = "Account Code already exists";
            }
        } else {
            $response['valid'] = 0;
            $response['msg'] = "Account Code should be a number";
        }
        return $this->response->setJSON($response);
    }

    public function AjaxGlAccountResponse()
    {
        $financeModel = new FinanceModel();
        $response = array();
        $data['columns'] = [
            "account code" => "account_code",
            "account name" => "account_name",
            "group" => "account_groups.acc_group_id"
        ];
        $data['group'] = $this->request->getGet('group');
        $data['limit'] = $this->request->getGet('limit');
        $data['offset'] = $this->request->getGet('offset');
        $data['search'] = $this->request->getGet('search') ?? "";
        $data['orderby'] = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $data['ordercol'] = isset($_GET['ordercol']) ? $data['columns'][$_GET['ordercol']] : "";
        $response = $financeModel->getGlAccountsDatatable($data);
        return $this->response->setJSON($response);
    }

    public function AjaxFetchGlAccount($gl_acc_id)
    {
        $db = \Config\Database::connect();
        $response = array();
        $query = "SELECT gl_acc_id,account_code,account_name,acc_group_id,cash_flow,order_num FROM gl_accounts WHERE gl_acc_id=$gl_acc_id";
        $result = $db->query($query)->getRow();
        if (!empty($result)) {
            $response['error'] = 0;
            $response['data'] = $result;
        } else {
            $response['error'] = 1;
        }
        return $this->response->setJSON($response);
    }

    public function GlAccountDelete($gl_acc_id)
    {
        $financeModel = new FinanceModel();
        $financeModel->deleteGlAccount($gl_acc_id);
        return $this->response->redirect(url_to('erp.finance.glaccounts'));
    }

    public function getGlaccountsExport()
    {
        $financeModel = new FinanceModel();
        $type = $_GET['export'];
        $config = array();
        $config['title'] = "GL Accounts";
        if ($type == "pdf") {
            $config['column'] = array("Account Code", "Account Name", "Account Group", "Cashflow");
        } else {
            $config['column'] = array("Account Code", "Account Name", "Account Group", "Cashflow");
            $config['column_data_type'] = array(ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING);
        }
        $config['data'] = $financeModel->getGlaccountsExport($type);
        if ($type == "pdf") {
            Exporter::export(ExporterConst::PDF, $config);
        } else if ($type == "excel") {
            Exporter::export(ExporterConst::EXCEL, $config);
        } else if ($type == "csv") {
            Exporter::export(ExporterConst::CSV, $config);
        }
    }

    public function GlaccountImport()
    {
        $financeModel = new FinanceModel();
        $session = \Config\Services::session();
        $importer = Importer::init(AbstractImporter::GLACCOUNT, AbstractImporter::CSV);
        if (!empty($_FILES['csvfile']['name'])) {
            if (strtolower(pathinfo($_FILES['csvfile']['name'], PATHINFO_EXTENSION)) == "csv") {
                $path = get_tmp_path() . get_rand_str() . ".csv";
                if (move_uploaded_file($_FILES['csvfile']['tmp_name'], $path)) {

                    $data['acc_group_id'] = $this->request->getPost("account_group");
                    $data['cash_flow'] = $this->request->getPost("cash_flow");
                    $data['created_at'] = time();
                    $data['created_by'] = get_user_id();

                    $retval = $importer->import($path, $data);

                    $config['title'] = "GL Account Import";
                    $config['ref_link'] = "";
                    if ($retval == -1) {
                        $config['log_text'] = "[ GL Account failed to import ]";
                        $session->setFlashdata("op_error", "GL Account failed to import");
                        log_activity($config);
                    } else if ($retval == 0) {
                        $config['log_text'] = "[ GL Account partially imported ]";
                        $session->setFlashdata("op_success", "GL Account partially imported");
                        log_activity($config);
                        return $this->response->redirect(url_to("erp.finance.glaccounts"));
                    } else if ($retval == 1) {
                        $config['log_text'] = "[ GL Account successfully imported ]";
                        $session->setFlashdata("op_success", "GL Account successfully imported");
                        log_activity($config);
                        return $this->response->redirect(url_to("erp.finance.glaccounts"));
                    }
                } else {
                    $session->setFlashdata("op_error", "Internal Error while uploading file");
                }
            } else {
                $session->setFlashdata("op_error", "File should be in CSV format");
            }
            return $this->response->redirect(url_to("erp.finance.glaccountimport"));
        }
        $data['cashflow'] = $financeModel->getCashFlow();
        $data['account_groups'] = $financeModel->getAccountGroups();
        $data['columns'] = $importer->get_columns();
        $data['page'] = "finance/glaccountimport";
        $data['menu'] = "finance";
        $data['submenu'] = "glaccount";
        return view("erp/index", $data);
    }

    public function GlaccountImportTemplate()
    {
        $importer = Importer::init(AbstractImporter::GLACCOUNT, AbstractImporter::CSV);
        $importer->download_template();
    }

    #Bank Accounts
    public function BankAccounts()
    {
        $financeModel = new FinanceModel();
        $data['bankaccount_datatable_config'] = $financeModel->getDtconfigBankaccounts();
        $data['page'] = "finance/bankaccount";
        $data['menu'] = "finance";
        $data['submenu'] = "bankaccount";
        return view("erp/index", $data);
    }

    public function BankAccountsAdd()
    {
        $financeModel = new FinanceModel();
        if ($this->request->getPost("bank_name")) {
            $data['bank_id'] = $this->request->getPost("bank_id");
            $data['gl_acc_id'] = $this->request->getPost("gl_acc_id");
            $data['bank_name'] = $this->request->getPost("bank_name");
            $data['bank_acc_no'] = $this->request->getPost("bank_acc_no");
            $data['bank_code'] = $this->request->getPost("bank_code");
            $data['branch'] = $this->request->getPost("branch");
            $data['address'] = $this->request->getPost("address");
            $data['created_by'] = get_user_id();
            $financeModel->insertUpdateBankaccounts($data);
            return $this->response->redirect(url_to("erp.finance.bankaccounts"));
        }
        $data['page'] = "finance/bankaccount";
        return view('erp/index', $data);
    }

    public function ajaxBankaccountResponse()
    {
        $financeModel = new FinanceModel();
        $response = array();
        $response = $financeModel->getBankaccountsDatatable();
        return $this->response->setJSON($response);
    }

    public function ajaxFetchBankaccount($bank_id)
    {
        $db = \Config\Services::connect();
        $response = array();
        $query = "SELECT bank_id,bank_code,bank_acc_no,bank_name,gl_accounts.gl_acc_id,branch,address,CONCAT(account_code,' ',account_name) AS gl_account FROM bankaccounts JOIN gl_accounts ON bankaccounts.gl_acc_id=gl_accounts.gl_acc_id WHERE bank_id=$bank_id";
        $result = $this->db->query($query)->getRow();
        if (!empty($result)) {
            $response['error'] = 0;
            $response['data'] = $result;
        } else {
            $response['error'] = 1;
        }
        return $this->response->setJSON($response);
    }

    public function ajaxFetchGlAccounts()
    {
        $db = \Config\Database::connect();
        $search = $_GET['search'] ?? "";
        $response = array();
        $response['error'] = 1;
        if (!empty($search)) {
            $query = "SELECT gl_acc_id,account_code,account_name FROM gl_accounts WHERE ( account_name LIKE '%" . $search . "%' OR account_code LIKE '%" . $search . "%' ) LIMIT 6";
            $result = $db->query($query)->getResultArray();
            $m_result = array();
            foreach ($result as $r) {
                array_push($m_result, array(
                    "key" => $r['gl_acc_id'],
                    "value" => $r['account_code'] . ' ' . $r['account_name']
                ));
            }
            $response['error'] = 0;
            $response['data'] = $m_result;
        }
        return $this->response->setJSON($response);
    }

    public function bankaccountExport()
    {
        $type = $_GET['export'];
        $config = array();
        $config['title'] = "Bank Accounts";
        if ($type == "pdf") {
            $config['column'] = array("Bank Name", "Account No", "Bank Code", "GL Account", "Branch", "Address");
        } else {
            $config['column'] = array("Bank Name", "Account No", "Bank Code", "GL Account", "Branch", "Address");
            $config['column_data_type'] = array(ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING);
        }
        $data['limit'] = $this->request->getGet('limit');
        $data['offset'] = $this->request->getGet('offset');
        $data['search'] = $this->request->getGet('search') ?? "";
        $data['orderby'] = strtoupper($this->request->getGet('orderby') ?? "");
        $data['ordercol'] = $this->request->getGet('ordercol') ?? "";
        $data['ordercol'] = isset($columns[$data['ordercol']]) ? $columns[$data['ordercol']] : "";
        $financeModel = new FinanceModel();
        $config['data'] = $financeModel->getBankaccountsExport($type, $data);
        if ($type == "pdf") {
            Exporter::export(ExporterConst::PDF, $config);
        } else if ($type == "excel") {
            Exporter::export(ExporterConst::EXCEL, $config);
        } else if ($type == "csv") {
            Exporter::export(ExporterConst::CSV, $config);
        }
    }

    public function bankAccountDelete($bank_id)
    {
        $db = \Config\Database::connect();
        $session = \config\Services::session();
        $gl_acc_id = $this->db->table('bankaccounts')->select('gl_acc_id')->where('bank_id', $bank_id)->get()->getRow()->gl_acc_id;
        $result = $this->db->table('gl_accounts')->where('gl_acc_id', $gl_acc_id)->get()->getResultArray();
        $config['title'] = "Bank Delete";
        if (!empty($result)) {
            $config['log_text'] = "[ Bank failed to delete ]";
            $config['ref_link'] = "erp/finance/bankaccounts/";
            $session->setFlashdata('op_error', 'Bank account linked to another gl_account');
        } else {
            $deleted = $this->db->table('bankaccounts')->where('bank_id', $bank_id)->delete();
            if ($deleted) {
                $config['log_text'] = "[ Bank deleted successfully ]";
                $config['ref_link'] = "erp/finance/bankaccounts/";
                $session->setFlashdata('op_success', 'Bank account deleted');
            }
        }
        return $this->response->redirect(url_to('erp.finance.bankaccounts'));
    }


    //Tax
    public function tax()
    {
        $post = [
            'tax_name' => $this->request->getPost("tax_name"),
            'tax_percent' => $this->request->getPost("tax_percent"),
        ];
        $tax_id = $this->request->getPost("tax_id");
        if ($this->request->getPost("tax_name")) {
            $this->TaxesModel->insert_update_tax($tax_id, $post);
            return $this->response->redirect(url_to("erp.finance.tax"));
        }
        $this->data['tax_datatable_config'] = $this->TaxesModel->get_dtconfig_taxes();
        $this->data['page'] = "finance/tax";
        $this->data['menu'] = "finance";
        $this->data['submenu'] = "tax";
        return view("erp/index", $this->data);
    }

    public function ajaxTaxResponse()
    {
        $response = array();
        $response = $this->TaxesModel->get_taxes_datatable();
        return $this->response->setJSON($response);
    }

    //NOT IMPLEMENTED
    public function taxdelete($tax_id)
    {
        $this->TaxesModel->deleteTax($tax_id);
        return $this->response->redirect(url_to("erp.finance.tax"));
    }

    public function ajaxFetchTax($tax_id)
    {
        $response = array();
        $query = "SELECT tax_id,tax_name,percent FROM taxes WHERE tax_id=$tax_id";
        $result = $this->db->query($query)->getRow();
        if (!empty($result)) {
            $response['error'] = 0;
            $response['data'] = $result;
        } else {
            $response['error'] = 1;
        }
        return $this->response->setJSON($response);
    }

    public function taxExport()
    {
        $type = $_GET['export'];
        $config = array();
        $config['title'] = "Taxes";
        if ($type == "pdf") {
            $config['column'] = array("Tax Name", "Tax Percent");
        } else {
            $config['column'] = array("Tax Name", "Tax Percent");
            $config['column_data_type'] = array(ExporterConst::E_STRING, ExporterConst::E_INTEGER);
        }
        $config['data'] = $this->TaxesModel->get_taxes_export($type);
        if ($type == "pdf") {
            Exporter::export(ExporterConst::PDF, $config);
        } else if ($type == "excel") {
            Exporter::export(ExporterConst::EXCEL, $config);
        } else if ($type == "csv") {
            Exporter::export(ExporterConst::CSV, $config);
        }
    }

    public function currency()
    {
        $post = [
            'iso_code' => $this->request->getPost("iso_code"),
            'symbol' => $this->request->getPost("symbol"),
            'decimal_sep' => $this->request->getPost("decimal_sep"),
            'thousand_sep' => $this->request->getPost("thousand_sep"),
            'place' => $this->request->getPost("place"),
        ];
        $currency_id = $this->request->getPost("currency_id");
        if ($this->request->getPost("iso_code")) {
            $this->CurrenciesModel->insert_update_currency($currency_id, $post);
            return $this->response->redirect(url_to("erp.finance.currency"));
        }
        $this->data['currency_datatable_config'] = $this->CurrenciesModel->get_dtconfig_currencies();
        $this->data['page'] = "finance/currency";
        $this->data['menu'] = "finance";
        $this->data['submenu'] = "currency";
        return view("erp/index", $this->data);
    }

    public function ajaxCurrencyResponse()
    {
        $response = array();
        $response = $this->CurrenciesModel->get_currencies_datatable();
        return $this->response->setJSON($response);
    }

    public function ajaxFetchCurrency($currency_id)
    {
        $response = array();
        $query = "SELECT currency_id,iso_code,symbol,decimal_sep,thousand_sep,place FROM currencies WHERE currency_id=$currency_id";
        $result = $this->db->query($query)->getRow();
        if (!empty($result)) {
            $response['error'] = 0;
            $response['data'] = $result;
        } else {
            $response['error'] = 1;
        }
        return $this->response->setJSON($response);
    }

    // IMPLEMENTED Tamil
    public function currencybaseset($currency_id)
    {

        $post = [
            'iso_code' => $this->request->getPost("iso_code"),
            'symbol' => $this->request->getPost("symbol"),
        ];
        $currency_id = $this->request->getPost("currency_id");
        if ($this->request->getPost("iso_code")) {
            $this->CurrenciesModel->insert_update_currency($currency_id, $post);
            return $this->response->redirect(url_to("erp.finance.currency"));
        }
        $this->data['currency_datatable_config'] = $this->CurrenciesModel->get_dtconfig_currencies();
        $this->data['page'] = "finance/basecurrency";
        $this->data['menu'] = "finance";
        $this->data['submenu'] = "currency";
        return view("erp/index", $this->data);


    }

    // Currency delete
    public function currencydelete($currency_id)
    {
        $this->CurrenciesModel->deleteCurrency($currency_id);
        return $this->response->redirect(url_to("erp.finance.currency"));
    }

    public function currencyExport()
    {
        $type = $_GET['export'];
        $config = array();
        $config['title'] = "Currencies";
        if ($type == "pdf") {
            $config['column'] = array("ISO Code", "Symbol", "Decimal Separator", "Thousand Separator", "Place", "Base Currency");
        } else {
            $config['column'] = array("ISO Code", "Symbol", "Decimal Separator", "Thousand Separator", "Place", "Base Currency");
            $config['column_data_type'] = array(ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING);
        }
        $config['data'] = $this->CurrenciesModel->get_currencies_export($type);
        if ($type == "pdf") {
            Exporter::export(ExporterConst::PDF, $config);
        } else if ($type == "excel") {
            Exporter::export(ExporterConst::EXCEL, $config);
        } else if ($type == "csv") {
            Exporter::export(ExporterConst::CSV, $config);
        }
    }

    /////////////////////////////////////////////
    public function accountgroups()
    {
        if ($this->request->getPost("group_name")) {
            $this->AccountGroupsModel->insert_update_accgroup();
            return $this->response->redirect(url_to("erp.finance.accountgroups"));
        }
        $this->data['accgroup_datatable_config'] = $this->AccountGroupsModel->get_dtconfig_accgroups();
        $this->data['accountbase'] = $this->AccountBaseModel->get_account_bases();
        $this->data['page'] = "finance/accountgroup";
        $this->data['menu'] = "finance";
        $this->data['submenu'] = "accountgroup";
        return view("erp/index", $this->data);
    }


    public function accgroup_export()
    {
        $type = $_GET['export'];
        $config = array();
        $config['title'] = "Account Groups";
        if ($type == "pdf") {
            $config['column'] = array("Group Name", "Base Name", "Profit \ Loss");
        } else {
            $config['column'] = array("Group Name", "Base Name", "Profit \ Loss");
            $config['column_data_type'] = array(ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING);
        }
        $config['data'] = $this->AccountGroupsModel->get_accgroups_export($type);
        if ($type == "pdf") {
            Exporter::export(ExporterConst::PDF, $config);
        } else if ($type == "excel") {
            Exporter::export(ExporterConst::EXCEL, $config);
        } else if ($type == "csv") {
            Exporter::export(ExporterConst::CSV, $config);
        }
    }


    public function glaccount_import()
    {
        $importer = Importer::init(AbstractImporter::GLACCOUNT, AbstractImporter::CSV);
        if (!empty($_FILES['csvfile']['name'])) {
            if (strtolower(pathinfo($_FILES['csvfile']['name'], PATHINFO_EXTENSION)) == "csv") {
                $path = get_tmp_path() . get_rand_str() . ".csv";
                if (move_uploaded_file($_FILES['csvfile']['tmp_name'], $path)) {

                    $data['acc_group_id'] = $this->request->getPost("account_group");
                    $data['cash_flow'] = $this->request->getPost("cash_flow");
                    $data['created_at'] = time();
                    $data['created_by'] = get_user_id();

                    $retval = $importer->import($path, $data);

                    $config['title'] = "GL Account Import";
                    $config['ref_link'] = "";
                    if ($retval == -1) {
                        $config['log_text'] = "[ GL Account failed to import ]";
                        $this->session->setFlashdata("op_error", "GL Account failed to import");
                        log_activity($config);
                    } else if ($retval == 0) {
                        $config['log_text'] = "[ GL Account partially imported ]";
                        $this->session->setFlashdata("op_success", "GL Account partially imported");
                        log_activity($config);
                        return $this->response->redirect(url_to("erp.finance.glaccounts"));
                    } else if ($retval == 1) {
                        $config['log_text'] = "[ GL Account successfully imported ]";
                        $this->session->setFlashdata("op_success", "GL Account successfully imported");
                        log_activity($config);
                        return $this->response->redirect(url_to("erp.finance.glaccounts"));
                    }
                } else {
                    $this->session->setFlashdata("op_error", "Internal Error while uploading file");
                }
            } else {
                $this->session->setFlashdata("op_error", "File should be in CSV format");
            }
            return $this->response->redirect(url_to("erp.finance.glaccount_import"));
        }
        $this->data['cashflow'] = $this->AccountBaseModel->get_cash_flow();
        $this->data['account_groups'] = $this->AccountGroupsModel->get_account_groups();
        $this->data['columns'] = $importer->get_columns();
        $this->data['page'] = "finance/glaccountimport";
        $this->data['menu'] = "finance";
        $this->data['submenu'] = "glaccount";
        return view("erp/index", $this->data);
    }


    public function ajax_accountcode_unique()
    {
        $gl_acc_id = $_GET['gl_acc_id'] ?? 0;
        $acc_code = $_GET['data'];
        if (is_numeric($acc_code)) {
            $query = "SELECT account_code FROM gl_accounts WHERE account_code=$acc_code ";
            if (!empty($gl_acc_id)) {
                $query = "SELECT account_code FROM gl_accounts WHERE account_code=$acc_code AND gl_acc_id<>$gl_acc_id";
            }
            $check = $this->db->query($query)->getRow();
            $response = array();
            if (empty($check)) {
                $response['valid'] = 1;
            } else {
                $response['valid'] = 0;
                $response['msg'] = "Account Code already exists";
            }
        } else {
            $response['valid'] = 0;
            $response['msg'] = "Account Code should be a number";
        }
        return $this->response->setJSON($response);
    }

    public function ajax_glaccount_response()
    {
        $response = array();
        $response = $this->GLAccountsModel->get_glaccounts_datatable();
        return $this->response->setJSON($response);
    }

    public function ajax_fetch_glaccount($gl_acc_id)
    {
        $response = array();
        $query = "SELECT gl_acc_id,account_code,account_name,acc_group_id,cash_flow,order_num FROM gl_accounts WHERE gl_acc_id=$gl_acc_id";
        $result = $this->db->query($query)->getRow();
        if (!empty($result)) {
            $response['error'] = 0;
            $response['data'] = $result;
        } else {
            $response['error'] = 1;
        }
        return $this->response->setJSON($response);
    }

    public function glaccount_export()
    {
        $type = $_GET['export'];
        $config = array();
        $config['title'] = "GL Accounts";
        if ($type == "pdf") {
            $config['column'] = array("Account Code", "Account Name", "Account Group", "Cashflow");
        } else {
            $config['column'] = array("Account Code", "Account Name", "Account Group", "Cashflow");
            $config['column_data_type'] = array(ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING);
        }
        $config['data'] = $this->GLAccountsModel->get_glaccounts_export($type);
        if ($type == "pdf") {
            Exporter::export(ExporterConst::PDF, $config);
        } else if ($type == "excel") {
            Exporter::export(ExporterConst::EXCEL, $config);
        } else if ($type == "csv") {
            Exporter::export(ExporterConst::CSV, $config);
        }
    }

    public function ajax_bankaccount_response()
    {
        $response = array();
        $response = $this->BankAccountsModel->get_bankaccounts_datatable();
        return $this->response->setJSON($response);
    }

    public function ajax_fetch_bankaccount($bank_id)
    {
        $response = array();
        $query = "SELECT bank_id,bank_code,bank_acc_no,bank_name,gl_accounts.gl_acc_id,branch,address,CONCAT(account_code,' ',account_name) AS gl_account FROM bankaccounts JOIN gl_accounts ON bankaccounts.gl_acc_id=gl_accounts.gl_acc_id WHERE bank_id=$bank_id";
        $result = $this->db->query($query)->getRow();
        if (!empty($result)) {
            $response['error'] = 0;
            $response['data'] = $result;
        } else {
            $response['error'] = 1;
        }
        return $this->response->setJSON($response);
    }


    public function bankaccount_export()
    {
        $type = $_GET['export'];
        $config = array();
        $config['title'] = "Bank Accounts";
        if ($type == "pdf") {
            $config['column'] = array("Bank Name", "Account No", "Bank Code", "GL Account", "Branch", "Address");
        } else {
            $config['column'] = array("Bank Name", "Account No", "Bank Code", "GL Account", "Branch", "Address");
            $config['column_data_type'] = array(ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING);
        }
        $config['data'] = $this->BankAccountsModel->get_bankaccounts_export($type);
        if ($type == "pdf") {
            Exporter::export(ExporterConst::PDF, $config);
        } else if ($type == "excel") {
            Exporter::export(ExporterConst::EXCEL, $config);
        } else if ($type == "csv") {
            Exporter::export(ExporterConst::CSV, $config);
        }
    }

    public function journalentry()
    {
        $this->data['accountbase'] = $this->AccountBaseModel->get_account_bases();
        $this->data['entry_datatable_config'] = $this->JournalEntryModel->get_dtconfig_journalentry();
        $this->data['page'] = "finance/journalentry";
        $this->data['menu'] = "finance";
        $this->data['submenu'] = "journalentry";
        return view("erp/index", $this->data);
    }

    public function journalentryadd()
    {
        $post = [
            'transaction_date' => $this->request->getPost("transaction_date"),
            'journal_type' => $this->request->getPost("journal_type"),
        ];
        if ($this->request->getPost("transaction_date")) {
            $this->JournalEntryModel->insert_journal_entry($post);
            return $this->response->redirect(url_to("erp.finance.journalentry"));
        }
        $this->data['page'] = "finance/journalentryadd";
        $this->data['menu'] = "finance";
        $this->data['submenu'] = "journalentry";
        return view("erp/index", $this->data);
    }

    public function ajaxJournalentryResponse()
    {
        $response = array();
        $response = $this->JournalEntryModel->get_journalentry_datatable();
        return $this->response->setJSON($response);
    }

    //NOT IMPLEMENTED
    public function journalentrydelete($journal_id)
    {
        $this->JournalEntryModel->deleteJournalentry($journal_id);
        return $this->response->redirect(url_to("erp.finance.journalentry"));
    }

    public function journalentrypost($journal_id)
    {
        $this->JournalEntryModel->post_journalentry($journal_id);
        return $this->response->redirect(url_to("erp.finance.journalentry"));
    }

    public function journalentryExport()
    {
        $type = $_GET['export'];
        $config = array();
        $config['title'] = "Journal Entry";
        if ($type == "pdf") {
            $config['column'] = array("GL Account", "Transaction Type", "Amount", "Journal Type", "Transaction Date", "Posted");
        } else {
            $config['column'] = array("GL Account", "Transaction Type", "Amount", "Narration", "Journal Type", "Transaction Date", "Posted");
            $config['column_data_type'] = array(ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING);
        }
        $config['data'] = $this->JournalEntryModel->get_journalentry_export($type);
        if ($type == "pdf") {
            Exporter::export(ExporterConst::PDF, $config);
        } else if ($type == "excel") {
            Exporter::export(ExporterConst::EXCEL, $config);
        } else if ($type == "csv") {
            Exporter::export(ExporterConst::CSV, $config);
        }
    }

    public function automation()
    {
        $post = [
            'trans_id' => $this->request->getPost("trans_id"),
            'debit_gl_account' => $this->request->getPost("debit_gl_account"),
            'credit_gl_account' => $this->request->getPost("credit_gl_account"),
            'auto_posting' => $this->request->getPost("auto_posting") ?? 0,
        ];
        $autotrans_id = $this->request->getPost("autotrans_id");
        if ($this->request->getPost("trans_id")) {
            $this->AutoTransactionModel->insert_update_automation($autotrans_id, $post);
            return $this->response->redirect(url_to("erp.finance.automation"));
        }
        $this->data['automation_datatable_config'] = $this->AutoTransactionModel->get_dtconfig_automation();
        $this->data['autotranslist'] = $this->AutoTransactionModel->get_autotranslist();
        $this->data['page'] = "finance/automation";
        $this->data['menu'] = "finance";
        $this->data['submenu'] = "automation";
        return view("erp/index", $this->data);
    }

    public function ajaxAutomationResponse()
    {
        $response = array();
        $response = $this->AutoTransactionModel->get_automation_datatable();
        return $this->response->setJSON($response);
    }

    public function ajaxFetchAutomation($autotrans_id)
    {
        $response = array();
        $query = "SELECT autotrans_id,trans_id,CONCAT(gl1.account_code,' ',gl1.account_name) AS debit_account,CONCAT(gl2.account_code,' ',gl2.account_name) AS credit_account,auto_posting,active,debit_gl_account,credit_gl_account FROM auto_transaction JOIN gl_accounts gl1 ON auto_transaction.debit_gl_account=gl1.gl_acc_id JOIN gl_accounts gl2 ON auto_transaction.credit_gl_account=gl2.gl_acc_id ";
        $result = $this->db->query($query)->getRow();
        if (!empty($result)) {
            $response['error'] = 0;
            $response['data'] = $result;
        } else {
            $response['error'] = 1;
        }
        return $this->response->setJSON($response);
    }

    public function ajaxAutomationActive($autotrans_id)
    {
        $response = array();
        $query = "UPDATE auto_transaction SET active=active^1 WHERE autotrans_id=$autotrans_id";
        $this->db->query($query);
        if ($this->db->affectedRows() > 0) {
            $response['error'] = 0;
        } else {
            $response['error'] = 1;
        }
        return $this->response->setJSON($response);
    }

    public function automationdelete($autotrans_id)
    {
        $this->AutoTransactionModel->delete_automation($autotrans_id);
        return $this->response->redirect(url_to("erp.finance.automation"));
    }

    public function automationExport()
    {
        $type = $_GET['export'];
        $config = array();
        $config['title'] = "Automation Joural Entry";
        if ($type == "pdf") {
            $config['column'] = array("Transaction Name", "Debit GL Account", "Credit GL Account", "Auto Posting", "Active");
        } else {
            $config['column'] = array("Transaction Name", "Debit GL Account", "Credit GL Account", "Auto Posting", "Active");
            $config['column_data_type'] = array(ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING);
        }
        $config['data'] = $this->AutoTransactionModel->get_automation_export($type);
        if ($type == "pdf") {
            Exporter::export(ExporterConst::PDF, $config);
        } else if ($type == "excel") {
            Exporter::export(ExporterConst::EXCEL, $config);
        } else if ($type == "csv") {
            Exporter::export(ExporterConst::CSV, $config);
        }
    }

    public function paymentmode()
    {
        $post = [
            'mode_name' => $this->request->getPost("mode_name"),
            'description' => $this->request->getPost("description"),
        ];
        $payment_id = $this->request->getPost("payment_id") ?? 0;
        if ($this->request->getPost("mode_name")) {
            $this->PaymentModesModel->insert_update_paymentmode($payment_id, $post);
            return $this->response->redirect(url_to("erp.finance.paymentmode"));
        }
        $this->data['paymentmode_datatable_config'] = $this->PaymentModesModel->get_dtconfig_paymentmode();
        $this->data['page'] = "finance/paymentmode";
        $this->data['menu'] = "finance";
        $this->data['submenu'] = "paymentmode";
        return view("erp/index", $this->data);
    }

    public function ajaxPaymentmodeResponse()
    {
        $response = array();
        $response = $this->PaymentModesModel->get_paymentmode_datatable();
        return $this->response->setJSON($response);
    }

    public function ajaxPaymentmodeActive($payment_id)
    {
        $response = array();
        $query = "UPDATE payment_modes SET active=active^1 WHERE payment_id=$payment_id";
        $this->db->query($query);
        if ($this->db->affectedRows() > 0) {
            $response['error'] = 0;
        } else {
            $response['error'] = 1;
        }
        return $this->response->setJSON($response);
    }

    public function ajaxFetchPaymentmode($payment_id)
    {
        $response = array();
        $query = "SELECT payment_id,name,description FROM payment_modes WHERE payment_id=$payment_id";
        $result = $this->db->query($query)->getRow();
        if (!empty($result)) {
            $response['error'] = 0;
            $response['data'] = $result;
        } else {
            $response['error'] = 1;
        }
        return $this->response->setJSON($response);
    }

    //NOT IMPLEMENTED
    public function paymentmodedelete($payment_id)
    {
        $this->PaymentModesModel->deletePaymentmode($payment_id);
        return $this->response->redirect(url_to("erp.finance.paymentmode"));
    }

    public function paymentmodeExport()
    {
        $type = $_GET['export'];
        $config = array();
        $config['title'] = "Payment Modes";
        if ($type == "pdf") {
            $config['column'] = array("Payment Mode Name", "Description", "Active");
        } else {
            $config['column'] = array("Payment Mode Name", "Description", "Active");
            $config['column_data_type'] = array(ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING);
        }
        $config['data'] = $this->PaymentModesModel->get_paymentmode_export($type);
        if ($type == "pdf") {
            Exporter::export(ExporterConst::PDF, $config);
        } else if ($type == "excel") {
            Exporter::export(ExporterConst::EXCEL, $config);
        } else if ($type == "csv") {
            Exporter::export(ExporterConst::CSV, $config);
        }
    }

    public function financeReport()
    {
        $expenc_m = new ExpensesModel();
        $payment_m = new PaymentModesModel();
        $contract_payment_m = new ContractModel();
        $procurement_m = new ProcurementModel();
        $expences = $expenc_m->get_expence_data_for_finance();
        $invoicePayments = $payment_m->get_payment_data_for_finance();
        $contractPayments = $contract_payment_m->get_contractor_data_for_finance();
        $procurementExpences = $procurement_m->get_procurement_data_for_finance();
        $monthly_report = [];
        for ($i = 1; $i <= 12; $i++) {
            $match_data = [];

            $found_expences = false;
            foreach ($expences as $month) {
                if ($month['month_number'] == $i) {
                    $found_expences = true;
                    $match_data = $month;
                    break;
                }
            }
            if ($found_expences) {
                $monthly_report[$i]['Expence'] = (int) $match_data['total_expenditure'];
            } else {
                $monthly_report[$i]['Expence'] = 0;
            }

            $found_payment = false;
            foreach ($invoicePayments as $month) {
                if ($month['month_number'] == $i) {
                    $found_payment = true;
                    $match_data = $month;
                    break;
                }
            }
            if ($found_payment) {
                $monthly_report[$i]['Income'] = $match_data['total_expenditure'];
            } else {
                $monthly_report[$i]['Income'] = 0;
            }

            $found_contract = false;
            foreach ($contractPayments as $month) {
                if ($month['month_number'] == $i) {
                    $found_contract = true;
                    $match_data = $month;
                    break;
                }
            }
            if ($found_contract) {
                $monthly_report[$i]['Income'] = $monthly_report[$i]['Income'] + $match_data['total_expenditure'];
            }


            $found_procurement = false;
            foreach ($procurementExpences as $month) {
                if ($month['month_number'] == $i) {
                    $found_procurement = true;
                    $match_data = $month;
                    break;
                }
            }
            if ($found_procurement) {
                $monthly_report[$i]['Expence'] = $monthly_report[$i]['Expence'] + $match_data['total_expenditure'];
            }


        }


        $this->data['monthly_income_expence'] = $monthly_report;
        $this->data['journalEntryCount'] = $this->JournalEntryModel->journalEntryCount();
        $this->data['journalEntryIdCount'] = $this->JournalEntryModel->journalEntryIdCount();
        $this->logger->error($this->data['journalEntryIdCount']);
        $this->data['page'] = "finance/financereport";
        $this->data['menu'] = "finance";
        $this->data['submenu'] = "financereport";
        return view("erp/index", $this->data);
    }
}
