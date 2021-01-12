<?php 
#konfigurasi database
$servername = "localhost";
$username = "biznizoi_adi";
$password = "@comrahasia321!";
$dbname = "biznizoi_kbsu";
$conn = new mysqli($servername, $username, $password, $dbname);


//$data = file_get_contents('php://input');
#ambil data dari callback prepaid
$data = json_decode(file_get_contents('php://input'), true); 
$status=$data["data"]["status"]; //1-sukses, 2-gagal
$ref_id=$data["data"]["ref_id"];
$status_message=$data["data"]["message"];


if($status=='1'){
	$status='2'; // kode berhasil di pemb_online
}else{
	$status='9'; //gagal di pemb_online
	
	#jika gagal hapus transaksi pulsa di jurnal dan tbsimpanan
		#ambil kode simpanan
			$query = "select NoKwitSimp from pemb_online where ref_id='$ref_id'";
			$result = mysqli_query($conn, $query);
			$row = mysqli_fetch_assoc($result);
			$NoKwitSimp=$row['NoKwitSimp']; 
		
		#hapus tbsimpanan dan tbjurnal berdasar NoKwitSimp
			$sql = "delete from tbsimpanan where NoKwitSimp='$NoKwitSimp'";
			$conn->query($sql);		
			$sql = "delete from tbjurnal where NoKwitSimp='$NoKwitSimp'";
			$conn->query($sql);						
}

#update status transaksi
$sql = "update pemb_online set status='$status', 
								status_message='$status_message' 
								where ref_id='$ref_id'";
$conn->query($sql);

$my_file = 'file.txt';
$handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file);
//fwrite($handle, $data);
fwrite($handle, $status_message);
fclose($handle);


#ambil gcm dari anggota 
$query = "select 
(select gcm from tbanggota where noang=pemb_online.noang) as gcm, 
(select KodeKantor from tbanggota where noang=pemb_online.noang) as KodeKantor, 
noang from pemb_online where ref_id='$ref_id'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$gcm=$row['gcm']; 
$noang=$row['noang'];
$KodeKantor=$row['KodeKantor'];



														

	function sendFCM($to, $judul, $deskripsi, $img_url) {
			$url = 'https://fcm.googleapis.com/fcm/send';
			$fields = array (
					'to' => $to,
			//		'registration_ids' => $id,
					'data' => array (
							"title" => $judul,
							"message" => $deskripsi,
							"img_url" => $img_url
					)
			);
			$fields = json_encode ( $fields );
			$headers = array (
					'Authorization: key=' . "AAAAuExMpbo:APA91bEuP1CA4vhAaV2NoJKmyPnPR3KuBZ8cxGybfoBiWG9hLnbpDoXekMJRw6T9IRxLmf-DK1a_DRJXT-3MazpteX9A_l0Wgd4OMBdhjGMV5elvpiqwZQ-dRni-d6_07mZhEsdjLOr6",
					'Content-Type: application/json'
			);

			$ch = curl_init ();
			curl_setopt ( $ch, CURLOPT_URL, $url );
			curl_setopt ( $ch, CURLOPT_POST, true );
			curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
			curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
			curl_setopt ( $ch, CURLOPT_POSTFIELDS, $fields );

			$result = curl_exec ( $ch );
			curl_close ( $ch );
			echo $result;
		}
		$img_url = "http://kbsujatim.com/images/banner/banner2.jpg";       
		ob_start();
		sendFCM($gcm,'KBSU Pay',$status_message,$img_url);	
        ob_end_clean(); 
		
?>