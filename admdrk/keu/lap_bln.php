<?php
///////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////
/////// SISFOKOL-KAMPUS         ///////
///////////////////////////////////////////////////////////
/////// Dibuat oleh :                               ///////
/////// Agus Muhajir, S.Kom                         ///////
/////// URL 	: http://sisfokol.wordpress.com     ///////
/////// E-Mail	:                                   ///////
///////     * hajirodeon@yahoo.com                  ///////
///////     * hajirodeon@gmail.com                  ///////
/////// HP/SMS	: 081-829-88-54                     ///////
///////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////







session_start();

//fungsi - fungsi
require("../../inc/config.php");
require("../../inc/fungsi.php");
require("../../inc/koneksi.php");
require("../../inc/cek/admdrk.php");
$tpl = LoadTpl("../../template/index.html");


nocache;

//nilai
$filenya = "lap_bln.php";
$jnskd = nosql($_REQUEST['jnskd']);
$progdi = nosql($_REQUEST['progdi']);
$kelkd = nosql($_REQUEST['kelkd']);
$tapelkd = nosql($_REQUEST['tapelkd']);
$smtkd = nosql($_REQUEST['smtkd']);
$s = nosql($_REQUEST['s']);
$ubln = nosql($_REQUEST['ubln']);
$uthn = nosql($_REQUEST['uthn']);


//ketahui jenis keuangan
$qdt = mysql_query("SELECT * FROM m_keu_jenis ".
			"WHERE kd = '$jnskd'");
$rdt = mysql_fetch_assoc($qdt);
$dt_kd = nosql($rdt['kd']);
$dt_jenis = balikin($rdt['nama']);




//judul halaman
$judul = "Lap.Bulanan : $dt_jenis";
$judulku = "$judul  [$drk_session : $nip1_session. $nm1_session]";
$juduli = $judul;
$ke = "$filenya?jnskd=$jnskd&progdi=$progdi&kelkd=$kelkd&tapelkd=$tapelkd&smtkd=$smtkd&uthn=$uthn&ubln=$ubln";



//focus...
if (empty($progdi))
	{
	$diload = "document.formx.progdi.focus();isodatetime();";
	}
else if (empty($kelkd))
	{
	$diload = "document.formx.kelas.focus();isodatetime();";
	}
else if (empty($tapelkd))
	{
	$diload = "document.formx.tapel.focus();isodatetime();";
	}
else if (empty($ubln))
	{
	$diload = "document.formx.ublnx.focus();";
	}






//isi *START
ob_start();

//js
require("../../inc/js/jumpmenu.js");
require("../../inc/js/swap.js");
require("../../inc/menu/admdrk.php");
xheadline($judul);

//view //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
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

	echo '<option value="'.$filenya.'?jnskd='.$jnskd.'&progdi='.$tpkd.'">'.$tpnama.'</option>';
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

	echo '<option value="'.$filenya.'?jnskd='.$jnskd.'&progdi='.$progdi.'&kelkd='.$btkd.'">'.$btkelas.'</option>';
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
						"ORDER BY tahun1 DESC");
$rowtp = mysql_fetch_assoc($qtp);

do
	{
	$tpkd = nosql($rowtp['kd']);
	$tpth1 = nosql($rowtp['tahun1']);
	$tpth2 = nosql($rowtp['tahun2']);

	echo '<option value="'.$filenya.'?jnskd='.$jnskd.'&progdi='.$progdi.'&kelkd='.$kelkd.'&tapelkd='.$tpkd.'">'.$tpth1.'/'.$tpth2.'</option>';
	}
while ($rowtp = mysql_fetch_assoc($qtp));

echo '</select>
</td>
</tr>
</table>

<table bgcolor="'.$warna02.'" width="100%" border="0" cellspacing="0" cellpadding="3">
<tr>
<td>
Bulan : ';
echo "<select name=\"ublnx\" onChange=\"MM_jumpMenu('self',this,0)\">";
echo '<option value="'.$ubln.''.$uthn.'" selected>'.$arrbln[$ubln].' '.$uthn.'</option>';
for ($i=1;$i<=12;$i++)
	{
	//nilainya
	if ($i<=6) //bulan juli sampai desember
		{
		$ibln = $i + 6;

		echo '<option value="'.$filenya.'?jnskd='.$jnskd.'&progdi='.$progdi.'&kelkd='.$kelkd.'&tapelkd='.$tapelkd.'&utgl='.$utgl.'&ubln='.$ibln.'&uthn='.$tpx_thn1.'">'.$arrbln[$ibln].' '.$tpx_thn1.'</option>';
		}

	else if ($i>6) //bulan januari sampai juni
		{
		$ibln = $i - 6;

		echo '<option value="'.$filenya.'?jnskd='.$jnskd.'&progdi='.$progdi.'&kelkd='.$kelkd.'&tapelkd='.$tapelkd.'&utgl='.$utgl.'&ubln='.$ibln.'&uthn='.$tpx_thn2.'">'.$arrbln[$ibln].' '.$tpx_thn2.'</option>';
		}
	}

echo '</select>
[<a href="lap_bln_prt.php?jnskd='.$jnskd.'&progdi='.$progdi.'&kelkd='.$kelkd.'&tapelkd='.$tapelkd.'&ubln='.$ubln.'&uthn='.$uthn.'"><img src="'.$sumber.'/img/print.gif" border="0" width="16" height="16"></a>]
</td>
</tr>
</table>';


//nek blm dipilih
if (empty($progdi))
	{
	echo '<p>
	<font color="#FF0000"><strong>PROGRAM PENDIDIKAN Belum Dipilih...!</strong></font>
	</p>';
	}

else if (empty($kelkd))
	{
	echo '<p>
	<font color="#FF0000"><strong>JENIS Belum Dipilih...!</strong></font>
	</p>';
	}

else if (empty($tapelkd))
	{
	echo '<p>
	<font color="#FF0000"><strong>TAHUN PELAJARAN Belum Dipilih...!</strong></font>
	</p>';
	}
else if (empty($ubln))
	{
	echo '<p>
	<font color="#FF0000"><strong>BULAN Belum Dipilih...!</strong></font>
	</p>';
	}
else
	{
	//mendapatkan jumlah tanggal maksimum dalam suatu bulan
	$tgl = 0;
	$bulan = $ubln;
	$bln = $bulan + 1;
	$thn = $uthn;

	$lastday = mktime (0,0,0,$bln,$tgl,$thn);

	//total tanggal dalam sebulan
	$tkhir = strftime ("%d", $lastday);

	//lopping tgl
	for ($i=1;$i<=$tkhir;$i++)
		{
		//ketahui harinya
		$day = $i;
		$month = $bulan;
		$year = $thn;


		//mencari hari
		$a = substr($year, 2);
			//mengambil dua digit terakhir tahun

		$b = (int)($a/4);
			//membagi tahun dengan 4 tanpa memperhitungkan sisa

		$c = $month;
			//mengambil angka bulan

		$d = $day;
			//mengambil tanggal

		$tot1 = $a + $b + $c + $d;
			//jumlah sementara, sebelum dikurangani dengan angka kunci bulan

		//kunci bulanan
		if ($c == 1)
			{
			$kunci = "2";
			}

		else if ($c == 2)
			{
			$kunci = "7";
			}

		else if ($c == 3)
			{
			$kunci = "1";
			}

		else if ($c == 4)
			{
			$kunci = "6";
			}

		else if ($c == 5)
			{
			$kunci = "5";
			}

		else if ($c == 6)
			{
			$kunci = "3";
			}

		else if ($c == 7)
			{
			$kunci = "2";
			}

		else if ($c == 8)
			{
			$kunci = "7";
			}

		else if ($c == 9)
			{
			$kunci = "5";
			}

		else if ($c == 10)
			{
			$kunci = "4";
			}

		else if ($c == 11)
			{
			$kunci = "2";
			}

		else if ($c == 12)
			{
			$kunci = "1";
			}

		$total = $tot1 - $kunci;

		//angka hari
		$hari = $total%7;

		//jika angka hari == 0, sebenarnya adalah 7.
		if ($hari == 0)
			{
			$hari = ($hari +7);
			}

		//kabisat, tahun habis dibagi empat alias tanpa sisa
		$kabisat = (int)$year % 4;

		if ($kabisat ==0)
			{
			$hri = $hri-1;
			}



		//hari ke-n
		if ($hari == 3)
			{
			$hri = 4;
			$dino = "Rabu";
			}

		else if ($hari == 4)
			{
			$hri = 5;
			$dino = "Kamis";
			}

		else if ($hari == 5)
			{
			$hri = 6;
			$dino = "Jum'at";
			}

		else if ($hari == 6)
			{
			$hri = 7;
			$dino = "Sabtu";
			}

		else if ($hari == 7)
			{
			$hri = 1;
			$dino = "Minggu";
			}

		else if ($hari == 1)
			{
			$hri = 2;
			$dino = "Senin";
			}

		else if ($hari == 2)
			{
			$hri = 3;
			$dino = "Selasa";
			}


		//nek minggu, abang ngi wae
		if ($hri == 1)
			{
			$warna = "red";
			$mggu_attr = "disabled";
			}
		else
			{
			if ($warna_set ==0)
				{
				$warna = $warna01;
				$warna_set = 1;
				$mggu_attr = "";
				}
			else
				{
				$warna = $warna02;
				$warna_set = 0;
				$mggu_attr = "";
				}
			}

		//nilai tanggal
		$i_tgl_bayar = "$dino, $i $arrbln[$ubln] $uthn";


		echo '<table width="600" border="0" cellspacing="0" cellpadding="3">
		<tr valign="top">
		<td><strong><font color="'.$warnatext.'">'.$i_tgl_bayar.'</font></strong></td>
		</tr>
		</table>';

		echo '<table width="600" border="1" cellspacing="0" cellpadding="3">
		<tr valign="top" bgcolor="'.$warnaheader.'">
		<td width="50"><strong><font color="'.$warnatext.'">NIM</font></strong></td>
		<td><strong><font color="'.$warnatext.'">Nama</font></strong></td>
		<td width="50"><strong><font color="'.$warnatext.'">Semester</font></strong></td>
		<td width="200" align="center"><strong><font color="'.$warnatext.'">Nominal</font></strong></td>
		</tr>';


		//query bayarnya...
		$qcc1 = mysql_query("SELECT m_mahasiswa.*, ".
					"mahasiswa_keu.kd AS pkd, ".
					"mahasiswa_keu.kd_mahasiswa AS swkd ".
					"FROM mahasiswa_keu, m_mahasiswa ".
					"WHERE mahasiswa_keu.kd_mahasiswa = m_mahasiswa.kd ".
					"AND mahasiswa_keu.kd_jenis = '$jnskd' ".
					"AND mahasiswa_keu.kd_tapel = '$tapelkd' ".
					"AND mahasiswa_keu.nilai <> '' ".
					"AND round(DATE_FORMAT(mahasiswa_keu.tgl_bayar, '%d')) = '$i' ".
					"AND round(DATE_FORMAT(mahasiswa_keu.tgl_bayar, '%m')) = '$ubln' ".
					"AND round(DATE_FORMAT(mahasiswa_keu.tgl_bayar, '%Y')) = '$uthn' ".
					"ORDER BY round(m_mahasiswa.nim) ASC");
		$rcc1 = mysql_fetch_assoc($qcc1);
		$tcc1 = mysql_num_rows($qcc1);

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

			$i_nomer = $i_nomer + 1;
			$i_pkd = nosql($rcc1['pkd']);
			$i_swkd = nosql($rcc1['swkd']);
			$i_nim = nosql($rcc1['nim']);
			$i_nama = balikin($rcc1['nama']);


			//jumlah bayar
			$qjmx = mysql_query("SELECT * FROM mahasiswa_keu ".
						"WHERE kd_jenis = '$jnskd' ".
						"AND kd_tapel = '$tapelkd' ".
						"AND nilai <> '' ".
						"AND round(DATE_FORMAT(tgl_bayar, '%d')) = '$i' ".
						"AND round(DATE_FORMAT(tgl_bayar, '%m')) = '$ubln' ".
						"AND round(DATE_FORMAT(tgl_bayar, '%Y')) = '$uthn' ".
						"AND kd_mahasiswa = '$i_swkd' ".
						"AND kd = '$i_pkd'");
			$rjmx = mysql_fetch_assoc($qjmx);
			$tjmx = mysql_num_rows($qjmx);
			$jmx_nilai = nosql($rjmx['nilai']);



			//ketahui kode mahasiswa, dari suatu mahasiswa_kelas
			$qske = mysql_query("SELECT mahasiswa_kelas.*, m_tapel.* ".
						"FROM mahasiswa_kelas, m_tapel ".
						"WHERE mahasiswa_kelas.kd_tapel = m_tapel.kd ".
						"AND mahasiswa_kelas.kd_mahasiswa = '$i_swkd' ".
						"AND m_tapel.kd = '$tapelkd'");
			$rske = mysql_fetch_assoc($qske);
			$tske = mysql_num_rows($qske);


			//semester terakhir
			$qnil = mysql_query("SELECT * FROM mahasiswa_kelas ".
						"WHERE kd_progdi = '$progdi' ".
						"AND kd_kelas = '$kelkd' ".
						"AND kd_tapel = '$tapelkd' ".
						"AND kd_mahasiswa = '$i_swkd'");
			$rnil = mysql_fetch_assoc($qnil);
			$tnil = mysql_num_rows($qnil);
			$nil_smtkd = nosql($rnil['kd_smt']);

			//smt
			$qkelx = mysql_query("SELECT * FROM m_smt ".
						"WHERE kd = '$nil_smtkd'");
			$rkelx = mysql_fetch_assoc($qkelx);
			$kelx_smt = balikin($rkelx['smt']);
			$kelx_no = nosql($rkelx['no']);




			echo "<tr valign=\"top\" bgcolor=\"$warna\" onmouseover=\"this.bgColor='$warnaover';\" onmouseout=\"this.bgColor='$warna';\">";
			echo '<td>'.$i_nim.'</td>
			<td>'.$i_nama.'</td>
			<td>'.$kelx_smt.'</td>
			<td align="right">'.xduit2($jmx_nilai).'</td>
			</tr>';
			}
		while ($rcc1 = mysql_fetch_assoc($qcc1));




		//ketahui jumlah uangnya...
		$qjmx1 = mysql_query("SELECT SUM(nilai) AS total ".
					"FROM mahasiswa_keu ".
					"WHERE kd_jenis = '$jnskd' ".
					"AND kd_tapel = '$tapelkd' ".
					"AND nilai <> '' ".
					"AND round(DATE_FORMAT(tgl_bayar, '%d')) = '$i' ".
					"AND round(DATE_FORMAT(tgl_bayar, '%m')) = '$ubln' ".
					"AND round(DATE_FORMAT(tgl_bayar, '%Y')) = '$uthn'");
		$rjmx1 = mysql_fetch_assoc($qjmx1);
		$tjmx1 = mysql_num_rows($qjmx1);
		$jmx1_total = nosql($rjmx1['total']);

		echo '<tr bgcolor="'.$warnaover.'">
		<td></td>
		<td></td>
		<td></td>
		<td align="right"><strong>'.xduit2($jmx1_total).'</strong></td>
		</tr>
		</table>
		<br>
		<br>';
		}


	//ketahui jumlah uangnya... sebulan
	$qjmx2 = mysql_query("SELECT SUM(nilai) AS total ".
				"FROM mahasiswa_keu ".
				"WHERE kd_jenis = '$jnskd' ".
				"AND kd_tapel = '$tapelkd' ".
				"AND nilai <> '' ".
				"AND round(DATE_FORMAT(tgl_bayar, '%m')) = '$ubln' ".
				"AND round(DATE_FORMAT(tgl_bayar, '%Y')) = '$uthn'");
	$rjmx2 = mysql_fetch_assoc($qjmx2);
	$tjmx2 = mysql_num_rows($qjmx2);
	$jmx2_total = nosql($rjmx2['total']);

	echo '<table width="990" border="0" cellspacing="0" cellpadding="3">
	<tr valign="top" bgcolor="'.$warnaover.'">
	<td>
	Total Nominal Bulan ini : <strong>'.xduit2($jmx2_total).'</strong>
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