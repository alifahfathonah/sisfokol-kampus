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

//fungsi2
require("../../inc/config.php");
require("../../inc/fungsi.php");
require("../../inc/koneksi.php");
require("../../inc/cek/admdrk.php");
$tpl = LoadTpl("../../template/window.html");

nocache;

//nilai
$filenya = "piutang_biaya_prt.php";
$judul = "Lap. Piutang Biaya";
$judulku = "$judul  [$drk_session : $nip1_session. $nm1_session]";
$judulx = $judul;
$tapelkd = nosql($_REQUEST['tapelkd']);
$s = nosql($_REQUEST['s']);
$page = nosql($_REQUEST['page']);
if ((empty($page)) OR ($page == "0"))
	{
	$page = "1";
	}

$ke = "$filenya?tapelkd=$tapelkd&page=$page";







//PROSES ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//re-direct print...
$ke = "piutang_biaya.php?tapelkd=$tapelkd";
$diload = "window.print();location.href='$ke'";
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////




//isi *START
ob_start();


//js
require("../../inc/js/swap.js");

//view //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
echo '<form name="formx" method="post" action="'.$filenya.'">
<table width="100%" border="0" cellspacing="0" cellpadding="3">
<tr valign="top">
<td valign="top" align="center">
<P>
<big>
<strong>LAPORAN PIUTANG BIAYA</strong>
</big>
</P>
<P>
<big>
<strong>'.$sek_nama.'</strong>
</big>
</P>
</td>
</tr>
</table>

<table bgcolor="'.$warnaover.'" width="100%" border="0" cellspacing="0" cellpadding="3">
<tr>
<td>
<hr height="1">
Tahun Akademik : ';
//terpilih
$qtpx = mysql_query("SELECT * FROM m_tapel ".
						"WHERE kd = '$tapelkd'");
$rowtpx = mysql_fetch_assoc($qtpx);
$tpx_kd = nosql($rowtpx['kd']);
$tpx_thn1 = nosql($rowtpx['tahun1']);
$tpx_thn2 = nosql($rowtpx['tahun2']);

echo '<strong>'.$tpx_thn1.'/'.$tpx_thn2.'</strong>
<hr height="1">
</td>
</tr>
</table>';




//netralkan dulu
mysql_query("DELETE FROM piutang_biaya ".
		"WHERE kd_tapel = '$tapelkd'");


//program studi
$qtp = mysql_query("SELECT * FROM m_progdi ".
			"ORDER BY nama ASC");
$rowtp = mysql_fetch_assoc($qtp);

do
	{
	$tpkd = nosql($rowtp['kd']);
	$tpnama = balikin($rowtp['nama']);


	//jenis
	$qju = mysql_query("SELECT * FROM m_kelas ".
				"ORDER BY no ASC");
	$rju = mysql_fetch_assoc($qju);

	do
		{
		//nilai
		$ju_kelkd = nosql($rju['kd']);
		$ju_kelas = nosql($rju['kelas']);

		echo '<p>
		<big>
		<strong>'.$tpnama.'</strong> ['.$ju_kelas.'].
		</big>
		</p>';

		echo '<table width="800" border="1" cellpadding="3" cellspacing="0">
		<tr bgcolor="'.$warnaheader.'">
		<td align="center"><strong>Jenis Keuangan</strong></td>
		<td width="150" align="center"><strong>Jml.Biaya</strong></td>
		<td width="150" align="center"><strong>Terbayar</strong></td>
		<td width="150" align="center"><strong>Piutang</strong></td>
		<td width="50" align="center"><strong>%</strong></td>
		</tr>';

		//daftar jenis uang, selain SPI dan SS ////////////////////////////////////////////////
		$qkti = mysql_query("SELECT m_keu.*, m_keu_jenis.*, ".
					"m_keu_jenis.kd AS jkd, m_smt.*, m_smt.kd AS smtkd ".
					"FROM m_keu, m_keu_jenis, m_smt ".
					"WHERE m_keu.kd_jenis = m_keu_jenis.kd ".
					"AND m_keu.kd_smt = m_smt.kd ".
					"AND m_keu.kd_progdi = '$tpkd' ".
					"AND m_keu.kd_kelas = '$ju_kelkd' ".
					"AND m_keu.kd_tapel = '$tapelkd' ".
					"ORDER BY m_keu_jenis.nama ASC, ".
					"round(m_smt.no) ASC");
		$rkti = mysql_fetch_assoc($qkti);


		do
			{
			//nilai
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
			$xyz = md5("$x$i_nomer");
			$kti_kd = nosql($rkti['jkd']);
			$kti_nama = balikin($rkti['nama']);
			$kti_smtkd = nosql($rkti['smtkd']);
			$kti_smt = nosql($rkti['smt']);
			$kti_jenis = "$kti_nama [$kti_smt].";


			//ketahui jumlah mahasiswa ////////////////////////////////////////////////////////////////////
			$qdt = mysql_query("SELECT DISTINCT(m_mahasiswa.nim) AS nim ".
						"FROM m_mahasiswa, mahasiswa_kelas ".
						"WHERE mahasiswa_kelas.kd_mahasiswa = m_mahasiswa.kd ".
						"AND mahasiswa_kelas.kd_tapel = '$tapelkd' ".
						"AND mahasiswa_kelas.kd_progdi = '$tpkd' ".
						"AND mahasiswa_kelas.kd_kelas = '$ju_kelkd' ".
						"AND mahasiswa_kelas.kd_smt = '$kti_smtkd' ".
						"ORDER BY round(m_mahasiswa.nim) ASC");
			$rdt = mysql_fetch_assoc($qdt);
			$tdt = mysql_num_rows($qdt);



			//jika SKS, dikalikan dengan jumlah SKS yang diambil ///////////////////////////////////////////////////
			if ($kti_kd == "b7456a463a7b0c1c9a3ece4b30c6db4a")
				{
				//total sks
				$qtokuy = mysql_query("SELECT SUM(m_makul.sks) AS total ".
							"FROM mahasiswa_makul, m_makul, mahasiswa_kelas ".
							"WHERE mahasiswa_makul.kd_makul = m_makul.kd ".
							"AND mahasiswa_makul.kd_mahasiswa_kelas = mahasiswa_kelas.kd ".
							"AND mahasiswa_kelas.kd_tapel = '$tapelkd' ".
							"AND mahasiswa_kelas.kd_progdi = '$tpkd' ".
							"AND mahasiswa_kelas.kd_kelas = '$ju_kelkd' ".
							"AND mahasiswa_kelas.kd_smt = '$kti_smtkd' ".
							"AND mahasiswa_makul.kd_tapel = '$tapelkd' ".
							"AND mahasiswa_makul.kd_smt = '$kti_smtkd'");
				$rtokuy = mysql_fetch_assoc($qtokuy);
				$tokuy_total = nosql($rtokuy['total']);

				//harga sks
				$qktiy = mysql_query("SELECT m_keu.*, m_keu_jenis.*, m_keu_jenis.kd AS jkd ".
							"FROM m_keu, m_keu_jenis ".
							"WHERE m_keu.kd_jenis = m_keu_jenis.kd ".
							"AND m_keu_jenis.kd = 'b7456a463a7b0c1c9a3ece4b30c6db4a' ".
							"AND m_keu.kd_progdi = '$tpkd' ".
							"AND m_keu.kd_kelas = '$ju_kelkd' ".
							"AND m_keu.kd_tapel = '$tapelkd' ".
							"AND m_keu.kd_smt = '$kti_smtkd'");
				$rktiy = mysql_fetch_assoc($qktiy);
				$ktiy_harga = nosql($rktiy['biaya']);


				//total telah dibayar
				$qccy = mysql_query("SELECT SUM(nilai) AS total FROM mahasiswa_keu ".
							"WHERE kd_progdi = '$tpkd' ".
							"AND kd_kelas = '$ju_kelkd' ".
							"AND kd_tapel = '$tapelkd' ".
							"AND kd_smt = '$kti_smtkd' ".
							"AND kd_jenis = '$kti_kd'");
				$rccy = mysql_fetch_assoc($qccy);
				$ccy_total = nosql($rccy['total']);


				//biaya lain
				$qktiy2 = mysql_query("SELECT SUM(biaya) AS total ".
							"FROM m_keu, m_keu_jenis ".
							"WHERE m_keu.kd_jenis = m_keu_jenis.kd ".
							"AND m_keu_jenis.kd <> 'b7456a463a7b0c1c9a3ece4b30c6db4a' ".
							"AND m_keu.kd_progdi = '$tpkd' ".
							"AND m_keu.kd_kelas = '$ju_kelkd' ".
							"AND m_keu.kd_tapel = '$tapelkd' ".
							"AND m_keu.kd_smt = '$kti_smtkd' ".
							"AND m_keu.kd_jenis = '$kti_kd'");
				$rktiy2 = mysql_fetch_assoc($qktiy2);
				$ktiy2_total = nosql($rktiy2['total']);


				//aneka total
				$tobiaya = round(($tokuy_total*$ktiy_harga*$tdt)+($ktiy2_total*$tdt));
				$toterbayar = $ccy_total;
				$topiutang = round($tobiaya - $toterbayar);
				}
			else
				{
				//total telah dibayar /////////////////////////////////////////////////////////////////////////
				$qccy = mysql_query("SELECT SUM(nilai) AS total FROM mahasiswa_keu ".
							"WHERE kd_progdi = '$tpkd' ".
							"AND kd_kelas = '$ju_kelkd' ".
							"AND kd_tapel = '$tapelkd' ".
							"AND kd_smt = '$kti_smtkd' ".
							"AND kd_jenis = '$kti_kd'");
				$rccy = mysql_fetch_assoc($qccy);
				$ccy_total = nosql($rccy['total']);


				//biaya lain //////////////////////////////////////////////////////////////////////////////////
				$qktiy2 = mysql_query("SELECT SUM(biaya) AS total ".
							"FROM m_keu, m_keu_jenis ".
							"WHERE m_keu.kd_jenis = m_keu_jenis.kd ".
							"AND m_keu_jenis.kd <> 'b7456a463a7b0c1c9a3ece4b30c6db4a' ".
							"AND m_keu.kd_progdi = '$tpkd' ".
							"AND m_keu.kd_kelas = '$ju_kelkd' ".
							"AND m_keu.kd_tapel = '$tapelkd' ".
							"AND m_keu.kd_smt = '$kti_smtkd' ".
							"AND m_keu.kd_jenis = '$kti_kd'");
				$rktiy2 = mysql_fetch_assoc($qktiy2);
				$ktiy2_total = nosql($rktiy2['total']);


				//aneka total /////////////////////////////////////////////////////////////////
				$tobiaya = round($ktiy2_total*$tdt);
				$toterbayar = $ccy_total;
				$topiutang = round($tobiaya - $toterbayar);
				}



			//masukkan dalam daftar piutang
			mysql_query("INSERT INTO piutang_biaya (kd, kd_tapel, kd_progdi, ".
					"kd_kelas, jenis, jml_biaya, jml_terbayar, jml_piutang) VALUES ".
					"('$xyz', '$tapelkd', '$tpkd', ".
					"'$ju_kelkd', '$kti_jenis', '$tobiaya', '$toterbayar', '$topiutang')");



			//perhitungan persen...
			//jika ada null
			if ((empty($topiutang)) OR (empty($tobiaya)))
				{
				$nil_persen = "0";
				}
			else
				{
				$nil_persen = round(($topiutang / $tobiaya) * 100,2);
				}


			echo "<tr valign=\"top\" bgcolor=\"$warna\" onmouseover=\"this.bgColor='$warnaover';\" onmouseout=\"this.bgColor='$warna';\">";
			echo '<td>'.$kti_nama.' ['.$kti_smt.'].</td>
			<td align="right">';

			//jika null
			if (empty($tobiaya))
				{
				echo "-";
				}
			else
				{
				echo xduit2($tobiaya);
				}

			echo '</td>
			<td align="right">';

			//jika null
			if (empty($toterbayar))
				{
				echo "-";
				}
			else
				{
				echo xduit2($toterbayar);
				}

			echo '</td>
			<td align="right">';

			//jika null
			if (empty($topiutang))
				{
				echo "-";
				}
			else
				{
				echo xduit2($topiutang);
				}
			echo '</td>
			<td align="right">
			'.$nil_persen.'
			</td>
			</tr>';
			}
		while ($rkti = mysql_fetch_assoc($qkti));


		//daftar jenis uang : SPI + SS ////////////////////////////////////////////////////////
		$qkti = mysql_query("SELECT * FROM m_keu_jenis ".
					"WHERE ((nama = 'SS') ".
					"OR (nama = 'SPI')) ".
					"ORDER BY nama ASC");
		$rkti = mysql_fetch_assoc($qkti);


		do
			{
			//nilai
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
			$kti_kd = nosql($rkti['kd']);
			$kti_nama = balikin($rkti['nama']);
			$kti_jenis = $kti_nama;


			//ketahui jumlah mahasiswa ////////////////////////////////////////////////////////////////////
			$qdt = mysql_query("SELECT DISTINCT(m_mahasiswa.nim) AS nim ".
						"FROM m_mahasiswa, mahasiswa_kelas ".
						"WHERE mahasiswa_kelas.kd_mahasiswa = m_mahasiswa.kd ".
						"AND mahasiswa_kelas.kd_tapel = '$tapelkd' ".
						"AND mahasiswa_kelas.kd_progdi = '$tpkd' ".
						"AND mahasiswa_kelas.kd_kelas = '$ju_kelkd' ".
						"AND mahasiswa_kelas.kd_smt = '$kti_smtkd' ".
						"ORDER BY round(m_mahasiswa.nim) ASC");
			$rdt = mysql_fetch_assoc($qdt);
			$tdt = mysql_num_rows($qdt);




			//total telah dibayar
			$qccy = mysql_query("SELECT SUM(nilai) AS total FROM mahasiswa_keu ".
						"WHERE kd_progdi = '$tpkd' ".
						"AND kd_kelas = '$ju_kelkd' ".
						"AND kd_tapel = '$tapelkd' ".
						"AND kd_jenis = '$kti_kd'");
			$rccy = mysql_fetch_assoc($qccy);
			$ccy_total = nosql($rccy['total']);



			//aneka total
			$tobiaya = $ccy_total;
			$toterbayar = $ccy_total;
			$topiutang = round($tobiaya - $toterbayar);


			//masukkan dalam daftar piutang
			mysql_query("INSERT INTO piutang_biaya (kd, kd_tapel, kd_progdi, ".
					"kd_kelas, jenis, jml_biaya, jml_terbayar, jml_piutang) VALUES ".
					"('$xyz', '$tapelkd', '$tpkd', ".
					"'$ju_kelkd', '$kti_jenis', '$tobiaya', '$toterbayar', '$topiutang')");


			echo "<tr valign=\"top\" bgcolor=\"$warna\" onmouseover=\"this.bgColor='$warnaover';\" onmouseout=\"this.bgColor='$warna';\">";
			echo '<td>'.$kti_nama.'</td>
			<td align="right">';

			//jika null
			if (empty($tobiaya))
				{
				echo "-";
				}
			else
				{
				echo xduit2($tobiaya);
				}

			echo '</td>
			<td align="right">';

			//jika null
			if (empty($toterbayar))
				{
				echo "-";
				}
			else
				{
				echo xduit2($toterbayar);
				}

			echo '</td>
			<td align="right">';

			//jika null
			if (empty($topiutang))
				{
				echo "-";
				}
			else
				{
				echo xduit2($topiutang);
				}
			echo '</td>
			<td align="right">';

			echo '</td>
			</tr>';
			}
		while ($rkti = mysql_fetch_assoc($qkti));




		//ketahui jumlah mahasiswa ////////////////////////////////////////////////////////////////////
		$qdt = mysql_query("SELECT DISTINCT(m_mahasiswa.nim) AS nim ".
					"FROM m_mahasiswa, mahasiswa_kelas, m_smt ".
					"WHERE mahasiswa_kelas.kd_mahasiswa = m_mahasiswa.kd ".
					"AND mahasiswa_kelas.kd_tapel = '$tapelkd' ".
					"AND mahasiswa_kelas.kd_progdi = '$tpkd' ".
					"AND mahasiswa_kelas.kd_kelas = '$ju_kelkd' ".
					"AND mahasiswa_kelas.kd_smt = m_smt.kd ".
					"ORDER BY round(m_mahasiswa.nim) ASC");
		$rdt = mysql_fetch_assoc($qdt);
		$tdt = mysql_num_rows($qdt);


		//total semuanya //////////////////////////////////////////////////////////////////////
		//jml.biaya
		$qtyu = mysql_query("SELECT SUM(jml_biaya) AS total ".
					"FROM piutang_biaya ".
					"WHERE kd_progdi = '$tpkd' ".
					"AND kd_tapel = '$tapelkd' ".
					"AND kd_kelas = '$ju_kelkd'");
		$rtyu = mysql_fetch_assoc($qtyu);
		$tyu_jml_biaya = nosql($rtyu['total']);

		//jml.terbayar
		$qtyu2 = mysql_query("SELECT SUM(jml_terbayar) AS total ".
					"FROM piutang_biaya ".
					"WHERE kd_progdi = '$tpkd' ".
					"AND kd_tapel = '$tapelkd' ".
					"AND kd_kelas = '$ju_kelkd'");
		$rtyu2 = mysql_fetch_assoc($qtyu2);
		$tyu2_jml_terbayar = nosql($rtyu2['total']);

		//jml.piutang
		$qtyu3 = mysql_query("SELECT SUM(jml_piutang) AS total ".
					"FROM piutang_biaya ".
					"WHERE kd_progdi = '$tpkd' ".
					"AND kd_tapel = '$tapelkd' ".
					"AND kd_kelas = '$ju_kelkd'");
		$rtyu3 = mysql_fetch_assoc($qtyu3);
		$tyu3_jml_piutang = nosql($rtyu3['total']);



		//perhitungan persen...
		//jika ada null
		if ((empty($tyu3_jml_piutang)) OR (empty($tyu_jml_biaya)))
			{
			$nil_persen = "0";
			}
		else
			{
			$nil_persen = round(($tyu3_jml_piutang / $tyu_jml_biaya) * 100,2);
			}

		echo '<tr bgcolor="'.$warnaheader.'">
		<td>&nbsp;</td>
		<td align="right"><strong>'.xduit2($tyu_jml_biaya).'</strong></td>
		<td align="right"><strong>'.xduit2($tyu2_jml_terbayar).'</strong></td>
		<td align="right"><strong>'.xduit2($tyu3_jml_piutang).'</strong></td>
		<td align="right"><strong>'.$nil_persen.'</strong></td>
		</tr>
		</table>
		<hr>';
		}
	while ($rju = mysql_fetch_assoc($qju));
	}
while ($rowtp = mysql_fetch_assoc($qtp));



//total seluruhnya //////////////////////////////////////////////////////////////////////
//jml.biaya
$qtyu = mysql_query("SELECT SUM(jml_biaya) AS total ".
			"FROM piutang_biaya ".
			"WHERE kd_tapel = '$tapelkd'");
$rtyu = mysql_fetch_assoc($qtyu);
$tyu_jml_biaya = nosql($rtyu['total']);

//jml.terbayar
$qtyu2 = mysql_query("SELECT SUM(jml_terbayar) AS total ".
			"FROM piutang_biaya ".
			"WHERE kd_tapel = '$tapelkd'");
$rtyu2 = mysql_fetch_assoc($qtyu2);
$tyu2_jml_terbayar = nosql($rtyu2['total']);

//jml.piutang
$qtyu3 = mysql_query("SELECT SUM(jml_piutang) AS total ".
			"FROM piutang_biaya ".
			"WHERE kd_tapel = '$tapelkd'");
$rtyu3 = mysql_fetch_assoc($qtyu3);
$tyu3_jml_piutang = nosql($rtyu3['total']);



//perhitungan persen...
//jika ada null
if ((empty($tyu3_jml_piutang)) OR (empty($tyu_jml_biaya)))
	{
	$nil_persen = "0";
	}
else
	{
	$nil_persen = round(($tyu3_jml_piutang / $tyu_jml_biaya) * 100,2);
	}


echo '<hr>
<p>
Total Biaya :
<br>
<strong>'.xduit2($tyu_jml_biaya).'</strong>
</p>

<p>
Total Terbayar :
<br>
<strong>'.xduit2($tyu2_jml_terbayar).'</strong>
</p>

<p>
Total Piutang :
<br>
<strong>'.xduit2($tyu3_jml_piutang).' ['.$nil_persen.'%].</strong>
</p>';


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