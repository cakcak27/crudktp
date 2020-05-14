<?php

function pesan_error($pesan){
	echo "<span style='color:#f00'>".$pesan."</span>";
}

class KTP {
	private $id 	;
	private $nama 	;
	private $nik 	;
	private $lahir 	;
	private $alamat	;
	private $agama 	;
	private $status	;
	private $kerja 	;

	function __constructor()
	{
		$this->id 		= "";
		$this->nama 	= "";
		$this->nik 		= "";
		$this->lahir 	= "";
		$this->alamat 	= "";
		$this->agama 	= "";
		$this->status 	= "";
		$this->kerja 	= "";
	}

	public function setId($id){
		$this->id = $id;
	}
	public function getId(){
		return $this->id;
	}

	public function setNik($nik){
		$this->nik = $nik;
	}
	public function getNik(){
		return $this->nik;
	}

	public function setNama($nama){
		$this->nama = $nama;
	}
	public function getNama(){
		return $this->nama;
	}

	public function setLahir($lahir){
		$this->lahir = $lahir;
	}
	public function getLahir(){
		return $this->lahir;
	}

	public function setAlamat($alamat){
		$this->alamat = $alamat;
	}
	public function getAlamat(){
		return $this->alamat;
	}

	public function setAgama($agama){
		$this->agama = $agama;
	}
	public function getAgama(){
		return $this->agama;
	}

	public function setStatus($status){
		$this->status = $status;
	}
	public function getStatus(){
		return $this->status;
	}

	public function seKerja($kerja){
		$this->kerja = $kerja;
	}
	public function getKerja(){
		return $this->kerja;
	}

}

$html_ktp = new KTP();


function get_connection()
{
	$db 		= "crud_db";
	$host		= "localhost";
	$user 		= "root";
	$pass 		= "";

	$connection = new PDO("mysql:dbname=$db;host:$host", $user, $pass);

	return $connection;
}
	


function inputfile($nik, $nama, $lahir, $alamat, $agama, $status, $kerja)
{
	
	try{
		$sql = "INSERT INTO ktp ( nama, nik, lahir, alamat, agama, status, kerja ) values ( :nama, :nik, :lahir, :alamat, :agama, :status, :kerja)";

		$connection= get_connection();

		$statement = $connection->prepare($sql);

		$statement->execute(array(
			':nama' 	=> $nama,
			':nik' 		=> $nik,
			':lahir' 		=> $lahir,
			':alamat' 		=> $alamat,
			':agama' 		=> $agama,
			':status' 		=> $status,
			':kerja' 		=> $kerja,

		));

		$html_ktp = new KTP();
		header('Location: http://localhost/crudktp/index.php');
	} catch (PDOException $e){
		pesan_error($e);
	}
	
}

function hapusfile($id){
	try{
		$sql="DELETE FROM `ktp` WHERE `ktp`.`id` = :id";
		$connection = get_connection();
		$statement = $connection->prepare($sql);

		$statement->execute(array(':id' => $id));

		header('Location: http://localhost/crudktp/index.php');

	} catch ( PDOException $e){
		pesan_error($e);
	}
}

	if(!isset($_GET['action'])){
	}

	if(isset($_GET['action'])){
		echo $_GET['action'];
		switch ($_GET['action']) {
			case 'input':
				$nama 		= $_POST['nama'];
				$nik 		= $_POST['nik'];
				$lahir 		= $_POST['lahir'];
				$alamat 	= $_POST['alamat'];
				$agama 		= $_POST['agama'];
				$status 	= $_POST['status'];
				$kerja 		= $_POST['kerja'];


				if(($nik!="") || ($nama!="") || ($lahir!="") || ($alamat!="") || ($agama!= "")) {
					$html_ktp->setNik($nik);
					inputfile( $nik, $nama, $lahir, $alamat, $agama, $status, $kerja);	
				}

				break;
			
			case 'hapus':
				if(isset($_GET['id'])){
					$id = $_GET['id'];
					$typedata = gettype($id);
					if($typedata == "integer")
					{
						hapusfile($id);
					} elseif ($typedata == "string")
					{
						$idint = (int) $id;
						hapusfile($idint);
					}
				}
				break;

			default:
				header('Location: http://localhost/crudktp/index.php');
				break;
		}
		
	}
		
function html_input($label, $nama, $value)
{
	echo "
	<tr>
		<td>".$label."</td>
		<td><input type=\"text\" name=\"".$nama."\" value=\"".$value."\" required /></td>
	</tr>
	";
	
}

?>
<!DOCTYPE html>
<html>
	<head><title>CRUD Bagian 1</title>
	</head>
	<body>
		<form action="index.php?action=input" method="POST" name="forminput">
			<table border="0">
				<tr>
					<td colspan="2">CRUD Bagian 1</td>
				</tr>
				<?php
				 

				 html_input('NIK', 'nik', $html_ktp->getNik());
				 html_input('Nama', 'nama', $html_ktp->getNama());
				 html_input('Lahir', 'lahir', $html_ktp->getLahir());
				 html_input('Alamat', 'alamat', $html_ktp->getAlamat());
				 html_input('Agama', 'agama', $html_ktp->getAgama());
				 html_input('Status', 'status', $html_ktp->getStatus());
				 html_input('Kerja', 'kerja', $html_ktp->getKerja());
				 ?>
<!-- 				<tr>
					<td>Nama</td>
					<td><input type="text" name="nama" value="<?=$nama?>" /></td>
				</tr> -->
				

				<tr>
					<td colspan="2"><input type="submit" value="submit" /> <br> <a href="index.php">Reset</a></td>
				</tr>
			</table>
		</form>
		<hr />

		<table border="1">
			<tr>
				<td>id</td>
				<td>nik</td>
				<td>nama</td>
				<td>lahir</td>
				<td>alamat</td>
				<td>agama</td>
				<td>status</td>
				<td>kerja</td>
				<td>action</td>
			</tr>
			<?php 
			$connection = get_connection();
			$sql = "SELECT * FROM `ktp` ";

			$statement = $connection->prepare($sql);

			$statement->execute();

			$results = $statement->fetchAll();

			// var_dump($results);

			foreach ($results as $result):
			?>
			<tr>
				<td><?=$result['id'];?></td>
				<td><?=$result['nik'];?></td>
				<td><?=$result['nama'];?></td>
				<td><?=$result['lahir'];?></td>
				<td><?=$result['alamat'];?></td>
				<td><?=$result['agama'];?></td>
				<td><?=$result['status'];?></td>
				<td><?=$result['kerja'];?></td>
				<td>
					<a href="index.php?action=hapus&id=<?=$result['id'];?>">hapus</a>
				</td>
			</tr>	
			
			<?php 
			endforeach; 
			?>
		</table>
	</body>
</html>

