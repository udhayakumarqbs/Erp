<?php

namespace App\Controllers\Erp;

use App\Controllers\BaseController;
use App\Models\Erp\AssetModel;
use App\Models\Erp\MRP\BOMModel;
use App\Models\Erp\MRP\BOMItemsModel;
use App\Models\Erp\MRP\BomscrapitemsModel;
use App\Models\Erp\MRP\WorkstationtypeModel;
use App\Models\Erp\MRP\WorkstationModel;
use App\Models\ERP\MRP\ForecastModel;
use App\Models\Erp\MRP\PlanningModel;
use App\Models\Erp\Warehouse\CurrentStockModel;
use App\Models\Erp\FinishedGoodsModel;
use App\Models\Erp\Warehouse\WarehouseModel;
use App\Models\Erp\MRP\OperationModel;
use App\Models\Erp\MRP\BomOperationModel;
use App\Models\Erp\MRP\ScrapModel;
use App\Models\Erp\MRP\CostingModel;
use App\Libraries\Exporter;
use App\Libraries\Exporter\ExporterConst;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Config\Session;
use PSpell\Config;

class MRPController extends BaseController
{
    protected $data;
    protected $db;
    protected $session;
    protected $ForecastModel;
    protected $CurrentStockModel;
    protected $FinishedGoodsModel;
    protected $WarehouseModel;
    protected $PlanningModel;
    protected $BomModel;
    protected $BOMItemsModel;
    protected $BomOperationModel;
    protected $BomscrapitemsModel;
    protected $WorkstationtypeModel;
    protected $WorkstationModel;

    protected $scrapModel;
    protected $costingModel;

    protected $OperationModel;
    protected $logger;

    private $planning_status = array(
        0 => "Not Started",
        1 => "Ongoing",
        2 => "Complete",
        3 => "Cancelled",
    );

    public function __construct()
    {
        $this->ForecastModel = new ForecastModel();
        $this->CurrentStockModel = new CurrentStockModel();
        $this->PlanningModel = new PlanningModel();
        $this->FinishedGoodsModel = new FinishedGoodsModel();
        $this->BomModel = new BOMModel();
        $this->OperationModel = new OperationModel();
        $this->BOMItemsModel = new BOMItemsModel();
        $this->BomscrapitemsModel = new BomscrapitemsModel();
        $this->WorkstationtypeModel = new WorkstationtypeModel();
        $this->WorkstationModel = new WorkstationModel();
        $this->scrapModel = new ScrapModel();
        $this->costingModel = new CostingModel();
        $this->BomOperationModel = new BomOperationModel();

        $this->WarehouseModel = new WarehouseModel();
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
        $this->logger = \Config\Services::logger();
        helper(['erp', 'form']);
    }
    public function dashboard()
    {
        // Getting the count for each status
        $not_started_count = $this->PlanningModel->get_count_status(0);
        $on_going_count = $this->PlanningModel->get_count_status(1);
        $complete_count = $this->PlanningModel->get_count_status(2);
        $cancelled_count = $this->PlanningModel->get_count_status(3);

        $data['status_count'] = [
            'not_started' => $not_started_count['status_count'],
            'ongoing' => $on_going_count['status_count'],
            'complete' => $complete_count['status_count'],
            'cancelled' => $cancelled_count['status_count']
        ];

        $data['year_of_production'] = $this->PlanningModel->year_base_production();



        $data['page'] = "mrp/dashboard";
        $data['menu'] = "mrp";
        $data['submenu'] = "dashboard";
        return view("erp/index", $data);
    }

    public function yearlyStockAjax()
    {
        $year = $this->request->getPost('year');
        $data = $this->PlanningModel->year_base_production($year);

        $structuredData = [];

        if ($data) {
            foreach ($data as $rec) {
                $year = $rec['start_date'];
                $year = date('Y', strtotime($year));
                $productName = $rec['finished_good_name'];
                $stock = $rec['stock'];

                if (!isset($structuredData[$year])) {
                    $structuredData[$year] = [];
                }

                $structuredData[$year][] = [
                    'product' => $productName,
                    'stock' => $stock
                ];
            }
        }

        return $this->response->setJSON($structuredData ?? []);
    }


    public function Forecasting()
    {
        $logger = \Config\Services::logger();

        if (isset($_POST['start_forecast'])) {
            if (!empty($_POST['related_to']) && !empty($_POST['related_id']) && !empty($_POST['fromDate']) && !empty($_POST['toDate'])) {

                // this python files writable folder inside there
                $forecastpy = 'mrpforecast.py';
                $dataForcast = $this->ForecastModel->GetDataWithTimestamp();
                foreach ($dataForcast as &$dataPoint) {
                    $dataPoint['timestamp'] = date('Y-m-d H:i:s', strtotime($dataPoint['timestamp']));
                }

                $dataForcastJson = json_encode($dataForcast);

                // $tempPath = get_tmp_path();
                $tempFile = tempnam(get_tmp_path(), 'to_forecast_data_');
                // var_dump($tempFile);
                // echo "<br>";
                file_put_contents($tempFile, $dataForcastJson);

                $escapeDataForcast = escapeshellarg($tempFile);
                chdir(WRITEPATH . 'pyscripts/');
                $pythonPath = getenv('PYTHONPATH');
                $output = exec("$pythonPath $forecastpy $escapeDataForcast", $result);

                // var_dump($dataForcastJson);
                // var_dump($output);
                // var_dump($result);
                // exit;
                unlink($tempFile);
                if (isset($result[0]) && !empty($result[0])) {
                    $forecastedRaw = json_decode($result[0], true);

                    // Logical Calculation Part
                    $current_stocks = $this->ForecastModel->GetCurrentStock($_POST['related_to'], $_POST['related_id']);
                    $timestamps = $forecastedRaw['timestamps'] ?? 0;
                    $forecasted = $forecastedRaw['fitted_values'] ?? 0;
                    $requirements = [];
                    $onhandstocks = [];
                    $scheduled = [];
                    // timestamps foeach to get the OnHandedStocks

                    if (isset($timestamps) && $timestamps != 0) {
                        foreach ($timestamps as $timestamp) {

                            $futureQty = $this->ForecastModel->GetFutureStocks($timestamp);
                            $calculatedValue = $futureQty + $current_stocks;
                            $onhandstocks[] = $calculatedValue;
                            $logger->error($current_stocks);
                        }

                        // requirements
                        foreach ($timestamps as $timestamp) {
                            $newTimestamp = date("Y-m-d H:i:s", strtotime($timestamp . " +1 year"));
                            $logger->error($newTimestamp);
                            $requiremntQty = $this->ForecastModel->GetRequiredQty($newTimestamp);
                            $requirements[] = $requiremntQty;
                        }
                    }



                    $i = 0;
                    foreach ($onhandstocks as $stock) {

                        $scheduled[] = round($forecasted[$i] - $stock);
                        $i++;
                    }

                    $forecastedTable = [];




                    $index = 0;
                    if (isset($timestamps) && $timestamps != 0) {
                        foreach ($timestamps as $timestamp) {
                            $newTimestamp = date("Y-m-d", strtotime($timestamp . " +1 year"));
                            array_push(
                                $forecastedTable,
                                array(
                                    '<tr>',
                                    // '<td></td>',
                                    '<td>' . ($index + 1) . '</td>',
                                    '<td>' . $newTimestamp . '</td>',
                                    '<td>' . $onhandstocks[$index] . '</td>',
                                    '<td>' . $requirements[$index] . '</td>',
                                    '<td>' . round($forecasted[$index]) . '</td>',
                                    '<td>' . $scheduled[$index] . '</td>',
                                    '</tr>'
                                )
                            );

                            $index++;
                        }
                    }

                    $data['forcasted_table'] = $forecastedTable ?? "There Is an Error";
                    $data['result'] = ' ' . $_POST['related_to'];


                    // Data Preparation for the Export
                    $exportTable = [];

                    $exportI = 0;
                    if (isset($timestamps) && $timestamps != 0) {
                        foreach ($timestamps as $timestamp) {
                            $newTimestamp = date("Y-m-d", strtotime($timestamp . " +1 year"));
                            $exportTable[] = array(
                                $exportI + 1,
                                $newTimestamp,
                                $onhandstocks[$exportI],
                                $requirements[$exportI],
                                round($forecasted[$exportI]),
                                $scheduled[$exportI],
                            );

                            $exportI++;
                        }
                    }

                    $this->WriteExcel($exportTable);
                } else {
                    session()->setFlashdata("op_error", "There is an Error Try Again Later");
                }
            } else {
                session()->setFlashdata("op_error", "Invalid Inputs");
            }
        }

        $data['product_links'] = $this->CurrentStockModel->get_product_links();
        $data['product_types'] = $this->ForecastModel->GetProductType();
        $data['page'] = "mrp/forecasting";
        $data['menu'] = "mrp";
        $data['title'] = 'Forecasting Stocks and Demands';
        $data['submenu'] = "forecasting";
        $data['forecast_datatable_config'] = $this->ForecastModel->get_dtconfig_forecast();
        $data['min_year'] = date('Y-1') . "-01-01";
        $data['max_year'] = date('Y') . "-12-31";
        return view('erp/index', $data);
    }

    public function WriteExcel($exportTable)
    {
        // Create a new Spreadsheet object
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Add column headers
        $sheet->fromArray(['SNO', 'Timestamp', 'On Hand Stocks', 'Requirements', 'Forecasted', 'To Be Scheduled'], 'A1');

        // Add data to the sheet
        $sheet->fromArray($exportTable, null, 'A2');

        // Save the spreadsheet to a file
        $excelFilePath = get_tmp_path() . '/forecast/' . 'forecasting_' . date('Y-m-d_H-i-s') . '.xlsx';
        $writer = new Xlsx($spreadsheet);
        // $writer->save($excelFilePath);

        session()->set('forecast_file', $excelFilePath);
        session()->setFlashdata('op_success', 'Forecasting Completed! File Ready to Export:)');
    }

    public function ForecastExportasExcel()
    {
        $excelFilePath = session()->get('forecast_file');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="forecast_data.xlsx"');
        readfile($excelFilePath);
    }

    public function planningSchedule()
    {
        $data['planning_status'] = $this->PlanningModel->get_planning_status();
        $data['datatable_config'] = $this->PlanningModel->get_dtconfig_planning();
        $data['page'] = "mrp/planning";
        $data['menu'] = "mrp";
        $data['submenu'] = "planning";
        return view("erp/index", $data);
    }

    public function ajaxPlanningResponse()
    {
        $response = array();
        $response = $this->PlanningModel->get_planning_datatable();
        return $this->response->setJSON($response);
    }

    //come
    public function productPlanningexport()
    {
        $type = $_GET["export"];

        $config["title"] = "Product Planning List";
        if ($type == "pdf") {
            $config["column"] = array("Sno", "Product", "Start Date", "End Date", "Finished Date ", "Stock", "status");

        } else {
            $config["column"] = array("Sno", "Product", "Start Date", "End Date", "Finished Date ", "Stock", "status");
            $config["column_data_type"] = array(ExporterConst::E_INTEGER, ExporterConst::E_STRING, ExporterConst::E_DATE, ExporterConst::E_DATE, ExporterConst::E_DATE, ExporterConst::E_INTEGER, ExporterConst::E_STRING);
        }

        $config["data"] = $this->PlanningModel->get_planning_datatable_export();

        if ($type == "pdf") {
            Exporter::export("pdf", $config);
        } elseif ($type == "csv") {
            Exporter::export("csv", $config);
        } elseif ($type == "excel") {
            Exporter::export("excel", $config);
        }
    }

    public function productPlanningAdding()
    {
        $post = [
            'finished_good_id' => $this->request->getPost("finished_good_id"),
            'start_date' => $this->request->getPost("start_date"),
            'end_date' => $this->request->getPost("end_date"),
            'stock' => $this->request->getPost("stock"),
            'price_id' => $this->request->getPost("price_id"),
            // 'status' => $this->request->getPost("status"),
        ];

        if ($this->request->getPost("finished_good_id")) {
            // return var_dump($this->request->getPost());
            if ($this->PlanningModel->insert_planning($post)) {
                return $this->response->redirect(url_to("erp.mrp.planningschedule"));
            } else {
                return $this->response->redirect(url_to("erp.mrp.productplanningadding"));
            }
        }

        $data['planning_status'] = $this->PlanningModel->get_planning_status();
        $data['price_list'] = $this->PlanningModel->get_active_price_list();
        $data['product'] = $this->PlanningModel->finishedGoodsProduct();
        $data['title'] = 'Forecasting Stocks and Demands';
        // $this->logger->error($data['product']);
        $data['page'] = "mrp/scheduling";
        $data['menu'] = "mrp";
        $data['submenu'] = "planning";
        return view("erp/index", $data);
    }

    public function productPlanningEdit($planning_id)
    {
        $post = [
            'finished_good_id' => $this->request->getPost("finished_good_id"),
            'start_date' => $this->request->getPost("start_date"),
            'end_date' => $this->request->getPost("end_date"),
            'stock' => $this->request->getPost("stock"),
            'status' => $this->request->getPost("status"),
            'finished_date' => $this->request->getPost("finished_date"),
            'price_id' => $this->request->getPost("price_id"),
        ];

        if ($this->request->getPost("finished_good_id")) {
            if ($this->PlanningModel->update_planning($planning_id, $post)) {
                return $this->response->redirect(url_to("erp.mrp.planningschedule"));
            } else {
                return $this->response->redirect(url_to("erp.mrp.productplanningedit", $planning_id));
            }
        }
        $data['planning'] = $this->PlanningModel->get_planning_by_id($planning_id);
        if (empty($data['planning'])) {
            $this->session->setFlashdata("op_error", "Planning Not Found");
            return $this->response->redirect(url_to("erp.mrp.planningschedule"));
        }
        $data['price_list'] = $this->PlanningModel->get_active_price_list();
        $data['planning_status'] = $this->PlanningModel->get_planning_status();
        $data['product'] = $this->PlanningModel->finishedGoodsProduct();
        // $this->logger->error($data['planning']);
        $data['planning_id'] = $planning_id;
        $data['page'] = "mrp/schedulingedit";
        $data['menu'] = "mrp";
        $data['submenu'] = "planning";
        return view("erp/index", $data);
    }

    public function planningView($planning_id)
    {

        if (empty($planning_id) || !is_numeric($planning_id)) {
            throw new \InvalidArgumentException('Invalid or missing Planning ID');
        }
        $data['planning'] = $this->PlanningModel->get_planning_by_id($planning_id);
        if (empty($data['planning'])) {
            $this->session->setFlashdata("op_error", "Planning Not Found");
            return $this->response->redirect(url_to("erp.mrp.planningschedule"));
        }
        $data['mrpscheduling_datatable_config'] = $this->BomModel->get_dtconfig_bom();
        $data['planning_status'] = $this->PlanningModel->get_planning_status();
        $data['planning_status_bg'] = $this->PlanningModel->get_planning_status_bg();
        $data['planning'] = $this->PlanningModel->get_planning_by_id($planning_id);
        $data['price_list'] = $this->PlanningModel->get_active_price_list();


        $data['planning_id'] = $planning_id;
        $data['page'] = "mrp/planningview";
        $data['menu'] = "mrp";
        $data['submenu'] = "planning";
        return view("erp/index", $data);
    }

    //bomdatatable
    public function datatable_bom($id)
    {
        $result = array();
        $result = $this->BomModel->get_datatable_bom($id);
        return $this->response->setJSON($result);
    }

    // public function addMRPStock($planning_id)
    // {
    //     if ($this->request->getPost('sku')) {
    //         if ($this->PlanningModel->insert_mrp_scheduling($planning_id)) {
    //             return $this->response->redirect(url_to("erp.mrp.planningview", $planning_id));
    //         } else {
    //             return $this->response->redirect(url_to("erp.mrp.addmrpstock", $planning_id));
    //         }
    //     }
    //     $finisg_hed_id = $this->PlanningModel->get_finishedgood_id($planning_id);
    //     $getcode = $this->FinishedGoodsModel->get_finishedgood_by_id($finisg_hed_id["finished_good_id"]);
    //     $data["product_code"] = $getcode->code;
    //     $data['product_links'] = $this->PlanningModel->get_product_links();
    //     $data['product_types'] = $this->PlanningModel->get_product_types();
    //     $data['warehouses'] = $this->PlanningModel->get_all_warehouses();
    //     $data['stocks'] = $this->PlanningModel->getStock($planning_id);
    //     $data['finishedgoodProduct'] = $this->PlanningModel->getFinishedGoodProduct($planning_id);
    //     $data['planning_id'] = $planning_id;
    //     // $this->logger->error($post);
    //     $data['page'] = "mrp/addmrpstock";
    //     $data['menu'] = "mrp";
    //     $data['submenu'] = "planning";
    //     return view("erp/index", $data);
    // }

    public function editMRPStock($planning_id, $mrp_scheduling_id)
    {
        // $this->logger->error($_POST);
        if ($this->request->getPost('sku')) {
            if ($this->PlanningModel->update_mrp_scheduling($planning_id, $mrp_scheduling_id)) {
                $this->session->setFlashdata("op_success", "MRP Scheduling successfully updated");
                return $this->response->redirect(url_to("erp.mrp.planningview", $planning_id));
            } else {
                $this->session->setFlashdata("op_error", "MRP Scheduling failed to update");
                return $this->response->redirect(url_to("erp.mrp.addmrpstock", $planning_id));
            }
        }

        $data['mrp_scheduling'] = $this->PlanningModel->get_mrp_scheduling_by_id($planning_id, $mrp_scheduling_id);

        // if (empty($data['planning'])) {
        //     $this->session->setFlashdata("op_error", "MRP Scheduling Not Found");
        //     return $this->response->redirect(url_to("erp.mrp.planningview", $planning_id));
        // }

        $data['currentstock_datatable_config'] = $this->CurrentStockModel->get_dtconfig_currentstock();
        $data['product_links'] = $this->PlanningModel->get_product_links();
        $data['product_types'] = $this->PlanningModel->get_product_types();
        $data['warehouses'] = $this->PlanningModel->get_all_warehouses();
        $data['stocks'] = $this->PlanningModel->getStock($planning_id);
        $data['finishedgoodProduct'] = $this->PlanningModel->getFinishedGoodProduct($planning_id);
        $data['planning_id'] = $planning_id;
        // $this->logger->error($post);
        $data['page'] = "mrp/editmrpstock";
        $data['menu'] = "mrp";
        $data['submenu'] = "planning";

        return view("erp/index", $data);
    }

    public function ajaxMrpSchedulingResponse()
    {
        $planningId = $this->request->getGet('planning_id');
        // return var_dump($planningId);
        $response = array();
        $response = $this->PlanningModel->getMrpSchedulingDatatable($planningId);

        // var_dump($response);
        // exit;
        return $this->response->setJSON($response);
    }

    //Implemented by Tamil


    public function BOM()
    {
        $data['bom_dtconfig'] = $this->BomModel->get_dtconfig_bom();
        $data['bom_status'] = [1, 2, 3];
        $data['page'] = "mrp/bom";
        $data['title'] = "Bill of Material";
        $data['menu'] = "mrp";
        $data['submenu'] = "bom";
        return view("erp/index", $data);
    }


    //ssdatatable
    public function get_m_invoice_datatable()
    {
        $columns = array(
            "code" => "code",
            "invoice date" => "invoice_date",
            "invoice expiry" => "invoice_expiry",
            "total amount" => "total_amount"
        );

        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT sale_invoice.invoice_id,code,customers.name,invoice_date,invoice_expiry,sale_invoice.status,trans_charge,discount,(SUM(sale_order_items.amount)+trans_charge-discount) AS total_amount,can_edit FROM sale_invoice JOIN customers ON sale_invoice.cust_id=customers.cust_id JOIN sale_order_items ON sale_invoice.invoice_id=sale_order_items.invoice_id WHERE sale_invoice.type=1 ";
        $count = "SELECT COUNT(sale_invoice.invoice_id) AS total FROM sale_invoice JOIN customers ON sale_invoice.cust_id=customers.cust_id JOIN sale_order_items ON sale_invoice.invoice_id=sale_order_items.invoice_id WHERE sale_invoice.type=1 ";
        $where = true;
        $filter_1_col = isset($_GET['status']) ? $columns['status'] : "";
        $filter_1_val = $_GET['status'];

        if (!empty($filter_1_col) && $filter_1_val !== "") {
            if (!$where) {
                $query .= " WHERE " . $filter_1_col . "=" . $filter_1_val . " ";
                $count .= " WHERE " . $filter_1_col . "=" . $filter_1_val . " ";
                $where = true;
            } else {
                $query .= " AND " . $filter_1_col . "=" . $filter_1_val . " ";
                $count .= " AND " . $filter_1_col . "=" . $filter_1_val . " ";
            }
        }

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE ( code LIKE '%" . $search . "%' OR customers.name LIKE '%" . $search . "%' ) ";
                $count .= " WHERE ( code LIKE '%" . $search . "%' OR customers.name LIKE '%" . $search . "%' ) ";
                $where = true;
            } else {
                $query .= " AND ( code LIKE '%" . $search . "%' OR customers.name LIKE '%" . $search . "%' ) ";
                $count .= " AND ( code LIKE '%" . $search . "%' OR customers.name LIKE '%" . $search . "%' ) ";
            }
        }
        $query .= " GROUP BY sale_invoice.invoice_id ";
        if (!empty($ordercol) && !empty($orderby)) {
            $query .= " ORDER BY " . $ordercol . " " . $orderby;
        }
        $query .= " LIMIT $offset,$limit ";
        $result = $this->db->query($query)->getResultArray();
        $m_result = array();
        $sno = $offset + 1;
        foreach ($result as $r) {
            $action = '<div class="dropdown tableAction">
                        <a type="button" class="dropBtn HoverA "><i class="fa fa-ellipsis-v"></i></a>
                        <div class="dropdown_container">
                            <ul class="BB_UL flex">
                                <li><a href="' . url_to('erp.sale.invoice.view', $r['invoice_id']) . '" title="View" class="bg-warning "><i class="fa fa-eye"></i> </a></li>';
            if ($r['can_edit'] == 1) {
                $action .= '<li><a href="' . url_to('erp.sale.invoice.edit', $r['invoice_id']) . '" title="Edit" class="bg-success "><i class="fa fa-pencil"></i> </a></li>';
            }
            $action .= '<li><a href="' . url_to('erp.sale.invoice.delete', $r['invoice_id']) . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
                            </ul>
                        </div>
                    </div>';
            array_push(
                $m_result,
                array(
                    $r['invoice_id'],
                    $sno,
                    $r['code'],
                    $r['name'],
                    $r['invoice_date'],
                    $r['invoice_expiry'],
                    $r['trans_charge'],
                    $r['discount'],
                    $r['total_amount'],
                    $action
                )
            );
            $sno++;
        }
        $response = array();
        $response['total_rows'] = $this->db->query($count)->getRow()->total;
        $response['data'] = $m_result;
        return $response;
    }


    public function AddMrpBOM($planning_id)
    {
        $bom_items_model = new BOMItemsModel();
        if ($this->request->getPost()) {
            // return var_dump($_POST);
            $product = $this->request->getPost("product");
            // $scrap = $this->request->getPost("scrap");
            if (isset($product)) {
                $this->logger->info("inside if");
                if ($this->BomModel->insert_mrp_scheduling($planning_id)) {
                    if (!$this->planning_update_status($planning_id)) {
                        $this->session->setFlashdata("op_error", "Error While updating Planning Status!");
                        return $this->response->redirect(url_to("erp.mrp.planningview", $planning_id));
                    }


                    $this->session->setFlashdata("op_success", "BOM successfully created");
                    return $this->response->redirect(url_to("erp.mrp.planningview", $planning_id));
                } else {
                    $this->session->setFlashdata("op_error", "BOM failed to create");
                    return $this->response->redirect(url_to("erp.mrp.add.bom", $planning_id));
                }
            } else {
                $this->logger->info("inside else");
                $this->session->setFlashdata("op_error", "Items is required!");
                return $this->response->redirect(url_to("erp.mrp.add.bom", $planning_id));
            }
        }

        $finisg_hed_id = $this->PlanningModel->get_finishedgood_id($planning_id);
        $getcode = $this->FinishedGoodsModel->get_finishedgood_by_id($finisg_hed_id["finished_good_id"]);
        $data["planning_product_code"] = $getcode->code;
        $data["planning_product_name"] = $getcode->name;
        $data["status"] = $this->PlanningModel->get_planning_status();
        $data["planning_product_id"] = $getcode->finished_good_id;
        $data['product_links'] = $this->BomModel->get_product_links();
        $data['product_types'] = $this->BomModel->get_product_types();
        $data['warehouses'] = $this->BomModel->get_all_warehouses();
        $data['stocks'] = $this->BomModel->getStock($planning_id);
        $data['finishedgoodProduct'] = $this->BomModel->getFinishedGoodProduct($planning_id);
        $data['planning_id'] = $planning_id;
        // $this->logger->error($post);
        $data['page'] = "mrp/bomadd";
        $data['menu'] = "mrp";
        $data['submenu'] = "planning";
        return view("erp/index", $data);
    }

    public function planning_update_status($planning_id)
    {
        $exist_planning_stock = $this->PlanningModel->getStock($planning_id);

        $total_completed_bom_stock = $this->BomModel->get_completed_bom_stock($planning_id);

        // $this->logger->info($exist_planning_stock);
        // $this->logger->info($total_completed_bom_stock);
        $result = true;
        if ($total_completed_bom_stock >= $exist_planning_stock["stock"]) {
            $this->logger->info($total_completed_bom_stock >= $exist_planning_stock);
            if ($this->PlanningModel->update($planning_id, ["status" => 2])) {
                // $this->logger->info("if");
                $result = true;
            } else {
                // $this->logger->info("else");
                $result = false;
            }
        }
        // $this->logger->info($total_completed_bom_stock >= $exist_planning_stock);

        return $result;
    }


    public function bom_edit_view($bom_id, $planning_id)
    {
        $bom_items_model = new BOMItemsModel();
        if ($this->request->getPost()) {
            $product = $this->request->getPost("product");
            $scrap = $this->request->getPost("scrap");

            if (isset($product)) {
                // return var_dump($this->request->getPost());
                // exit;
                if ($this->BomModel->update_mrp_scheduling($bom_id, $planning_id)) {

                    if (!$this->planning_update_status($planning_id)) {
                        $this->session->setFlashdata("op_error", "Error While updating Planning Status!");
                        return $this->response->redirect(url_to("erp.mrp.planningview", $planning_id));
                    }

                    $this->session->setFlashdata("op_success", "BOM successfully Updated");
                    return $this->response->redirect(url_to("erp.mrp.planningview", $planning_id));
                } else {
                    $this->session->setFlashdata("op_error", "BOM failed to update");
                    return $this->response->redirect(url_to("erp.bill.of.materials.edit", $bom_id, $planning_id));
                }
            } else {
                $this->session->setFlashdata("op_error", "Items is required!");
                return $this->response->redirect(url_to("erp.bill.of.materials.edit", $bom_id, $planning_id));
            }
        }

        $finisg_hed_id = $this->PlanningModel->get_finishedgood_id($planning_id);
        $getcode = $this->FinishedGoodsModel->get_finishedgood_by_id($finisg_hed_id["finished_good_id"]);
        $data["planning_product_code"] = $getcode->code;
        $data["planning_product_name"] = $getcode->name;
        $data["planning_product_id"] = $getcode->finished_good_id;
        $data["status"] = $this->PlanningModel->get_planning_status();
        $data["exist_data"] = $this->BomModel->get_exist_bom($bom_id, $planning_id);
        $data['product_links'] = $this->BomModel->get_product_links();
        $data['bom_items'] = $this->BOMItemsModel->get_bom_items($bom_id);
        $data['bom_scrap_items'] = $this->BomscrapitemsModel->get_bom_scrap_items($bom_id);
        $data['bom_operation_items'] = $this->BomOperationModel->get_bom_operation_items($bom_id);
        // var_dump($data['bom_operation_items']);
        // exit;
        $data['product_types'] = $this->BomModel->get_product_types();
        $data['warehouses'] = $this->BomModel->get_all_warehouses();
        $data['stocks'] = $this->BomModel->getStock($planning_id);
        $data['finishedgoodProduct'] = $this->BomModel->getFinishedGoodProduct($planning_id);
        // costing
        $data['scrap_datatable_config'] = $this->scrapModel->get_dtconfig_bom_scrap();
        $data['scrap_cost'] = $this->BomscrapitemsModel->totalScrapCost($bom_id)['total_scrap_cost'];
        $data['material_cost'] = $this->BOMItemsModel->totalMaterialCost($bom_id)["total_material_cost"];
        $data['operation_cost'] = $this->costingModel->getOperationCost($planning_id, $bom_id);
        $data['total_costing'] = $data['material_cost'] - $data['scrap_cost'] + $data['operation_cost'];
        // $data['total_costing'] = $data['material_cost'] - $data['scrap_cost'];
        $data['costingData'] = $this->costingModel->getCostingData($planning_id);
        $data['planning_id'] = $planning_id;


        $data['page'] = "mrp/bomedit";
        $data['menu'] = "mrp";
        $data['submenu'] = "planning";
        return view("erp/index", $data);
    }
    public function bom_data_view($bom_id, $planning_id)
    {
        $data['page'] = "mrp/bomdata_view";
        $data['menu'] = "mrp";
        $data['planning_id'] = $planning_id;
        $data['bom_id'] = $bom_id;
        $data['submenu'] = "planning";
        return view("erp/index", $data);
    }

    // public function get_stock_goods(){
    //     $warehouse_id = $_POST["w_id"];
    //     $warehouse_type = ($_POST["w_type"] == "rawmaterials") ? "raw_material" : "semi_finished";


    // }

    public function bom_delete($bom_id, $planning_id)
    {

        if ($this->BomModel->delete_bom($bom_id, $planning_id)) {
            $this->session->setFlashdata("op_success", "BOM successfully deleted");
        } else {
            $this->session->setFlashdata("op_error", "BOM failed to delete");
        }
        return $this->response->redirect(url_to("erp.mrp.planningview", $planning_id));
    }


    //overallbom view
    public function overallbomList()
    {
        $data["status"] = $this->PlanningModel->get_planning_status();
        $data["datatable_config"] = $this->BomModel->get_dtconfig_overall_bom();
        $data['menu'] = "mrp";
        $data['page'] = "mrp/overallbomlist";
        $data['submenu'] = "overallbomlist";
        return view("erp/index", $data);
    }
    public function overallbomdatatable()
    {
        $result = array();
        $result = $this->BomModel->get_bom_datatable_overall();
        return $this->response->setJSON($result);
    }

    public function overall_bom_export()
    {
        $type = $_GET["export"];

        $config["title"] = "Overall Bill Of Materials List";
        if ($type == "pdf") {
            $config["column"] = array("Sno", "Product", "SKU Code", "Warehouse", "BOM Date", "Amount", "Quantity", "status");

        } else {
            $config["column"] = array("Sno", "Product", "SKU Code", "Warehouse", "BOM Date", "Amount", "Quantity", "status");
            $config["column_data_type"] = array(ExporterConst::E_INTEGER, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_DATE, ExporterConst::E_INTEGER, ExporterConst::E_INTEGER, ExporterConst::E_STRING);
        }

        $config["data"] = $this->BomModel->getOverallDataExport();

        if ($type == "pdf") {
            Exporter::export("pdf", $config);
        } elseif ($type == "csv") {
            Exporter::export("csv", $config);
        } elseif ($type == "excel") {
            Exporter::export("excel", $config);
        }
    }




    public function workstationtypeview()
    {
        $data['menu'] = "mrp";
        $data['page'] = "mrp/workstationtype";
        $data['submenu'] = "worstationtype";
        $data['datatable_config'] = $this->WorkstationtypeModel->get_dtconfig_planning();
        return view("erp/index", $data);
    }

    public function workstationtype_datatable()
    {
        $result = array();
        $result = $this->WorkstationtypeModel->datatable();
        return $this->response->setJSON($result);
    }

    public function workstationtype_add()
    {
        if ($this->request->getPost()) {
            $data = [
                "name" => $this->request->getPost("name"),
                "electricity_cost" => $this->request->getPost("ec"),
                "rent_cost" => $this->request->getPost("rc"),
                "consumable_cost" => $this->request->getPost("cc"),
                "wages_cost" => $this->request->getPost("wage"),
                "description" => $this->request->getPost("desc"),
            ];

            $result = $this->WorkstationtypeModel->add($data);

            if ($result) {
                $this->session->setFlashdata("op_success", "Workstatiom type Inserted Successfully!");
            } else {
                $this->session->setFlashdata("op_error", "Workstatiom type Failed to Inserted!");
            }
            return $this->response->redirect(url_to("erp.mrp.worstationtype"));

        }
    }

    public function workstationtype_update()
    {
        $id = $this->request->getPost("workstationid");
        if (!empty($id)) {
            $data = [
                "name" => $this->request->getPost("name"),
                "electricity_cost" => $this->request->getPost("ec"),
                "rent_cost" => $this->request->getPost("rc"),
                "consumable_cost" => $this->request->getPost("cc"),
                "wages_cost" => $this->request->getPost("wage"),
                "description" => $this->request->getPost("desc"),
            ];

            $result = $this->WorkstationtypeModel->edit($id, $data);
            if ($result) {
                $this->session->setFlashdata("op_success", "Workstatiom type Inserted update!");
            } else {
                $this->session->setFlashdata("op_error", "Workstatiom type Failed to Update!");
            }
            return $this->response->redirect(url_to("erp.mrp.worstationtype"));
        } else {
            $this->session->setFlashdata("op_error", "No entries were found in this data!");
            return $this->response->redirect(url_to("erp.mrp.worstationtype"));
        }
    }

    public function workstationtype_delete($id)
    {
        $result = $this->WorkstationtypeModel->remove($id);

        if ($result) {
            $this->session->setFlashdata("op_success", "Deleted successfully!");
        } else {
            $this->session->setFlashdata("op_error", "Failed to delete!");
        }
        return $this->response->redirect(url_to("erp.mrp.worstationtype"));
    }

    public function getworkstationtypedata()
    {
        $id = $_POST["w_id"];
        $result = $this->WorkstationtypeModel->get_workstation_by_id($id);
        if ($result) {
            return $this->response->setJSON(["success" => true, "data" => $result]);
        } else {
            return $this->response->setJSON(["success" => false]);
        }
    }

    //workstations
    public function workstationview()
    {
        $data['menu'] = "mrp";
        $data['page'] = "mrp/workstationview";
        $data['submenu'] = "worstation";
        $data['datatable_config'] = $this->WorkstationModel->get_dtconfig_planning();

        return view("erp/index", $data);
    }

    public function workstationdatatable()
    {
        $result = array();
        $result = $this->WorkstationModel->datatable();
        return $this->response->setJSON($result);
    }

    public function workstation_add()
    {

        if ($this->request->getPost()) {

            $data = [
                "name" => $this->request->getPost("name"),
                "workstationtype_id" => $this->request->getPost("workstation_id"),
                "warehose_id" => $this->request->getPost("warehouse_id"),
                "electricity_cost" => $this->request->getPost("e_cost"),
                "rent_cost" => $this->request->getPost("r_cost"),
                "consumable_cost" => $this->request->getPost("cc_cost"),
                "wages_cost" => $this->request->getPost("wages_cost"),
                "work_hour_start" => $this->request->getPost("work_start_date"),
                "work_hour_end" => $this->request->getPost("work_end_date"),
                "description" => $this->request->getPost("description"),
                "status" => $this->request->getPost("status"),
            ];


            $result = $this->WorkstationModel->Workstationadd($data);
            // var_dump($result);
            // exit;

            if ($result) {
                $this->session->setFlashdata("op_success", "Workstation Inserted successfully!");
                return $this->response->redirect(url_to("erp.mrp.worstation"));
            } else {
                $this->session->setFlashdata("op_error", "Error While Inserting Workstation!");
                return $this->response->redirect(url_to("erp.mrp.workstationadd.view"));
            }
        }

        $data['menu'] = "mrp";
        $data['page'] = "mrp/workstationadd";
        $data['submenu'] = "worstation";
        $data['status'] = $this->WorkstationModel->get_worstation_status();
        $data['warehouses'] = $this->BomModel->get_all_warehouses();
        $data['workstation_type'] = $this->WorkstationtypeModel->get_all_workstation();
        return view("erp/index", $data);
    }
    public function workstation_edit($id)
    {

        if ($this->request->getPost()) {

            $data = [
                "name" => $this->request->getPost("name"),
                "workstationtype_id" => $this->request->getPost("workstation_id"),
                "warehose_id" => $this->request->getPost("warehouse_id"),
                "electricity_cost" => $this->request->getPost("e_cost"),
                "rent_cost" => $this->request->getPost("r_cost"),
                "consumable_cost" => $this->request->getPost("cc_cost"),
                "wages_cost" => $this->request->getPost("wages_cost"),
                "work_hour_start" => $this->request->getPost("work_start_date"),
                "work_hour_end" => $this->request->getPost("work_end_date"),
                "description" => $this->request->getPost("description"),
                "status" => $this->request->getPost("status"),
            ];

            $result = $this->WorkstationModel->Workstationedit($id, $data);

            if ($result) {
                $this->session->setFlashdata("op_success", "Workstation updated successfully!");
                return $this->response->redirect(url_to("erp.mrp.worstation"));
            } else {
                $this->session->setFlashdata("op_error", "Error While updating Workstation!");
                return $this->response->redirect(url_to("erp.mrp.workstationedit.view", $id));
            }
        }

        $data['menu'] = "mrp";
        $data['page'] = "mrp/workstationedit";
        $data['submenu'] = "worstation";
        $data['exist_data'] = $this->WorkstationModel->get_workstation_data_by_id($id);
        $data['status'] = $this->WorkstationModel->get_worstation_status();
        $data['warehouses'] = $this->BomModel->get_all_warehouses();
        $data['workstation_type'] = $this->WorkstationtypeModel->get_all_workstation();
        return view("erp/index", $data);

    }

    public function workstation_delete($id)
    {
        $result = $this->WorkstationModel->remove($id);

        if ($result) {
            $this->session->setFlashdata("op_success", "Workstation deleted successfully!");
            return $this->response->redirect(url_to("erp.mrp.worstation"));
        } else {
            $this->session->setFlashdata("op_error", "Error while deleting Workstation!");
            return $this->response->redirect(url_to("erp.mrp.worstation"));
        }
    }


    public function ajaxFetchworkstationtype()
    {
        $search = $_GET['search'] ?? "";
        $response = array();
        $response['error'] = 1;
        if (!empty($search)) {
            $query = "SELECT id,name FROM workstationtype WHERE name LIKE '%" . $search . "%' LIMIT 10";
            $result = $this->db->query($query)->getResultArray();
            $m_result = array();
            foreach ($result as $r) {
                array_push($m_result, array(
                    "key" => $r['id'],
                    "value" => $r['name']
                ));
            }
            $response['error'] = 0;
            $response['data'] = $m_result;
        }
        return $this->response->setJSON($response);
    }

    public function ajaxgetworkstationdetails()
    {
        $id = $_POST["id"];

        if (!empty($id)) {
            $result = $this->WorkstationtypeModel->get_workstation_type_details_by_id($id);

            if ($result) {
                return $this->response->setJSON(["success" => true, "data" => $result]);
            } else {
                return $this->response->setJSON(["success" => false, "errorcode" => 0]);
            }
        } else {
            return $this->response->setJSON(["success" => false, "errorcode" => 1]);
        }
    }


    // Costing Details Data


    public function CostingAdd($planning_id)
    {


        if ($data['raw_material_cost'] = $this->request->getPost('raw_material_cost')) {
            $data['operating_cost'] = $this->request->getPost('operating_cost');
            $data['scrap_cost'] = $this->request->getPost('scrap_cost');
            $data['total_cost'] = $this->request->getPost('total_cost');

            $data['planning_id'] = $planning_id;

            if ($this->costingModel->getCostingData($planning_id) === null) {
                // No data exists, insert new data
                if ($this->costingModel->insertCostingData($data)) {
                    $config['log_text'] = "[MRP Costing Added Successfully!]";
                    $config['ref_link'] = url_to('erp.mrp.planningview', $planning_id);
                    $this->session->setFlashdata('op_success', 'MRP Costing Added Successfully!');
                    return $this->response->redirect(url_to('erp.mrp.planningview', $planning_id));
                } else {
                    $config['log_text'] = "[MRP Costing Creation Failed!]";
                    $config['ref_link'] = url_to('erp.mrp.planningview', $planning_id);
                    $this->session->setFlashdata('op_error', 'MRP Costing Creation Failed!');
                    return $this->response->redirect(url_to('erp.mrp.planningview', $planning_id));
                }
            } elseif ($this->costingModel->updateCostingData($planning_id, $data)) {
                // Data exists, update it
                $config['log_text'] = "[MRP Costing Updated Successfully!]";
                $config['ref_link'] = url_to('erp.mrp.planningview', $planning_id);
                $this->session->setFlashdata('op_success', 'MRP Costing Updated Successfully!');
                return $this->response->redirect(url_to('erp.mrp.planningview', $planning_id));
            } else {
                // Error occurred
                $config['log_text'] = "[MRP Costing Action Failed]";
                $config['ref_link'] = url_to('erp.mrp.planningview', $planning_id);
                $this->session->setFlashdata('op_error', 'MRP Costing Action Failed!');
                return $this->response->redirect(url_to('erp.mrp.planningview', $planning_id));
            }


        }

        $this->data['menu'] = "mrp";
        $this->data['submenu'] = "planning";
        return view("erp/index", $this->data);
    }

    // scrap 

    public function scrapaddedit()
    {

        $scrap_id = $this->request->getPost("scrap_id");

        $data['item_code'] = $this->request->getPost("item_code");
        $data['item_name'] = $this->request->getPost("item_name");

        $data['quantity'] = $this->request->getPost("quantity");
        $data['rate'] = $this->request->getPost("rate");

        if ($data) {
            $this->scrapModel->insert_update_scrap($scrap_id, $data);
        }

        return $this->response->redirect(url_to("erp.mrp.planningview"));
    }


    public function ajaxScrapResponse()
    {
        $response = array();
        $response = $this->scrapModel->get_datatable_scrap();
        return $this->response->setJSON($response);
    }


    // Operations Ahead !
    public function OperationList()
    {
        $data['datatable_config'] = $this->OperationModel->get_dtconfig_bom_operation();
        $data['menu'] = "mrp";
        $data['page'] = "mrp/operationlist";
        $data['submenu'] = "operation";
        return view("erp/index", $data);
    }

    public function OperationAdd()
    {
        if (isset($_POST['operation_name'])) {
            $data['name'] = $_POST['operation_name'];
            $data['default_workstation_id'] = $_POST['workstation_id'];
            $data['description'] = $_POST['description'];
            $data['is_corrective_operation'] = $_POST['is_corrective_operation'];

            if ($this->OperationModel->insert($data)) {
                session()->setFlashdata('op_success', 'Operation Saved !');
                return $this->response->redirect(url_to("erp.mrp.operation.list"));
            } else {
                session()->setFlashdata("op_error", "Can't Save Operation !");
                return $this->response->redirect(url_to("erp.mrp.operation.add"));
            }
        }
        $data['workstations'] = $this->OperationModel->get_all_workstation();
        $data['menu'] = "mrp";
        $data['page'] = "mrp/operationadd";
        $data['submenu'] = "operation";
        return view("erp/index", $data);
    }

    public function OperationDelete($id)
    {
        if ($this->OperationModel->delete($id)) {
            session()->setFlashdata('op_success', 'Operation Deleted !');
            return $this->response->redirect(url_to("erp.mrp.operation.list"));
        } else {
            session()->setFlashdata("op_error", "Can't Delete Operation !");
            return $this->response->redirect(url_to("erp.mrp.operation.list"));
        }
    }

    public function operationEdit($id)
    {
        if (isset($_POST['operation_name'])) {
            // return var_dump($_POST);
            $data['name'] = $_POST['operation_name'];
            $data['default_workstation_id'] = $_POST['workstation_id'];
            $data['description'] = $_POST['description'];
            $data['is_corrective_operation'] = !empty($_POST['is_corrective_operation']) ?? 0;
            if ($this->OperationModel->update($id, $data)) {
                session()->setFlashdata('op_success', 'Operation Update !');
                return $this->response->redirect(url_to("erp.mrp.operation.list"));
            } else {
                session()->setFlashdata("op_error", "Can't Update Operation !");
                return $this->response->redirect(url_to("erp.mrp.operation.edit", $id));
            }
        }

        $data['operation_data'] = $this->OperationModel->get_edit_page_data($id);
        $data['workstations'] = $this->OperationModel->get_all_workstation();
        $data['menu'] = "mrp";
        $data['page'] = "mrp/operationEdit";
        $data['submenu'] = "operation";
        return view("erp/index", $data);
    }

    public function ajaxOperationResponse()
    {
        $response = array();
        $response = $this->OperationModel->get_operation_datatable();
        return $this->response->setJSON($response);
    }

    public function getDataForBom()
    {
        $search = $_GET['id'] ?? "";
        $response = array();
        $response['error'] = 1;
        if (!empty($search)) {
            $query = "SELECT bom_operation.id as id, bom_operation.name as name, workstation.name as workstation_name, 
            (workstation.electricity_cost + workstation.rent_cost + workstation.consumable_cost + workstation.wages_cost) as total_cost
            
            FROM bom_operation 
            LEFT JOIN workstation ON bom_operation.default_workstation_id = workstation.id 
            WHERE bom_operation.id = " . $_GET['id'];
            $response['error'] = 0;
            $response['data'] = $this->db->query($query)->getRowArray();
        }
        return $this->response->setJSON($response);
    }

    public function ajaxOperationlist()
    {
        $search = $_GET['search'] ?? "";
        $response = array();
        $response['error'] = 1;
        if (!empty($search)) {
            $query = "SELECT bom_operation.id as id, bom_operation.name as name FROM bom_operation 
        LEFT JOIN workstation ON bom_operation.default_workstation_id = workstation.id 
        WHERE bom_operation.name like '%" . $_GET['search'] . "%'  LIMIT 10";
            $result = $this->db->query($query)->getResultArray();
            $m_result = array();
            foreach ($result as $r) {
                array_push($m_result, array(
                    "key" => $r['id'],
                    "value" => $r['name']
                ));
            }
            $response['error'] = 0;
            $response['data'] = $m_result;
        }
        return $this->response->setJSON($response);
    }

    public function operationsExport()
    {
        $type = $_GET['export'];
        $config = array();
        $config['title'] = "Operation list";
        if ($type == "pdf") {
            $config['column'] = array("Service Code", "Service Name", "Priority", "Assigned To", "Lobers", "Status");
        } else {
            $config['column'] = array("Service Code", "Service Name", "Priority", "Assigned To", "Lobers", "Status");
            $config['column_data_type'] = array(ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING);
        }
        $config['data'] = $this->OperationModel->getOperationListExport($type);
        if ($type == "pdf") {
            Exporter::export(ExporterConst::PDF, $config);
        } else if ($type == "excel") {
            Exporter::export(ExporterConst::EXCEL, $config);
        } else if ($type == "csv") {
            Exporter::export(ExporterConst::CSV, $config);
        }
    }




}
