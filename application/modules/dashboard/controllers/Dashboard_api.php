<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Class Dashboard_api
 *
 * @property Schedule_model $schedule_model
 * @property Loaplan_model $loadplan_model
 * @property Bill_lading_model $bill_lading_model
 * @property Penyerahan_model $penyerahan_model
 */
class Dashboard_api extends Api_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        
    }

    public function widget()
    {
        $this->load->model('schedules/schedule_model');
        $this->load->model('voyage/loadplan_model');
        $this->load->model('voyage/bill_lading_model');
        $this->load->model('voyage/penyerahan_model');

        $countSchedule = $this->schedule_model->count_rows(['MONTH(date_start)' => date('m')]);
        $countLoadplan = $this->loadplan_model->count_rows(['MONTH(loadplan_date)' => date('m')]);
        $countBillLading = $this->bill_lading_model->count_rows(['MONTH(created_at)' => date('m')]);
        $countPenyerahan = $this->penyerahan_model->count_rows(['MONTH(tanggal)' => date('m')]);
        
        $this->template->build_json([
            'schedule' => $countSchedule,
            'loadplan' => $countLoadplan,
            'billLading' => $countBillLading,
            'penyerahan' => $countPenyerahan,
        ]);
    }
}