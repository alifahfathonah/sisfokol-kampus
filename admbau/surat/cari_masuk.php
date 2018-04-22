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

require("../../inc/config.php");
require("../../inc/fungsi.php");
require("../../inc/koneksi.php");
require("../../inc/cek/admbau.php");
require("../../inc/class/paging.php");
$tpl = LoadTpl("../../template/index.html");

nocache;

//nilai
$filenya = "cari_masuk.php";
$judul = "Cari Surat Masuk";
$judulku = "[$bau_session : $nip3_session. $nm3_session]. $judul";
$judulx = $judul;
$s = nosql($_REQUEST['s']);
$page = nosql($_REQUEST['page']);
if ((empty($page)) OR ($page == "0"))
	{
	$page = "1";
	}




//isi *START
ob_start();


//js
require("../../inc/js/jumpmenu.js");
require("../../inc/js/swap.js");
require("../../inc/menu/admbau.php");
xheadline($judul);

//view //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
echo '<form action="'.$filenya.'" method="post" name="formx">';

//cari
if ($_POST['btnCARI2'])
{
//nilai
$kunci = cegah($_POST['kunci']);
$kat = nosql($_POST['kat']);
$terima_tgl = nosql($_POST['terima_tgl']);
$terima_bln = nosql($_POST['terima_bln']);
$terima_thn = nosql($_POST['terima_thn']);
$tgl_terima = "$terima_thn:$terima_bln:$terima_tgl";
$terima2_tgl = nosql($_POST['terima2_tgl']);
$terima2_bln = nosql($_POST['terima2_bln']);
$terima2_thn = nosql($_POST['terima2_thn']);
$tgl_terima2 = "$terima2_thn:$terima2_bln:$terima2_tgl";


$klasifikasi = nosql($_POST['klasifikasi']);
$sifat = nosql($_POST['sifat']);
$status = nosql($_POST['status']);
$balasan = nosql($_POST['balasan']);



//jika null
if (($tgl_terima == "::") OR ($tgl_terima == "::"))
	{
	echo '<p>
	<font color="red">
	<b>Jenjang Tanggal Terima, Harus Diisi. Harap Diperhatikan...!!</b>
	</font>
	</p>
	<p>
	[<a href="'.$filenya.'">Cari Lagi</a>]
	</p>';
	}
else
	{
	echo '<p>
	[<a href="'.$filenya.'">Cari Lagi</a>]
	</p>
	<p>
	<big>
	Jenjang Tanggal Terima dari <b>'.$terima_tgl.' '.$arrbln[$terima_bln].' '.$terima_thn.'</b> sampai <b>'.$terima2_tgl.' '.$arrbln[$terima2_bln].' '.$terima2_thn.'</b>.
	</big>
	<br>';

	//kunci
	if (empty($kunci))
		{
		$kunci_ket = "Tanpa Kata Kunci";
		}
	else
		{
		$kunci_ket = $kunci;
		}


	//kategori
	if (empty($kat))
		{
		$kat_ket = "Tanpa Kategori";
		}
	else
		{
		$arrkat = array(
       'kat01' => 'Nomor Urut',
       'kat02' => 'Nomor Surat',
       'kat03' => 'Asal',
       'kat04' => 'Tujuan',
       'kat05' => 'Perihal',
       'kat06' => 'Lampiran',
       'kat07' => 'Tembusan',
       'kat08' => 'Ket.Lain',
       'kat09' => 'Ruang',
       'kat10' => 'Lemari',
       'kat11' => 'Rak',
       'kat12' => 'MAP');

		$kat_ket = $arrkat[$kat];
		}


	//klasifikasi
	if (empty($klasifikasi))
		{
		$dtx_klasifikasi = "Semua Klasifikasi";
		}
	else
		{
		$qdtx = mysql_query("SELECT * FROM surat_m_klasifikasi ".
										"WHERE kd = '$klasifikasi'");
		$rdtx = mysql_fetch_assoc($qdtx);
		$dtx_klasifikasi = balikin($rdtx['klasifikasi']);
		}


	//sifat
	if (empty($sifat))
		{
		$dtx2_sifat = "Semua Sifat";
		}
	else
		{
		$qdtx2 = mysql_query("SELECT * FROM surat_m_sifat ".
										"WHERE kd = '$sifat'");
		$rdtx2 = mysql_fetch_assoc($qdtx2);
		$dtx2_sifat = balikin($rdtx2['sifat']);
		}


	//status
	if (empty($status))
		{
		$dtx3_status = "Semua Status";
		}
	else
		{
		$qdtx3 = mysql_query("SELECT * FROM surat_m_status ".
										"WHERE kd = '$status'");
		$rdtx3 = mysql_fetch_assoc($qdtx3);
		$dtx3_status = balikin($rdtx3['status']);
		}


	//balasan
	if (empty($balasan))
		{
		$x_balasan = "Semua Status";
		}
	else
		{
		//jika telah dibalas
		if ($balasan == "true")
			{
			$balasan_ket = "Telah Dibalas";
			}
		else if ($balasan == "false")
			{
			$balasan_ket = "Belum Dibalas";
			}

		$x_balasan = $balasan_ket;
		}

	echo '[Kata Kunci : <b>'.$kunci_ket.'</b>. Kategori : <b>'.$kat_ket.'</b>],
	[Klasifikasi : <b>'.$dtx_klasifikasi.'</b>],
	[Sifat : <b>'.$dtx2_sifat.'</b>],
	<br>
	[Status Keberadaan Surat : <b>'.$dtx3_status.'</b>],
	[Status Deadline Balasan Surat : <b>'.$x_balasan.'</b>]
	</p>

	<p>';

	//untuk query /////////////////////////////////////////////////////////////////
	if (!empty($kat))
		{
		//jika no_urut
		if (($kat == 'kat01') AND (!empty($kunci)))
			{
			$k_no_urut = "surat_masuk.no_urut LIKE '%$kunci%'";
			}
		else
			{
			$k_no_urut = "surat_masuk.no_urut <> '$kunci'";
			}


		//jika no_surat
		if (($kat == 'kat02') AND (!empty($kunci)))
			{
			$k_no_surat = "surat_masuk.no_surat LIKE '%$kunci%'";
			}
		else
			{
			$k_no_surat = "surat_masuk.no_surat <> '$kunci'";
			}


		//jika asal
		if (($kat == 'kat03') AND (!empty($kunci)))
			{
			$k_asal = "surat_masuk.asal LIKE '%$kunci%'";
			}
		else
			{
			$k_asal = "surat_masuk.asal <> '$kunci'";
			}

		//jika tujuan
		if (($kat == 'kat04') AND (!empty($kunci)))
			{
			$k_tujuan = "surat_masuk.tujuan LIKE '%$kunci%'";
			}
		else
			{
			$k_tujuan = "surat_masuk.tujuan <> '$kunci'";
			}

		//jika perihal
		if (($kat == 'kat05') AND (!empty($kunci)))
			{
			$k_perihal = "surat_masuk.perihal LIKE '%$kunci%'";
			}
		else
			{
			$k_perihal = "surat_masuk.perihal <> '$kunci'";
			}

		//jika lampiran
		if (($kat == 'kat06') AND (!empty($kunci)))
			{
			$k_lampiran = "surat_masuk.lampiran LIKE '%$kunci%'";
			}
		else
			{
			$k_lampiran = "surat_masuk.lampiran <> '$kunci'";
			}

		//jika tembusan
		if (($kat == 'kat07') AND (!empty($kunci)))
			{
			$k_tembusan = "surat_masuk.tembusan LIKE '%$kunci%'";
			}
		else
			{
			$k_tembusan = "surat_masuk.tembusan <> '$kunci'";
			}

		//jika ket
		if (($kat == 'kat08') AND (!empty($kunci)))
			{
			$k_ket = "surat_masuk.ket LIKE '%$kunci%'";
			}
		else
			{
			$k_ket = "surat_masuk.ket <> '$kunci'";
			}

		//jika ruang
		if (($kat == 'kat09') AND (!empty($kunci)))
			{
			$k_ruang = "surat_m_ruang.ruang LIKE '%$kunci%'";
			}
		else
			{
			$k_ruang = "surat_m_ruang.ruang <> '$kunci'";
			}

		//jika lemari
		if (($kat == 'kat10') AND (!empty($kunci)))
			{
			$k_lemari = "surat_m_lemari.lemari LIKE '%$kunci%'";
			}
		else
			{
			$k_lemari = "surat_m_lemari.lemari <> '$kunci'";
			}

		//jika rak
		if (($kat == 'kat11') AND (!empty($kunci)))
			{
			$k_rak = "surat_m_rak.rak LIKE '%$kunci%'";
			}
		else
			{
			$k_rak = "surat_m_rak.rak <> '$kunci'";
			}

		//jika map
		if (($kat == 'kat12') AND (!empty($kunci)))
			{
			$k_map = "surat_m_map.map LIKE '%$kunci%'";
			}
		else
			{
			$k_map = "surat_m_map.map <> '$kunci'";
			}



		//jika null klasifikasi
		if (empty($klasifikasi))
			{
			$k_klasifikasi = "surat_masuk.kd_klasifikasi <> '$klasifikasi'";
			}
		else
			{
			$k_klasifikasi = "surat_masuk.kd_klasifikasi = '$klasifikasi'";
			}


		//jika null sifat
		if (empty($sifat))
			{
			$k_sifat = "surat_masuk.kd_sifat <> '$sifat'";
			}
		else
			{
			$k_sifat = "surat_masuk.kd_sifat = '$sifat'";
			}


		//jika null status
		if (empty($status))
			{
			$k_status = "surat_masuk.kd_status <> '$status'";
			}
		else
			{
			$k_status = "surat_masuk.kd_status = '$status'";
			}


		//jika null balasan
		if (empty($balasan))
			{
			$k_balasan = "surat_masuk.balas <> '$balasan'";
			}
		else
			{
			$k_balasan = "surat_masuk.balas = '$balasan'";
			}


		//utk query
		$ku_filter_nih = "$k_no_urut AND $k_no_surat ".
								"AND $k_tujuan AND $k_perihal ".
								"AND $k_lampiran AND $k_tembusan ".
								"AND $k_ket AND $k_ruang ".
								"AND $k_lemari AND $k_rak ".
								"AND $k_map ".
								"AND $k_klasifikasi AND $k_sifat ".
								"AND $k_status AND $k_balasan";
		}
	else
		{
		//jika null klasifikasi
		if (empty($klasifikasi))
			{
			$k_klasifikasi = "surat_masuk.kd_klasifikasi <> '$klasifikasi'";
			}
		else
			{
			$k_klasifikasi = "surat_masuk.kd_klasifikasi = '$klasifikasi'";
			}


		//jika null sifat
		if (empty($sifat))
			{
			$k_sifat = "surat_masuk.kd_sifat <> '$sifat'";
			}
		else
			{
			$k_sifat = "surat_masuk.kd_sifat = '$sifat'";
			}


		//jika null status
		if (empty($status))
			{
			$k_status = "surat_masuk.kd_status <> '$status'";
			}
		else
			{
			$k_status = "surat_masuk.kd_status = '$status'";
			}


		//jika null balasan
		if (empty($balasan))
			{
			$k_balasan = "surat_masuk.balas <> '$balasan'";
			}
		else
			{
			$k_balasan = "surat_masuk.balas = '$balasan'";
			}


		//utk query
		$ku_filter_nih = "$k_klasifikasi AND $k_sifat ".
								"AND $k_status AND $k_balasan";
		}




	//query
	$p = new Pager();
	$start = $p->findStart($limit);

	$sqlcount = "SELECT surat_masuk.*, surat_masuk.kd AS smkd, ".
						"surat_m_ruang.*, surat_m_lemari.*, surat_m_rak.*, ".
						"surat_m_map.* ".
						"FROM surat_masuk, surat_m_ruang, surat_m_lemari, ".
						"surat_m_rak, surat_m_map ".
						"WHERE surat_masuk.kd_ruang = surat_m_ruang.kd ".
						"AND surat_masuk.kd_lemari = surat_m_lemari.kd ".
						"AND surat_masuk.kd_rak = surat_m_rak.kd ".
						"AND surat_masuk.kd_map = surat_m_map.kd ".
						"AND (surat_masuk.tgl_terima >= '$tgl_terima' ".
						"AND surat_masuk.tgl_terima <= '$tgl_terima2') ".
						"AND $ku_filter_nih ".
						"ORDER BY round(surat_masuk.no_urut) DESC";
	$sqlresult = $sqlcount;

	$count = mysql_num_rows(mysql_query($sqlcount));
	$pages = $p->findPages($count, $limit);
	$result = mysql_query("$sqlresult LIMIT ".$start.", ".$limit);
	$pagelist = $p->pageList($_GET['page'], $pages, $target);
	$data = mysql_fetch_array($result);

	//jika ada
	if ($count != 0)
		{
		echo '<p>
		<table width="100%" border="1" cellspacing="0" cellpadding="3">
		<tr bgcolor="'.$warnaheader.'">
		<td width="5"><strong><font color="'.$warnatext.'">&nbsp;</font></strong></td>
		<td width="5"><strong><font color="'.$warnatext.'">No.</font></strong></td>
		<td><strong><font color="'.$warnatext.'">No. Surat</font></strong></td>
		<td width="50"><strong><font color="'.$warnatext.'">Tgl.Surat</font></strong></td>
		<td width="50"><strong><font color="'.$warnatext.'">Tgl.Terima</font></strong></td>
		<td width="100"><strong><font color="'.$warnatext.'">Asal</font></strong></td>
		<td width="100"><strong><font color="'.$warnatext.'">Tujuan</font></strong></td>
		<td width="100"><strong><font color="'.$warnatext.'">Perihal</font></strong></td>
		<td width="50"><strong><font color="'.$warnatext.'">Klasifikasi</font></strong></td>
		<td width="50"><strong><font color="'.$warnatext.'">Sifat</font></strong></td>
		<td width="50"><strong><font color="'.$warnatext.'">Status</font></strong></td>
		<td width="50"><strong><font color="'.$warnatext.'">Balasan</font></strong></td>
		<td width="50"><strong><font color="'.$warnatext.'">Ruang</font></strong></td>
		<td width="50"><strong><font color="'.$warnatext.'">Lemari</font></strong></td>
		<td width="50"><strong><font color="'.$warnatext.'">Rak</font></strong></td>
		<td width="50"><strong><font color="'.$warnatext.'">MAP</font></strong></td>
		<td width="50"><strong><font color="'.$warnatext.'">Disposisi</font></strong></td>
		</tr>';


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

			//nilai
			$ku_kd = nosql($data['smkd']);
			$ku_no_urut = nosql($data['no_urut']);
			$ku_no_surat = balikin2($data['no_surat']);
			$ku_asal = balikin2($data['asal']);
			$ku_tujuan = balikin2($data['tujuan']);
			$ku_perihal = balikin2($data['perihal']);
			$ku_kd_klasifikasi = nosql($data['kd_klasifikasi']);
			$ku_kd_sifat = nosql($data['kd_sifat']);
			$ku_kd_status = nosql($data['kd_status']);
			$ku_balas = nosql($data['balas']);
			$ku_tgl_surat = $data['tgl_surat'];
			$ku_tgl_terima = $data['tgl_terima'];
			$ku_kd_ruang = nosql($data['kd_ruang']);
			$ku_kd_lemari = nosql($data['kd_lemari']);
			$ku_kd_rak = nosql($data['kd_rak']);
			$ku_kd_map = nosql($data['kd_map']);


			//balas...?
			if ($ku_balas == "true")
				{
				$ku_balas_ket = "<font color=\"blue\">Sudah Dibalas.</font>";
				}
			else if ($ku_balas == "false")
				{
				$ku_balas_ket = "<font color=\"red\"><b>Belum Dibalas.</b></font>";
				}




			//klasifikasi
			$qdtx = mysql_query("SELECT * FROM surat_m_klasifikasi ".
											"WHERE kd = '$ku_kd_klasifikasi'");
			$rdtx = mysql_fetch_assoc($qdtx);
			$dtx_klasifikasi = balikin($rdtx['klasifikasi']);

			//sifat
			$qdtx2 = mysql_query("SELECT * FROM surat_m_sifat ".
											"WHERE kd = '$ku_kd_sifat'");
			$rdtx2 = mysql_fetch_assoc($qdtx2);
			$dtx2_sifat = balikin($rdtx2['sifat']);

			//status
			$qdtx3 = mysql_query("SELECT * FROM surat_m_status ".
											"WHERE kd = '$ku_kd_status'");
			$rdtx3 = mysql_fetch_assoc($qdtx3);
			$dtx3_status = balikin($rdtx3['status']);


			//ruang
			$qdt1 = mysql_query("SELECT * FROM surat_m_ruang ".
											"WHERE kd = '$ku_kd_ruang'");
			$rdt1 = mysql_fetch_assoc($qdt1);
			$dt1_ruang = balikin($rdt1['ruang']);


			//lemari
			$qdt2 = mysql_query("SELECT * FROM surat_m_lemari ".
											"WHERE kd = '$ku_kd_lemari'");
			$rdt2 = mysql_fetch_assoc($qdt2);
			$dt2_lemari = balikin($rdt2['lemari']);


			//rak
			$qdt3 = mysql_query("SELECT * FROM surat_m_rak ".
											"WHERE kd = '$ku_kd_rak'");
			$rdt3 = mysql_fetch_assoc($qdt3);
			$dt3_rak = balikin($rdt3['rak']);


			//map
			$qdt4 = mysql_query("SELECT * FROM surat_m_map ".
											"WHERE kd = '$ku_kd_map'");
			$rdt4 = mysql_fetch_assoc($qdt4);
			$dt4_map = balikin($rdt4['map']);

			echo "<tr valign=\"top\" bgcolor=\"$warna\" onmouseover=\"this.bgColor='$warnaover';\" onmouseout=\"this.bgColor='$warna';\">";
			echo '<td>
			<a href="masuk.php?s=edit&sukd='.$ku_kd.'">
			<img src="'.$sumber.'/img/edit.gif" width="16" height="16" border="0">
			</a>
			</td>
			<td>'.$ku_no_urut.'</td>
			<td>'.$ku_no_surat.'</td>
			<td>'.$ku_tgl_surat.'</td>
			<td>'.$ku_tgl_terima.'</td>
			<td>'.$ku_asal.'</td>
			<td>'.$ku_tujuan.'</td>
			<td>'.$ku_perihal.'</td>
			<td>'.$dtx_klasifikasi.'</td>
			<td>'.$dtx2_sifat.'</td>
			<td>'.$dtx3_status.'</td>
			<td>'.$ku_balas_ket.'</td>
			<td>'.$dt1_ruang.'</td>
			<td>'.$dt2_lemari.'</td>
			<td>'.$dt3_rak.'</td>
			<td>'.$dt4_map.'</td>
			<td>
			<a href="masuk_disposisi.php?sukd='.$ku_kd.'">
			<img src="'.$sumber.'/img/edit.gif" width="16" height="16" border="0">
			</a>
			</td>
			</tr>';
			}
		while ($data = mysql_fetch_assoc($result));

		echo '</table>
		<table width="100%" border="0" cellspacing="0" cellpadding="3">
		<tr>
		<td align="right">Total : <strong><font color="#FF0000">'.$count.'</font></strong> Data. '.$pagelist.'</td>
		</tr>
		</table>
		</p>';
		}
	else
		{
		echo '<p>
		<font color="red">
		<b>TIDAK ADA DATA.</b>
		</font>
		</p>';
		}

	echo '
	</p>';
	}
}

else
{
echo '<p>
<table width="100%" border="0" cellspacing="0" cellpadding="3">
<tr>
<td width="200">
Tanggal Terima dari Tanggal
</td>
<td>:
<select name="terima_tgl">
<option value="" selected></option>';
for ($i=1;$i<=31;$i++)
	{
	echo '<option value="'.$i.'">'.$i.'</option>';
	}

echo '</select>
<select name="terima_bln">
<option value="" selected></option>';
for ($j=1;$j<=12;$j++)
	{
	echo '<option value="'.$j.'">'.$arrbln[$j].'</option>';
	}

echo '</select>
<select name="terima_thn">
<option value="" selected></option>';
for ($k=$surat01;$k<=$surat02;$k++)
	{
	echo '<option value="'.$k.'">'.$k.'</option>';
	}
echo '</select>
</td>
</tr>

<tr>
<td width="200">
Sampai tanggal
</td>
<td>:
<select name="terima2_tgl">
<option value="" selected></option>';
for ($i=1;$i<=31;$i++)
	{
	echo '<option value="'.$i.'">'.$i.'</option>';
	}

echo '</select>
<select name="terima2_bln">
<option value="" selected></option>';
for ($j=1;$j<=12;$j++)
	{
	echo '<option value="'.$j.'">'.$arrbln[$j].'</option>';
	}

echo '</select>
<select name="terima2_thn">
<option value="" selected></option>';
for ($k=$surat01;$k<=$surat02;$k++)
	{
	echo '<option value="'.$k.'">'.$k.'</option>';
	}
echo '</select>
</td>
</tr>
</table>

<hr height="1">
<table width="100%" border="0" cellspacing="0" cellpadding="3">
<tr>
<td width="200">
Kata Kunci
</td>
<td>:
<input type="text" name="kunci" value="" size="20">,
Kategori :
<select name="kat">
<option value="" selected>-</option>
<option value="kat01">Nomor Urut</option>
<option value="kat02">Nomor Surat</option>
<option value="kat03">Asal</option>
<option value="kat04">Tujuan</option>
<option value="kat05">Perihal</option>
<option value="kat06">Lampiran</option>
<option value="kat07">Tembusan</option>
<option value="kat08">Ket.Lain</option>
<option value="kat09">Ruang</option>
<option value="kat10">Lemari</option>
<option value="kat11">Rak</option>
<option value="kat12">MAP</option>
</select>
</td>
</tr>
<tr>
<td width="200">
Klasifikasi Surat
</td>
<td>:
<select name="klasifikasi">
<option value="" selected>-Semua Klasifikasi-</option>';

//daftar klasifikasi
$qdt = mysql_query("SELECT * FROM surat_m_klasifikasi ".
							"ORDER BY klasifikasi ASC");
$rdt = mysql_fetch_assoc($qdt);

do
	{
	//nilai
	$dt_kd = nosql($rdt['kd']);
	$dt_klasifikasi = balikin($rdt['klasifikasi']);

	echo '<option value="'.$dt_kd.'">'.$dt_klasifikasi.'</option>';
	}
while ($rdt = mysql_fetch_assoc($qdt));

echo '</select>
</td>
</tr>
<tr>
<td>
Sifat Surat
</td>
<td>:
<select name="sifat">
<option value="" selected>-Semua Sifat-</option>';

//daftar sifat
$qdt = mysql_query("SELECT * FROM surat_m_sifat ".
							"ORDER BY sifat ASC");
$rdt = mysql_fetch_assoc($qdt);

do
	{
	//nilai
	$dt_kd = nosql($rdt['kd']);
	$dt_sifat = balikin($rdt['sifat']);

	echo '<option value="'.$dt_kd.'">'.$dt_sifat.'</option>';
	}
while ($rdt = mysql_fetch_assoc($qdt));

echo '</select>
</td>
</tr>
<tr>
<td>
Status Keberadaan Surat
</td>
<td>:
<select name="status">
<option value="" selected>-Semua Status-</option>';

//daftar status
$qdt = mysql_query("SELECT * FROM surat_m_status ".
							"ORDER BY status ASC");
$rdt = mysql_fetch_assoc($qdt);

do
	{
	//nilai
	$dt_kd = nosql($rdt['kd']);
	$dt_status = balikin($rdt['status']);

	echo '<option value="'.$dt_kd.'">'.$dt_status.'</option>';
	}
while ($rdt = mysql_fetch_assoc($qdt));

echo '</select>
</td>
</tr>
<tr>
<td width="200">
Status Deadline Balasan
</td>
<td>:
<select name="balasan">
<option value="" selected>-Semua Status-</option>
<option value="true">Sudah Dibalas</option>
<option value="false">Belum Dibalas</option>
</select>
</td>
</tr>
<tr>
<td width="200">
<input type="submit" name="btnCARI2" value="CARI">
<input type="reset" name="btnBTL" value="BATAL">
</td>
<td>

</td>
</tr>
</table>';
}


echo '<br>
<br>
<br>
</form>';
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