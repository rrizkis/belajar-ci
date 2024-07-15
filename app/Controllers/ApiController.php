<?php

namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

use App\Models\UserModel;
use App\Models\TransactionModel;
use App\Models\TransactionDetailModel;

class ApiController extends ResourceController
{
    protected $apiKey = "fd2de1c0308133e0ae2bd476f4b065d6";
    protected $user;
    protected $transaction;
    protected $transaction_detail;

    function __construct()
    {
        $this->user = new UserModel();
        $this->transaction = new TransactionModel();
        $this->transaction_detail = new TransactionDetailModel();
    }

    // Other methods like index(), show(), new(), create(), etc. can be kept as they are or implemented as needed.

    public function monthly()
    {
        $data = [
            'query' => [],
            'results' => [],
            'status' => ["code" => 401, "description" => "Unauthorized"]
        ];

        $postData = $this->request->getPost();

        if (empty($postData) || !isset($postData['type'], $postData['tahun'], $postData['bulan'])) {
            return $this->fail('Invalid request parameters', 400);
        }

        $headers = $this->request->getHeaders();

        if (!isset($headers['Key'])) {
            return $this->fail('API Key is missing', 401);
        }

        $apiKey = $headers['Key']->getValue();

        if ($apiKey !== $this->apiKey) {
            return $this->fail('Unauthorized access', 403);
        }

        $data['query'] = $postData;

        switch ($postData['type']) {
            case 'transaction':
                $result = $this->transaction->select('COUNT(*) as jml')
                    ->like('created_at', $postData['tahun'] . '-' . $postData['bulan'], 'after')
                    ->first();
                break;
            case 'earning':
                $result = $this->transaction->select('SUM(total_harga) as jml')
                    ->like('created_at', $postData['tahun'] . '-' . $postData['bulan'], 'after')
                    ->first();
                break;
            case 'user':
                $result = $this->user->select('COUNT(*) as jml')
                    ->like('created_at', $postData['tahun'] . '-' . $postData['bulan'], 'after')
                    ->first();
                break;
            default:
                return $this->fail('Invalid type requested', 400);
        }

        if ($result) {
            $data['results'] = $result;
            $data['status'] = ["code" => 200, "description" => "OK"];
        } else {
            $data['status'] = ["code" => 404, "description" => "Data not found"];
        }

        return $this->respond($data);
    }

    public function yearly()
    {
        $data = [
            'query' => [],
            'results' => [],
            'status' => ["code" => 401, "description" => "Unauthorized"]
        ];

        $postData = $this->request->getPost();

        if (empty($postData) || !isset($postData['type'], $postData['tahun'])) {
            return $this->fail('Invalid request parameters', 400);
        }

        $headers = $this->request->getHeaders();

        if (!isset($headers['Key'])) {
            return $this->fail('API Key is missing', 401);
        }

        $apiKey = $headers['Key']->getValue();

        if ($apiKey !== $this->apiKey) {
            return $this->fail('Unauthorized access', 403);
        }

        $data['query'] = $postData;

        switch ($postData['type']) {
            case 'transaction':
                $result = $this->transaction->select('COUNT(*) as jml')
                    ->like('created_at', $postData['tahun'], 'after')
                    ->first();
                break;
            case 'earning':
                $result = $this->transaction->select('SUM(total_harga) as jml')
                    ->like('created_at', $postData['tahun'], 'after')
                    ->first();
                break;
            case 'user':
                $result = $this->user->select('COUNT(*) as jml')
                    ->like('created_at', $postData['tahun'], 'after')
                    ->first();
                break;
            default:
                return $this->fail('Invalid type requested', 400);
        }

        if ($result) {
            $data['results'] = $result;
            $data['status'] = ["code" => 200, "description" => "OK"];
        } else {
            $data['status'] = ["code" => 404, "description" => "Data not found"];
        }

        return $this->respond($data);
    }
}
