# CI.3.E41181255_P4

## Langkah - Langkah Membuat REST API dengan CodeIgniter
REST merupakan  suatu gaya arsitektur perangkat lunak untuk untuk pendistibusian sistem hipermedia seperti www.

### Persiapan Yang Harus di Lakukan
1. Web Server, disini web server yang digunakan adalah xampp
2. Text Editor, sublime atau visual studio code, disini yang digunakan adalah visual studio code
3. Codeigniter dan library REST server yang diperlukan dapat diunduh di https://github.com/ardisaurus/ci-restserver.

### Instalasi REST SERVER API
Setelah folder rest server di download, langkah selanjutknya adalah meng-ekstrak folder rest server dan memindahkannya ke dalam folder htdocs 
pada direktori xampp, lalu rename foldernya menjadi rest_ci. Kemudian akses dengan link http://localhost/rest_ci/ci-restserver-master/index.php/rest_server (link ini disesuaikan berdasarkan penyimpanan dan nama direktori yang kita buat). Maka akan tampil gambar seperti dibawah ini :
![Instalasi Sukses](https://static.cdn-cdpl.com/source/18844/1_restserver.png)

### Konfigurasi Database
1. Buat Database dengan nama kontak dengan cara 
2. Kemudian buat tabel telepon dengan syntax sql :
```
CREATE TABLE IF NOT EXISTS `telepon` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) NOT NULL,
  `nomor` varchar(13) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;
```
3. Isikan data pada tabel telepon :
```
INSERT INTO `telepon` (`id`, `nama`, `nomor`) VALUES
(1, 'Orion', '08576666762'),
(2, 'Mars', '08576666770'),
(7, 'Alpha', '08576666765');
```
4. Pada text editor, buka application/config/database.php, lalu lakukan perubahan terhadap username dan database. Untuk username diisi dengan root, sedangkan databasenya diisi dengan nama database yang telah dibuat yaitu kontak.
```<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$active_group = 'default';
$query_builder = TRUE;

$db['default'] = array(
	'dsn'	=> '',
	'hostname' => 'localhost',
	'username' => 'root',
	'password' => '',
	'database' => 'kontak', //merupakan nama database yang digunakan 
	'dbdriver' => 'mysqli',
	'dbprefix' => '',
	'pconnect' => FALSE,
	'db_debug' => (ENVIRONMENT !== 'production'),
	'cache_on' => FALSE,
	'cachedir' => '',
	'char_set' => 'utf8',
	'dbcollat' => 'utf8_general_ci',
	'swap_pre' => '',
	'encrypt' => FALSE,
	'compress' => FALSE,
	'stricton' => FALSE,
	'failover' => array(),
	'save_queries' => TRUE
);
```
## GET
GET merupakan metode yang menyediakan akses baca pada sumber daya yang disediakan oleh REST API. Fungsi GET disini berfungsi untuk menampilkan data dari tabel telepon pada database kontak.
Fungsi GET dibua terlebih dahulu karena untuk memeriksa apakah terdapat property id pada address bar, sehingga data yang ditampilkan dapat diseleksu berdasarkan id.
Untuk melakukan fungsi GET, tambahkan kode pada controller, yaitu dengan membuat file kontak.php pada controller, lalu isikan code berikut
```<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Kontak extends REST_Controller {

    function __construct($config = 'rest') {
        parent::__construct($config);
        $this->load->database();
    }

    //Menampilkan data kontak
    function index_get() {
        $id = $this->get('id');
        if ($id == '') {
            $kontak = $this->db->get('telepon')->result();
        } else {
            $this->db->where('id', $id);
            $kontak = $this->db->get('telepon')->result();
        }
        $this->response($kontak, 200);
    }

}
?>
```
Kemudian lakukan test dengan menggunakan aplikasi postman, tuliskan linknya http://localhost/CI.3.E41181255_P4/rest_ci/ci-restserver-master/index.php/kontak (sesuai dengan direktori yang dibuat), pilih metode GET lalu send, maka akan tampil data dari tabel telepon

## POST 
Metode POST digunakan untuk mengirimkan data baru dari client ke server REST API. Disini POST digunakan untuk menambahkan kontak baru yang terdiri dari id, nama, dan nomor. 

Untuk mengirim atau menambah data baru maka masukkan code berikut pada controller :
```     function index_post() {
        $data = array(
                    'id'           => $this->post('id'),
                    'nama'          => $this->post('nama'),
                    'nomor'    => $this->post('nomor'));
        $insert = $this->db->insert('telepon', $data);
        if ($insert) {
            $this->response($data, 200);
        } else {
            $this->response(array('status' => 'fail', 502));
        }
    }
   ```
   Untuk menguji dengan postman tuliskan linknya http://localhost/CI.3.E41181255_P4/rest_ci/ci-restserver-master/index.php/kontak (sesuai dengan direktori yang dibuat)
   pada address bar, klik "Body". Pada menu dibawah address bar, pilih x-www-form-urlencoded, masukan key dan value yang diperlukan (id, nama, nomor), lalu klik "Send".
   Maka akan terlihat data yang telah dikirim menggunakan metode POST
   
   
   ## PUT 
   Metode PUT digunakan untuk memperbarui data yang telah ada di server REST API. Disini PUT digunakan untuk memperbarui data dengan id pada tabel telepon database kontak.
   Untuk menggunakan PUT maka tuliskan code berikut pada controller :
   
   ``` function index_put() {
        $id = $this->put('id');
        $data = array(
                    'id'       => $this->put('id'),
                    'nama'          => $this->put('nama'),
                    'nomor'    => $this->put('nomor'));
        $this->db->where('id', $id);
        $update = $this->db->update('telepon', $data);
        if ($update) {
            $this->response($data, 200);
        } else {
            $this->response(array('status' => 'fail', 502));
        }
    } ```
   Untuk menguji dengan postman tuliskan linknya http://localhost/CI.3.E41181255_P4/rest_ci/ci-restserver-master/index.php/kontak (sesuai dengan direktori yang dibuat)
   pada address bar, klik "Body". Pada menu dibawah address bar, pilih x-www-form-urlencoded, masukan key dan value yang diperlukan (id, nama, nomor), lalu klik "Send".
   Maka akan terlihat data yang telah diubah menggunakan metode PUT.
   
   
   ## DELETE
   Metode DELETE digunakan untuk menghapus data yang telah ada di server REST API. Disini DELETE digunakan untuk menghapus data berdasarkan id.
   Untuk menggunakan DELETE maka masukkan code berikut pada controller :
   ```
    function index_delete() {
        $id = $this->delete('id');
        $this->db->where('id', $id);
        $delete = $this->db->delete('telepon');
        if ($delete) {
            $this->response(array('status' => 'success'), 201);
        } else {
            $this->response(array('status' => 'fail', 502));
        }
    }
    ```
   Untuk menguji dengan postman tuliskan linknya http://localhost/CI.3.E41181255_P4/rest_ci/ci-restserver-master/index.php/kontak (sesuai dengan direktori yang dibuat)
   pada address bar, klik "Body". Pada menu dibawah address bar, pilih x-www-form-urlencoded, masukan key dan value yang diperlukan (id, nama, nomor), lalu klik "Send".
   Maka akan terlihat data yang telah dihapus menggunakan metode DELETE.
    

   
