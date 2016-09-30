<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'third_party/composer/vendor/autoload.php';

use Tarantool\Client\Client;
use Tarantool\Client\Connection\StreamConnection;
use Tarantool\Client\Packer\PurePacker;

class Tarantool_model extends CI_Model {

    private $client;

    public function __construct()
    {
        parent::__construct();

        $conn = new StreamConnection('tcp://127.0.0.1:3301', ['socket_timeout' => 5.0, 'connect_timeout' => 5.0]);
        $this->client = new Client($conn, new PurePacker());
        $this->client->authenticate('william', '@admin10');
    }

    public function spaceinsert($data) {
        $doctype = $data['doctype'];
        $csvdata = $data['data'];
        $userid = $data['userid'];
    }

    public function test()
    {
        $result = $this->client->evaluate('return getM(...)', array('matrix1'));
        return $result->getData();
    }

    public function insert()
    {
        $mat = [];
        array_push($mat, $_POST['mat_name'], $_POST['mat_1'], $_POST['mat_2'], $_POST['mat_3'], $_POST['mat_4'], $_POST['mat_5'], $_POST['mat_6'], $_POST['mat_7'], $_POST['mat_8'], $_POST['mat_9'], $_POST['mat_10'], $_POST['mat_11'], $_POST['mat_12']);
        $this->client->evaluate('return insertM(...)', array('matrix1', $mat));
    }

}