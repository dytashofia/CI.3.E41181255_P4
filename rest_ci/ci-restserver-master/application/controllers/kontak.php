<?php

/*
| Controller ini merupakan controller untuk kontak
*/

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';  // import library dari rest controller
use Restserver\Libraries\REST_Controller;  

// Mendlekarasikan class Kontak
class Kontak extends REST_Controller {

    function __construct($config = 'rest') { // mengaktifkan konfigurasi rest
        parent::__construct($config); 
        $this->load->database(); // memanggil database
    }

    /*
    | Menampilkan data kontak menggunakan fungsi GET
    | GET menyediakan akses baca pada sumber daya yang disediakan oleh REST API
    | Fungsi GET disini berfungsi untuk menampilkan data dari tabel telepon pada database kontak
    | GET dituliskan pertama kali untuk memeriksa apakah terdapat properti id pada address bar, sehinnga data yang ditampilkan diseleksi berdasarkan id
    */ 

    function index_get() { //function untuk get
        $id = $this->get('id'); // variabel di inisialisasikan untuk mendapat id
        if ($id == '') { // jika id sama dengan yang di inputkan
            $kontak = $this->db->get('telepon')->result(); // variable kontak dibuat untuk menyimpan ke tabel telepon
        } else {
            $this->db->where('id', $id); 
            $kontak = $this->db->get('telepon')->result();
        }
        $this->response($kontak, 200); // untuk menampilkan response, dan 200 adalah status respon
 
    }   

    /*
    | Mengirim atau menambah data kontak baru menggunakan method POST
    | POST berfungsi untuk mengirimkan data baru dari client ke REST SERVER
    | POST disini berfungsi untuk menambahkan data baru berdasarkan id, nama, dan nomor
    */
    
    function index_post() {  //function untuk post
        // menyimpan data yang di post dalam variabel data
        $data = array(
                    'id'           => $this->post('id'),
                    'nama'          => $this->post('nama'),
                    'nomor'    => $this->post('nomor'));
        $insert = $this->db->insert('telepon', $data);
        if ($insert) { // jika data telah diinsertkan maka akan tampil responnya
            $this->response($data, 200); 
        } else { // jika tidak maka akan tampil respon fail dengan status 502
            $this->response(array('status' => 'fail', 502));
        }
    }

    /*
    | Memperbarui data kontak yang telah ada menggunakan method PUT
    | PUT berfungsi untuk memperbarui data yang telah ada pada server REST API
    | PUT disini berfungsi untuk memperbarui data yang ada di server REST API berdasarkan id pada tabel telepon
    */

    function index_put() { // function untuk put
        $id = $this->put('id'); // untuk memperbarui data dengan memanggil id
        $data = array(
                    'id'       => $this->put('id'),
                    'nama'          => $this->put('nama'),
                    'nomor'    => $this->put('nomor'));
        $this->db->where('id', $id);

        // untuk update atau memperbarui data yang telah diinputkan
        $update = $this->db->update('telepon', $data);
        if ($update) { //jika update selesai maka tampil respon 
            $this->response($data, 200);
        } else { ,, jika // jika update gagal maka akan tampil respon fail dengan status 502
            $this->response(array('status' => 'fail', 502));
        }
    }

    /*
    | Menghapus salah satu data kontak menggunakan method DELETE
    | DELETE berfungsi untuk menghapus data yang telah ada di server REST API
    | DELETE disini berfungsi untuk menghapus data yang ada di server REST API berdasarkan id pada tabel telepon
    */
    function index_delete() { // function untuk delete
        $id = $this->delete('id');
        $this->db->where('id', $id);
        $delete = $this->db->delete('telepon');
        // kondisi untuk hapus
        if ($delete) { 
            $this->response(array('status' => 'success'), 201);
        } else {
            $this->response(array('status' => 'fail', 502));
        }
    }
}     