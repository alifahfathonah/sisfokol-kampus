<?php
session_start();

//fungsi2
require("../../inc/config.php");
require("../../inc/fungsi.php");
require("../../inc/koneksi.php");
require("../../inc/cek/admbaak.php");
require("../../inc/class/paging.php");
$tpl = LoadTpl("../../template/index.html");

nocache;

//nilai
$filenya = "mhs_sp.php";
$judul = "Mahasiswa Peserta Semester Pendek";
$judulku = "[$baak_session : $nip2_session. $nm2_session]. $judul";
$judulx = $judul;
$tapelkd = nosql($_REQUEST['tapelkd']);
$progdi = nosql($_REQUEST['progdi']);
$kelkd = nosql($_REQUEST['kelkd']);
$s = nosql($_REQUEST['s']);
$page = nosql($_REQUEST['page']);
if ((empty($page)) OR ($page == "0"))
	{
	$page = "1";
	}

$ke = "$filenya?progdi=$progdi&kelkd=$kelkd&tapelkd=$tapelkd&page=$page";




//focus...
if (empty($progdi))
	{
	$diload = "document.formx.progdi.focus();";
	}
else if (empty($kelkd))
	{
	$diload = "document.formx.kelas.focus();";
	}
else if (empty($tapelkd))
	{
	$diload = "document.formx.tapel.focus();";
	}






//PROSES ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//jika batal
if ($_POST['btnBTL'])
	{
	//nilai
	$progdi = nosql($_POST['progdi']);
	$tapelkd = nosql($_POST['tapelkd']);
	$kelkd = nosql($_POST['kelkd']);
	$page = nosql($_POST['page']);

	//diskonek
	xfree($qbw);
	xclose($koneksi);

	//re-direct
	xloc($ke);
	exit();
	}





//jika hapus
if ($_POST['btnHPS'])
	{
	//nilai
	$progdi = nosql($_POST['progdi']);
	$tapelkd = nosql($_POST['tapelkd']);
	$kelkd = nosql($_POST['kelkd']);
	$page = nosql($_POST['page']);
	$jml = nosql($_POST['jml']);

	//ambil semua
	for ($i=1; $i<=$jml;$i++)
		{
		//ambil nilai
		$yuk = "item";
		$yuhu = "$yuk$i";
		$kdix = nosql($_POST["$yuhu"]);

		//hapus
		mysql_query("DELETE FROM mahasiswa_sp ".
						"WHERE kd_mhs = '$kdix' ".
						"AND kd_tapel = '$tapelkd'");
		}

	//diskonek
	xfree($qbw);
	xclose($koneksi);

	//re-direct
	xloc($ke);
	exit();
	}





//jika tambah
if ($_POST['btnSMPx'])
	{
	//nilai
	$progdi = nosql($_POST['progdi']);
	$tapelkd = nosql($_POST['tapelkd']);
	$kelkd = nosql($_POST['kelkd']);
	$page = nosql($_POST['page']);
	$e_siswa = nosql($_POST['e_siswa']);
	$e_info = cegah($_POST['e_info']);


	$mtgl = $_POST['datepicker1'];
	$mpecah1 = explode("/", $mtgl);
	$datepicker1_bln = $mpecah1[0];
	$datepicker1_tgl = $mpecah1[1];
	$datepicker1_thn = $mpecah1[2];	
	$e_tgl_ambil = "$datepicker1_thn:$datepicker1_bln:$datepicker1_tgl";



	//cek, 
	$qc = mysql_query("SELECT * FROM mahasiswa_sp ".
						"WHERE kd_tapel = '$tapelkd' ".
						"AND kd_mhs = '$e_siswa'");
	$rc = mysql_fetch_assoc($qc);
	$tc = mysql_num_rows($qc);


	//nek iyo
	if ($tc != 0)
		{
		//re-direct
		$pesan = "Data Sudah Ada. Harap Diperhatikan...!!.";
		pekem($pesan,$ke);
		exit();
		}
	else
		{
		//query
		mysql_query("INSERT INTO mahasiswa_sp(kd, kd_mhs, kd_tapel, ket, tgl, postdate) VALUES ".
						"('$x', '$e_siswa', '$tapelkd', '$e_info', '$e_tgl_ambil', '$today')");

		//diskonek
		xfree($qbw);
		xclose($koneksi);

		//re-direct
		xloc($ke);
		exit();
		}
	}



//jika simpan
if ($_POST['btnSMP2'])
	{
	//nilai
	$progdi = nosql($_POST['progdi']);
	$tapelkd = nosql($_POST['tapelkd']);
	$kelkd = nosql($_POST['kelkd']);
	$page = nosql($_POST['page']);
	$total = nosql($_POST['total']);

	for($i=1;$i<=$total;$i++)
		{
		//ambil nilai
		$kd = "kd";
		$kd1 = "$kd$i";
		$kdx = nosql($_POST["$kd1"]);
		
		$abs = "i_info";
		$abs1 = "$abs$i";
		$absxx = nosql($_POST["$abs1"]);



		//query
		mysql_query("UPDATE mahasiswa_sp SET ket = '$absxx' ".
						"WHERE kd_mhs = '$kdx' ".
						"AND kd_tapel = '$tapelkd'");
		}

	//diskonek
	xfree($qbw);
	xclose($koneksi);

	//re-direct
	xloc($ke);
	exit();
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


//isi *START
ob_start();


//js
require("../../inc/js/jumpmenu.js");
require("../../inc/js/swap.js");
require("../../inc/js/checkall.js");
require("../../inc/js/number.js");
require("../../inc/menu/admbaak.php");
xheadline($judul);

//view //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
?>

<script type="text/javascript">
$(document).ready(function() {
$(function() {
	$('#datepicker1').datepicker({
		changeMonth: true,
		yearRange: "-100:+10",
		changeYear: true
		});

	$('#datepicker2').datepicker({
		changeMonth: true,
		yearRange: "-100:+10",
		changeYear: true
		});
	});

	
});
</script>




<?php

echo '<form name="formx" method="post" action="'.$filenya.'">
<table bgcolor="'.$warnaover.'" width="100%" border="0" cellspacing="0" cellpadding="3">
<tr>
<td>
Program Studi : ';
echo "<select name=\"progdi\" onChange=\"MM_jumpMenu('self',this,0)\">";
//terpilih
$qtpx = mysql_query("SELECT * FROM m_progdi ".
			"WHERE kd = '$progdi'");
$rowtpx = mysql_fetch_assoc($qtpx);
$tpx_kd = nosql($rowtpx['kd']);
$tpx_nama = balikin($rowtpx['nama']);

echo '<option value="'.$tpx_kd.'" selected>'.$tpx_nama.'</option>';

$qtp = mysql_query("SELECT * FROM m_progdi ".
			"WHERE kd <> '$progdi' ".
			"ORDER BY nama ASC");
$rowtp = mysql_fetch_assoc($qtp);

do
	{
	$tpkd = nosql($rowtp['kd']);
	$tpnama = balikin($rowtp['nama']);

	echo '<option value="'.$filenya.'?progdi='.$tpkd.'">'.$tpnama.'</option>';
	}
while ($rowtp = mysql_fetch_assoc($qtp));

echo '</select>,


Jenis : ';
echo "<select name=\"kelas\" onChange=\"MM_jumpMenu('self',this,0)\">";

//terpilih
$qbtx = mysql_query("SELECT * FROM m_kelas ".
			"WHERE kd = '$kelkd'");
$rowbtx = mysql_fetch_assoc($qbtx);
$btxkd = nosql($rowbtx['kd']);
$btxkelas = nosql($rowbtx['kelas']);

echo '<option value="'.$btxkd.'">'.$btxkelas.'</option>';

$qbt = mysql_query("SELECT * FROM m_kelas ".
			"WHERE kd <> '$kelkd' ".
			"ORDER BY no ASC");
$rowbt = mysql_fetch_assoc($qbt);

do
	{
	$btkd = nosql($rowbt['kd']);
	$btkelas = nosql($rowbt['kelas']);

	echo '<option value="'.$filenya.'?progdi='.$progdi.'&kelkd='.$btkd.'">'.$btkelas.'</option>';
	}
while ($rowbt = mysql_fetch_assoc($qbt));

echo '</select>,


Tahun Akademik : ';
echo "<select name=\"tapel\" onChange=\"MM_jumpMenu('self',this,0)\">";

//terpilih
$qtpx = mysql_query("SELECT * FROM m_tapel ".
			"WHERE kd = '$tapelkd'");
$rowtpx = mysql_fetch_assoc($qtpx);
$tpx_kd = nosql($rowtpx['kd']);
$tpx_thn1 = nosql($rowtpx['tahun1']);
$tpx_thn2 = nosql($rowtpx['tahun2']);

echo '<option value="'.$tpx_kd.'">'.$tpx_thn1.'/'.$tpx_thn2.'</option>';

$qtp = mysql_query("SELECT * FROM m_tapel ".
			"WHERE kd <> '$tapelkd' ".
			"ORDER BY tahun1 ASC");
$rowtp = mysql_fetch_assoc($qtp);

do
	{
	$tpkd = nosql($rowtp['kd']);
	$tpth1 = nosql($rowtp['tahun1']);
	$tpth2 = nosql($rowtp['tahun2']);

	echo '<option value="'.$filenya.'?progdi='.$progdi.'&kelkd='.$kelkd.'&tapelkd='.$tpkd.'">'.$tpth1.'/'.$tpth2.'</option>';
	}
while ($rowtp = mysql_fetch_assoc($qtp));

echo '</select>
</td>
</tr>
</table>
<br>';


//nek blm dipilih
if (empty($progdi))
	{
	echo '<p>
	<strong><font color="#FF0000"><strong>PROGRAM STUDI Belum Ditentukan. Harap Diperhatikan...!</strong></font></strong>
	</p>';
	}
else if (empty($kelkd))
	{
	echo '<p>
	<strong><font color="#FF0000"><strong>JENIS Belum Ditentukan. Harap Diperhatikan...!</strong></font></strong>
	</p>';
	}

else if (empty($tapelkd))
	{
	echo '<p>
	<strong><font color="#FF0000"><strong>TAHUN PELAJARAN Belum Ditentukan. Harap Diperhatikan...!</strong></font></strong>
	</p>';
	}

else
	{
	//query
	$p = new Pager();
	$start = $p->findStart($limit);

/*
	$sqlcount = "SELECT m_mahasiswa.*, m_mahasiswa.kd AS mskd, mahasiswa_sp.* ".
					"FROM m_mahasiswa, mahasiswa_kelas, mahasiswa_sp ".
					"WHERE mahasiswa_kelas.kd_mahasiswa = m_mahasiswa.kd ".
					"AND mahasiswa_sp.kd_mhs = m_mahasiswa.kd ".
					"AND mahasiswa_kelas.kd_progdi = '$progdi' ".
					"AND mahasiswa_kelas.kd_kelas = '$kelkd' ".
					"AND mahasiswa_sp.kd_tapel = '$tapelkd' ".
					"ORDER BY m_mahasiswa.nama ASC";
	$sqlresult = $sqlcount;
*/
	$sqlcount = "SELECT DISTINCT(m_mahasiswa.kd) AS mskd ".
					"FROM m_mahasiswa, mahasiswa_kelas, mahasiswa_sp ".
					"WHERE mahasiswa_kelas.kd_mahasiswa = m_mahasiswa.kd ".
					"AND mahasiswa_sp.kd_mhs = m_mahasiswa.kd ".
					"AND mahasiswa_kelas.kd_progdi = '$progdi' ".
					"AND mahasiswa_kelas.kd_kelas = '$kelkd' ".
					"AND mahasiswa_sp.kd_tapel = '$tapelkd' ".
					"ORDER BY m_mahasiswa.nama ASC";
	$sqlresult = $sqlcount;


	$count = mysql_num_rows(mysql_query($sqlcount));
	$pages = $p->findPages($count, $limit);
	$result = mysql_query("$sqlresult LIMIT ".$start.", ".$limit);
	$target = "$filenya?progdi=$progdi&tapelkd=$tapelkd&kelkd=$kelkd";
	$pagelist = $p->pageList($_GET['page'], $pages, $target);
	$data = mysql_fetch_array($result);

	//tambah
	echo '<select name="e_siswa">
    <option value="" selected>--</option>';

	//query
	$qks = mysql_query("SELECT DISTINCT(m_mahasiswa.kd) AS mskd ".
							"FROM m_mahasiswa, mahasiswa_kelas ".
							"WHERE mahasiswa_kelas.kd_mahasiswa = m_mahasiswa.kd ".
							"AND mahasiswa_kelas.kd_progdi = '$progdi' ".
							"AND mahasiswa_kelas.kd_kelas = '$kelkd' ".
							"ORDER BY m_mahasiswa.nama ASC");
	$rowks = mysql_fetch_assoc($qks);

	do
		{
		$kdks = nosql($rowks['mskd']);
		
		
		//detail e
		$qku = mysql_query("SELECT * FROM m_mahasiswa ".
								"WHERE kd = '$kdks'");
		$rku = mysql_fetch_assoc($qku);
		$nisks = nosql($rku['nim']);
		$nmks = balikin2($rku['nama']);

		echo '<option value="'.$kdks.'">('.$nisks.') '.$nmks.'</option>';
		}
	while ($rowks = mysql_fetch_assoc($qks));

	echo '</select>
	<br>
	<br>
	
	Tanggal Ikut  :
	<br> 
	<input name="datepicker1" id="datepicker1" type="text" value="'.$e_tgl_awal.'" size="10">	
	<br>
	<br>
	

	Ket. :
	<br>
	<input name="e_info" type="text" value="'.$e_informasi.'" size="20">
	<br>
	<br>
	
	<input name="progdi" type="hidden" value="'.$progdi.'">
	<input name="tapelkd" type="hidden" value="'.$tapelkd.'">
	<input name="kelkd" type="hidden" value="'.$kelkd.'">
	<button name="btnSMPx" type="submit" value="save" class="search_btn"><img src="'.$sumber.'/img/save.png" alt="save">SIMPAN</button>

	<br>
	<br>
	<table width="1100" border="1" cellpadding="3" cellspacing="0">
 	<tr bgcolor="'.$warnaheader.'">
	<td width="1" valign="top">&nbsp;</td>
	<td width="50" valign="top"><strong>NIM</strong></td>
	<td valign="top"><strong>NAMA</strong></td>
	<td valign="top"><strong>TANGGAL IKUT</strong></td>
	<td valign="top"><strong>KETERANGAN</strong></td>
	</tr>';

	//nek ada
	if ($count != 0)
		{
		do
  			{
			if ($warna_set ==0)
				{
				$warna = $warna01;
				$warna_set = 1;
				}
			else
				{
				$warna = $warna02;
				$warna_set = 0;
				}

			$nomer = $nomer + 1;

			$i_kd = nosql($data['mskd']);
			
			
			//detail e
			$qku = mysql_query("SELECT m_mahasiswa.*, mahasiswa_sp.* ".
									"FROM m_mahasiswa, mahasiswa_sp ".
									"WHERE mahasiswa_sp.kd_mhs = m_mahasiswa.kd ".
									"AND mahasiswa_sp.kd_tapel = '$tapelkd' ".
									"AND m_mahasiswa.kd = '$i_kd'");
			$rku = mysql_fetch_assoc($qku);
			$i_nim = nosql($rku['nim']);
			$i_nama = balikin($rku['nama']);
			$i_tgl_ambil = $rku['tgl'];
			$i_ket = balikin($rku['ket']);


			echo "<tr valign=\"top\" bgcolor=\"$warna\" onmouseover=\"this.bgColor='$warnaover';\" onmouseout=\"this.bgColor='$warna';\">";
			echo '<td valign="top">
			<input name="kd'.$nomer.'" type="hidden" value="'.$i_kd.'">
			<input name="item'.$nomer.'" type="checkbox" value="'.$i_kd.'">
			</td>
  			<td valign="top">'.$i_nim.'</td>
  			<td valign="top">'.$i_nama.'</td>
  			<td valign="top">'.$i_tgl_ambil.'</td>

  			<td valign="top">
  			<input name="i_info'.$nomer.'" type="text" value="'.$i_ket.'" size="20">
  			</td>
			</tr>';
			}
		while ($data = mysql_fetch_assoc($result));
		}

	echo '</table>

	<table width="900" border="0" cellspacing="0" cellpadding="3">
	<tr>
	<td align="right">Total : <font color="#FF0000"><strong>'.$count.'</strong></font> Data. '.$pagelist.'</td>
	</tr>
	<tr>
	<td align="right">
	<button name="btnALL" type="button" value="SEMUA" class="search_btn" onClick="checkAll('.$limit.')"><img src="'.$sumber.'/img/checked.png" alt="all">SEMUA</button>
	<button name="btnBTL" type="reset" value="BATAL" class="search_btn"><img src="'.$sumber.'/img/reset.png" alt="reset">BATAL</button>
	<button name="btnHPS" type="submit" value="HAPUS" class="search_btn"><img src="'.$sumber.'/img/trash.png" alt="delete">HAPUS</button>
	<button name="btnSMP2" type="submit" value="save" class="search_btn"><img src="'.$sumber.'/img/save.png" alt="save">SIMPAN</button>
	<input name="jml" type="hidden" value="'.$limit.'">
	<input name="page" type="hidden" value="'.$page.'">
	<input name="total" type="hidden" value="'.$count.'">
	</td>
	</tr>
	</table>';
	}

echo '</form>
<br>
<br>
<br>';
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//isi
$isi = ob_get_contents();
ob_end_clean();


require("../../inc/niltpl.php");


//diskonek
xfree($qbw);
xclose($koneksi);
exit();
?>