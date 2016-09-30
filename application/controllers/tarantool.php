<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . 'third_party/parsecsv/parsecsv.lib.php';

class Tarantool extends CI_Controller {

    private $userId = '100';

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
    }

    public function index()
    {
        $this->load->model('tarantool_model');

        $data['header'] = $this->tarantool_model->test();
        $data['title'] = 'Under Construction';

        if($_POST['submit_add']){
            if($_POST['mat_name'] || $_POST['mat_1'] || $_POST['mat_2'] || $_POST['mat_3'] || $_POST['mat_4'] || $_POST['mat_5'] || $_POST['mat_6'] || $_POST['mat_7'] || $_POST['mat_8'] || $_POST['mat_9'] || $_POST['mat_10'] || $_POST['mat_11'] || $_POST['mat_12']){
                $this->tarantool_model->insert();
            }
        }

        $this->load->view('tarantool', $data);
    }

    public function uploadfile() {

        $this->load->model('tarantool_model');

        $config = array(
            'upload_path' => APPPATH . 'uploads/',
            'allowed_types' => 'csv',
            'overwrite' => TRUE,
            'max_size' => '2048',
            'max_filename' => '255'
        );

        if (isset($_FILES['file']['name'])) {
            if (0 < $_FILES['file']['error']) {
                echo json_encode( array(
                    'status' => 'error',
                    'statusMsg' => '<p>Error during file upload' . $_FILES['file']['error'] . '.</p>'
                ) );
            } else {
                $this->load->library('upload', $config);
                if (!$this->upload->do_upload('file')) {
                    echo json_encode( array(
                        'status' => 'error',
                        'statusMsg' => $this->upload->display_errors()
                    ) );
                } else {
                    $fileInfo = $this->upload->data();
                    $fileFullPath = $fileInfo['full_path'];

                    $csv = new parseCSV($fileFullPath);

                    $csvhtml = $this->csvhtml($csv->data);
                    echo json_encode( array(
                        'status' => 'success',
                        'statusMsg' => '<p>File successfully uploaded: ' . $_FILES['file']['name'] . '.</p>',
                        'data' => $csvhtml['html']
                    ) );

                    if($csvhtml['doctype']) {
                        $this->tarantool_model->spaceinsert(array(
                            'data' => $csv->data,
                            'doctype' => $csvhtml['doctype'],
                            'userid' => $this->userId
                        ));
                    }
                }
            }
        } else {
            echo json_encode( array(
                'status' => 'error',
                'statusMsg' => '<p>Please choose a file.</p>'
            ) );
        }
    }

    private function csvhtml($csvdata) {
        $doctype = '';
        $keys = array_keys($csvdata[0]);

        $primKeys = array('Version', 'Year', 'Month', 'Account_Element', 'Receiver', 'Fixed', 'Proportional');
        $secKeys = array('Version', 'Year', 'Month', 'Sender', 'Receiver', 'PropQty', 'FixQty');
        $rateKeys = array('Version', 'Year', 'Month', 'Sender', '#Value');

        if(!array_diff($primKeys, $keys) && !array_diff($keys, $primKeys)){
            $doctype = 'primdoc';
        } elseif(!array_diff($secKeys, $keys) && !array_diff($keys, $secKeys)) {
            $doctype = 'secdoc';
        } elseif(!array_diff($rateKeys, $keys) && !array_diff($keys, $rateKeys)) {
            $doctype = 'ratedoc';
        }

        if($doctype) {
            $html = '<p>' . $doctype . '</p>';
            $html .= '<table class="table table-striped">';
            $html .= '<tr>';
            foreach ($keys as $key) {
                $html .= '<th>' . $key . '</th>';
            }
            $html .= '</tr>';
            foreach ($csvdata as $row) {
                $html .= '<tr>';
                foreach($keys as $key) {
                    $html .= '<td>' . $row[$key] . '</td>';
                }
                $html .= '</tr>';
            }
            $html .= '</table>';
        } else {
            $html = '<p>Document format not valid.</p>';
        }

        return array('html' => $html, 'doctype' => $doctype);
    }

}