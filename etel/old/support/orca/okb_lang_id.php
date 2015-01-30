<?php /* ***** Orca Knowledgebase - Indonesian Language File ***** */

/* ***************************************************************
* Orca Knowledgebase v2.1b
*  Sistem knowledgebase yang kecil dan efisien
* Hak Cipta (C) 2004 GreyWyvern
*
* Program ini didistribusikan dibawah ketetapan GPL
*   - http://www.gnu.org/licenses/gpl.txt
* 
* Lihat berkas readme.txt untuk instruksi instalasi.
*
* Diterjemahkan oleh Ahmad Imron ( http://www.ahmadimron.com/ )
*************************************************************** */

$lang['charset'] = "ISO-8859-1";
setlocale(LC_TIME, array("id_ID", "ind"));
$pageEncoding = 2;  // Final Page Encoding
                    //  1 - UTF-8
                    //  2 - ISO-8859-1
                    //  3 - Other

$sData['dateformat'] = "%d %b, %Y  %X";  // see http://www.php.net/strftime


/* ************************************************************ */
/* ***** User GUI ********************************************* */
/* ************************************************************ */

$lang['term1'] = "Pencarian";
$lang['term2'] = "Bersihkan";
$lang['term3'] = "Pergi ke ID Pertanyaan";
$lang['term4'] = "Semua Kategori";
$lang['term5'] = "Semua Subkategori";
$lang['term6'] = "Pergi";
$lang['term7'] = "Diperbaharui";
$lang['term8'] = "Kategori";
$lang['term9'] = "Subkategori";
$lang['terma'] = "ID Pertanyaan %s tidak ada";
$lang['termb'] = "Kembali";
$lang['termc'] = "<strong>%d</strong> Hasil(s)";
$lang['termd'] = "Pencarian di: %s";
$lang['terme'] = "Menampilkan: %s";
$lang['termf'] = "Pertanyaan";
$lang['termg'] = "Tidak ada hasil ditemukan";
$lang['termh'] = "Sebelumnya";
$lang['termi'] = "Menampilkan pertanyaan %1\$d ke %2\$d";
$lang['termj'] = "Berikutnya";
$lang['termk'] = "Halaman sebelumnya";
$lang['terml'] = "Halaman berikutnya";
$lang['termm'] = "Tidak dapat menemukan apa yang anda cari?";
$lang['termn'] = "Kirim pertanyaan ke administrator knowledgebase";
$lang['termo'] = "Pertanyaan anda";
$lang['termp'] = "Alamat email anda";
$lang['termq'] = "Terima kasih!";
$lang['termr'] = "Pertanyaan anda telah dikirimkan";
$lang['terms'] = "Solusi";
$lang['termt'] = "Saringan";


/* ***** Email ************************************************ */
$lang['email1']['subject'] = "%s - Pertanyaan yang masuk";
$lang['email1']['message'] = <<<ORCA
<%1\$s> menanyakan pertanyaan ini lewat %2\$s:

%3\$s

____________________________________________________________
%2\$s
ORCA;


/* ************************************************************ */
/* ***** Control Panel **************************************** */
/* ************************************************************ */
$lang['misc1'] = "Pengurutan daftar berdasar %s";
$lang['misc2'] = " - Panel Kontrol";
$lang['misc3'] = "Panel Kontrol Knowledgebase";
$lang['misc4'] = "Anda tidak logged in";
$lang['misc5'] = "Login";
$lang['misc6'] = "Anda logged in";
$lang['misc8'] = "Logout";
$lang['misc9'] = "Melanjutkan Pengeditan Knowledgebase";
$lang['misca'] = "Kembali ke Mode Pengeditan";
$lang['miscb'] = "Pengatur Upload Berkas";
$lang['miscc'] = "Berkas Aksesori Upload";
$lang['miscd'] = "Kesalahan telah terjadi";
$lang['misce'] = "Aksi sebelumnya menyebabkan kesalahan(s) ini:";
$lang['miscf'] = "Segarkan halaman ini";
$lang['miscg'] = "Bersihkan salah";


/* ***** File Upload Manager ********************************** */
$lang['misch'] = "Tidak dapat mengakses direktori berkas. tolong chmod direktori %s dengan nilai 777 (xrw-xrw-xrw)";
$lang['misci'] = "Segarkan";
$lang['miscj'] = "Berkas:";
$lang['misck'] = "Direktori Penyimpanan:";
$lang['miscl'] = "Tipe berkas diijinkan:";
$lang['miscm'] = "Batas ukuran berkas:";
$lang['miscn'] = "Upload";
$lang['misco'] = "Bersihkan";
$lang['miscp'] = "Nama berkas";
$lang['miscq'] = "Tipe berkas";
$lang['miscr'] = "Ukuran berkas";
$lang['miscs'] = "Hapus";
$lang['misct'] = "Hapus bakas";
$lang['miscu'] = "Apakah anda yakin akan menghapus berkas ini?";
$lang['miscv'] = "Kembali / Segarkan";


/* ***** Main Controls **************************************** */
$lang['miscw'] = "Kontrol Kategori";
$lang['miscx'] = "Tambah Kategori";
$lang['miscy'] = "Tambah";
$lang['miscz'] = "Pilih Kategori Kerja";
$lang['mis_1'] = "Pilih Kategori";
$lang['mis_2'] = "Tidak ada";
$lang['mis_3'] = "Pilih";
$lang['mis_4'] = "Rename Kategori";
$lang['mis_5'] = "Nama Baru";
$lang['mis_6'] = "Rename";
$lang['mis_7'] = "Hapus Kategori";
$lang['mis_8'] = "Hapus";
$lang['mis_9'] = "Kontrol Subkategori";
$lang['mis_a'] = "Tambah Subkategori";
$lang['mis_b'] = "Pilih Subdirektori Kerja";
$lang['mis_c'] = "Pilih Subkategori";
$lang['mis_d'] = "Rename Subkategori";
$lang['mis_e'] = "Hapus Subkategori";
$lang['mis_f'] = "Kontrol Pertanyaan";
$lang['mis_g'] = "Anda telah mengubah Kategori Pertanyaan ini";
$lang['mis_h'] = "Untuk menyelesaikan pengeditan ini, tolong pilih atau buat Subkategori baru untuk Pertanyaan ini";
$lang['mis_i'] = "Subkategori";
$lang['mis_j'] = "Subkategori Baru";
$lang['mis_k'] = "Batalkan Pengeditan Pertanyaan";
$lang['mis_l'] = "Batalkan";
$lang['mis_m'] = "Pengeditan Selesai";
$lang['mis_n'] = "Kategori";
$lang['mis_o'] = "Online";
$lang['mis_p'] = "Pertanyaan";
$lang['mis_q'] = "Jawaban<br /><small><em>HTML Diijinkan</em></small>";
$lang['mis_r'] = "Pilih berkas";
$lang['mis_s'] = "Tambah Link";
$lang['mis_t'] = "Tambah Gambar";
$lang['mis_u'] = "Kata kunci<br /><small><em>Dipisahkan dengan spasi</em></small>";
$lang['mis_v'] = "Batalkan Tambahan Pertanyaan";
$lang['mis_w'] = "ID Pertanyaan #";
$lang['mis_x'] = "Terakhir Diperbaharui";
$lang['mis_y'] = "Hits";
$lang['mis_z'] = "Tambah Pertanyaan dalam Subkategori ini";
$lang['mi__1'] = "Tambah Pertanyaan dalam Kategori ini";
$lang['mi__2'] = "Hapus Pertanyaan dengan ID #";
$lang['mi__3'] = "Edit Pertanyaan dengan ID #";
$lang['mi__4'] = "Edit";


/* ***** Database List Display ******************************** */
$lang['mi__5'] = "Database Pertanyaan";
$lang['mi__6'] = "Menampilkan:";
$lang['mi__7'] = "Semua Pertanyaan";
$lang['mi__8'] = "Q ID";
$lang['mi__9'] = "E";	// Short form for Edit


/* ***** Pagination/Footer ************************************ */
$lang['mi__a'] = "Halaman sebelumnya";
$lang['mi__b'] = "Sebelumnya";
$lang['mi__c'] = "Menampilkan Pertanyaan %1\$d ke %2\$d";
$lang['mi__d'] = "Halaman Berikutnya";
$lang['mi__e'] = "Berikutnya";
$lang['mi__f'] = "** Tidak ada pertanyaan pada Subcategory ini**";
$lang['mi__g'] = "** Tidak ada pertanyaan pada Kategori ini **";
$lang['mi__h'] = "** Tidak ada pertanyaan pada database ini**";


/* ***** Error Messages *************************************** */
$lang['err1'] = "Tipe berkas tidak diperbolehkan (%s), atau tidak ada berkas yang diupload";
$lang['err2'] = "Berkas lebih besar dari %1\$d BYTES (%2\$d KB) tidak diijinkan";
$lang['err3'] = "Nama berkas Sudah Ada";
$lang['err4'] = "Upload gagal.  Anda harus chmod target direktori ke 777";
$lang['err5'] = "Karakter tidak valid pada Nama Kategori";
$lang['err6'] = "Kategori Sudah Ada";
$lang['err7'] = "Karakter tidak valid pada Nama Subkategori";
$lang['err8'] = "Subkategori Sudah Ada";
$lang['err9'] = "Tidak ada Nama Subkategori yang Dimasukkan";
$lang['erra'] = "Pertanyaan Sudah Ada";
$lang['errb'] = "ID Pertanyaan <strong>%s</strong> tidak ada";
$lang['errc'] = "Tidak ada Nama Subkategori Dimasukkan";
$lang['errd'] = "Tidak ada Pertanyaan Dimasukkan";
$lang['erre'] = "Tidak ada Jawaban Dimasukkan";


while (list($key, $value) = each($lang)) {
  if (!is_array($value) && $key != "charset") {
    if ($pageEncoding == 3) {
      $lang[$key] = htmlentities($value, ENT_COMPAT, "ISO-8859-1");
      $lang[$key] = str_replace("&gt;", ">", $lang[$key]);
      $lang[$key] = str_replace("&lt;", "<", $lang[$key]);
    } else if ($pageEncoding == 1) $lang[$key] = utf8_encode($value);
  }
}

?>