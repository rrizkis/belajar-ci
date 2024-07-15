<?php

namespace App\Controllers;

use App\Models\ProductModel;
use Dompdf\Dompdf;

class ProdukController extends BaseController
{
    protected $product;
    protected $validation;

    function __construct()
    {
        $this->product = new ProductModel();
        $this->validation = \Config\Services::validation(); // Load the validation service
    }

    public function index()
    {
        $product = $this->product->findAll();
        $data['product'] = $product;

        return view('v_produk', $data);
    }

    public function create()
    {
        // Define validation rules
        $rules = [
            'nama' => 'required|min_length[6]',
            'harga' => 'required|numeric',
            'jumlah' => 'required|numeric'
        ];

        // Validate the form input
        if (!$this->validate($rules)) {
            // If validation fails, redirect back with validation errors and input data
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        // Proceed with form data if validation passes
        $dataFoto = $this->request->getFile('foto');

        $dataForm = [
            'nama' => $this->request->getPost('nama'),
            'harga' => $this->request->getPost('harga'),
            'jumlah' => $this->request->getPost('jumlah'),
            'created_at' => date("Y-m-d H:i:s")
        ];

        if ($dataFoto->isValid()) {
            $fileName = $dataFoto->getRandomName();
            $dataForm['foto'] = $fileName;
            $dataFoto->move('img/', $fileName);
        }

        $this->product->insert($dataForm);

        return redirect('produk')->with('success', 'Data Berhasil Ditambah');
    }

    public function edit($id)
    {
        $dataProduk = $this->product->find($id);

        // Define validation rules
        $rules = [
            'nama' => 'required|min_length[6]',
            'harga' => 'required|numeric',
            'jumlah' => 'required|numeric'
        ];

        // Validate the form input
        if (!$this->validate($rules)) {
            // If validation fails, redirect back with validation errors and input data
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        // Proceed with form data if validation passes
        $dataForm = [
            'nama' => $this->request->getPost('nama'),
            'harga' => $this->request->getPost('harga'),
            'jumlah' => $this->request->getPost('jumlah'),
            'updated_at' => date("Y-m-d H:i:s")
        ];

        if ($this->request->getPost('check') == 1) {
            if ($dataProduk['foto'] != '' && file_exists("img/" . $dataProduk['foto'])) {
                unlink("img/" . $dataProduk['foto']);
            }

            $dataFoto = $this->request->getFile('foto');

            if ($dataFoto->isValid()) {
                $fileName = $dataFoto->getRandomName();
                $dataFoto->move('img/', $fileName);
                $dataForm['foto'] = $fileName;
            }
        }

        $this->product->update($id, $dataForm);

        return redirect('produk')->with('success', 'Data Berhasil Diubah');
    }

    public function delete($id)
    {
        $dataProduk = $this->product->find($id);

        if ($dataProduk['foto'] != '' && file_exists("img/" . $dataProduk['foto'])) {
            unlink("img/" . $dataProduk['foto']);
        }

        $this->product->delete($id);

        return redirect('produk')->with('success', 'Data Berhasil Dihapus');
    }

    public function download()
    {
        $product = $this->product->findAll();

        $html = view('v_produkPDF', ['product' => $product]);

        $filename = date('y-m-d-H-i-s') . '-produk';

        // instantiate and use the dompdf class
        $dompdf = new Dompdf();

        // load HTML content
        $dompdf->loadHtml($html);

        // (optional) setup the paper size and orientation
        $dompdf->setPaper('A4', 'portrait');

        // render html as PDF
        $dompdf->render();

        // output the generated pdf
        $dompdf->stream($filename);
    }
}
