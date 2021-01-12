<?php

use Slim\Http\Request;
use Slim\Http\Response;
use \Firebase\JWT\JWT;

//Select / Read -> tampil data biasa
$app->get("/koperasi", function($request, $response, $datae){

	$hasil = $this->db->query("select * from koperasi")->fetchAll(PDO::FETCH_ASSOC);
	$json = json_encode($hasil);
	print_r($json);

});


#memfilter data berdasarkan field cif
$app->get("/koperasi/{cif}", function($request, $response, $datae){

	$cif=$datae['cif'];

	$hasil = $this->db->query("select * from koperasi where cif='$cif'")->fetchAll(PDO::FETCH_ASSOC);
	$json = json_encode($hasil);
	print_r($json);

});

#tampildata biasa
$app->get("/kecamatan", function($request, $response, $datae){

	$hasil = $this->db->query("select * from kecamatan")->fetchAll(PDO::FETCH_ASSOC);
	$json = json_encode($hasil);
	print_r($json);

});

#untuk input data
$app->post("/kodepos", function($request, $response, $datae){
	$postdata = $request->getBody(); #--> Sama dengan :  $postdata = file_get_contents("php://input");
  $req = json_decode($postdata);

	$kelurahan = $req->kelurahan; #variabel yang dikirim dari postman
	$kecamatan = $req->kecamatan;
	$jenis = $req->jenis;

	$hasil = $this->db->query("insert into kodepos set kelurahan='$kelurahan',
																											kecamatan='$kecamatan',
																											jenis='$jenis'");

});

//
//
// $app->group('/api', function(\Slim\App $app) {
//
// 		#berita
// 		$app->get('/berita', function($request, $response, $datae){
// 			$otentifikasi=$request->getHeaderLine('authorization');
// 			$otentifikasi=str_replace("Bearer ","",$otentifikasi);
//
// 			#ambil $idskul
// 			$hasil2=$this->db->query("select * from user where token_app='$otentifikasi'")->fetch(PDO::FETCH_ASSOC);
// 			$iduser=$hasil2['iduser'];
// 			$levelx=$hasil2['levelx'];
//
// 			//cek token betul gag
// 			$jum = $this->db->query("select count(*) from user where iduser='$iduser' and token_app='$otentifikasi'")->fetchColumn();
// 			if($jum<=0){
// 				return;
// 			}
// 			#update last_aktif
// 			$hasil=$this->db->query("update user set last_aktif=now() where iduser='$iduser' and token_app='$otentifikasi'");
//
// 			//tampil data
// 			$hasil = $this->db->query("select *, date(tglpost) as tglx, time(tglpost) as waktu from news order by tglpost")->fetchAll(PDO::FETCH_ASSOC);
// 			$json=json_encode($hasil);
// 			print_r($json);
// 		});
//
// 		#hanya bisa diakses oleh kantor utama
// 		$app->get('/berita/{idx}', function($request, $response, $datae){
// 			$otentifikasi=$request->getHeaderLine('authorization');
// 			$otentifikasi=str_replace("Bearer ","",$otentifikasi);
//
// 			#ambil $idskul
// 			$hasil2=$this->db->query("select * from user where token_app='$otentifikasi'")->fetch(PDO::FETCH_ASSOC);
// 			$iduser=$hasil2['iduser'];
// 			$levelx=$hasil2['levelx'];
//
// 			//cek token betul gag
// 			$jum = $this->db->query("select count(*) from user where iduser='$iduser' and token_app='$otentifikasi'")->fetchColumn();
// 			if($jum<=0){
// 				return;
// 			}
// 			#update last_aktif
// 			$hasil=$this->db->query("update user set last_aktif=now() where iduser='$iduser' and token_app='$otentifikasi'");
//
// 			$idx=$datae['idx'];
//
// 			//tampil data
// 			$hasil = $this->db->query("select *
// 			from news where id='$idx'")->fetchAll(PDO::FETCH_ASSOC);
// 			$json=json_encode($hasil);
// 			print_r($json);
//
// 		});
//
//
//
// 		#input data dengan POST pengemudi
// 		$app->post('/berita', function($request, $response){
//
// 			$otentifikasi=$request->getHeaderLine('authorization');
// 			$otentifikasi=str_replace("Bearer ","",$otentifikasi);
//
// 			#ambil $idskul
// 			$hasil2=$this->db->query("select * from user where token_app='$otentifikasi'")->fetch(PDO::FETCH_ASSOC);
// 			$iduser=$hasil2['iduser'];
// 			$levelx=$hasil2['levelx'];
//
// 			//cek token betul gag
// 			$jum = $this->db->query("select count(*) from user where iduser='$iduser' and token_app='$otentifikasi'")->fetchColumn();
// 			if($jum<=0){
// 				return;
// 			}
// 			#update last_aktif
// 			$hasil=$this->db->query("update user set last_aktif=now() where iduser='$iduser' and token_app='$otentifikasi'");
//
// 	  	$postdata = $request->getBody(); #--> Sama dengan :  $postdata = file_get_contents("php://input");
// 		  $req = json_decode($postdata);
//
// 		  $judul = $req->judul;
// 			$isi = $req->isi;
//
// 		  $hasil = $this->db->query("insert news set judul='$judul',
// 																									isi='$isi',
// 																									dilihat='0',
// 																													tglpost=now()");
//
// 			$id= $this->db->lastInsertId();
//
// 			echo $id;
//
//
// 			if(isset($req->image1)){
// 				$image1=$req->image1;
// 				$upload_path = "../upload/news/".$id.".jpg";
// 				file_put_contents($upload_path, base64_decode($image1));
// 			}
//
//
// 		});
//
// 		#input data dengan POST pengemudi
// 		$app->put('/berita', function($request, $response){
//
// 			$otentifikasi=$request->getHeaderLine('authorization');
// 			$otentifikasi=str_replace("Bearer ","",$otentifikasi);
//
// 			#ambil $idskul
// 			$hasil2=$this->db->query("select * from user where token_app='$otentifikasi'")->fetch(PDO::FETCH_ASSOC);
// 			$iduser=$hasil2['iduser'];
// 			$levelx=$hasil2['levelx'];
//
// 			//cek token betul gag
// 			$jum = $this->db->query("select count(*) from user where iduser='$iduser' and token_app='$otentifikasi'")->fetchColumn();
// 			if($jum<=0){
// 				return;
// 			}
// 			#update last_aktif
// 			$hasil=$this->db->query("update user set last_aktif=now() where iduser='$iduser' and token_app='$otentifikasi'");
//
// 	  	$postdata = $request->getBody(); #--> Sama dengan :  $postdata = file_get_contents("php://input");
// 		  $req = json_decode($postdata);
//
// 			$idx = $req->id;
// 			$judul = $req->judul;
// 			$isi = $req->isi;
//
// 		  $hasil = $this->db->query("update news set judul='$judul',
// 			 																						isi='$isi'
// 																									where id='$idx'");
//
// 			$id= $this->db->lastInsertId();
//
// 			echo $id;
//
// 			if(isset($req->image1)){
// 				$image1=$req->image1;
// 				$upload_path = "../upload/news/".$id.".jpg";
// 				file_put_contents($upload_path, base64_decode($image1));
// 			}
//
// 		});
//
//
// 		#HAPUS data pengemudi
// 		$app->delete('/berita/{idx}', function($request, $response, $datae){
//
// 			$otentifikasi=$request->getHeaderLine('authorization');
// 			$otentifikasi=str_replace("Bearer ","",$otentifikasi);
//
// 			#ambil $idskul
// 			$hasil2=$this->db->query("select * from user where token_app='$otentifikasi'")->fetch(PDO::FETCH_ASSOC);
// 			$iduser=$hasil2['iduser'];
// 			$levelx=$hasil2['levelx'];
//
// 			//cek token betul gag
// 			$jum = $this->db->query("select count(*) from user where iduser='$iduser' and token_app='$otentifikasi'")->fetchColumn();
// 			if($jum<=0){
// 				return;
// 			}
// 			#update last_aktif
// 			$hasil=$this->db->query("update user set last_aktif=now() where iduser='$iduser' and token_app='$otentifikasi'");
//
// 			$idx=$datae['idx'];
// 			$hasil = $this->db->query("delete from news where id='$idx'");
// 			echo 'sukses';
//
// 			unlink("../upload/news/".$idx.".jpg");
// 		});
//
// });
