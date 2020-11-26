<?php 

	include_once "config.php";
	include_once "connectionController.php";

	if (isset($_POST['action'])){

		$UserController = new UserController();

		switch ($_POST['action']) {
			case 'store':
				$name = strip_tags($_POST['name']);
				$last = strip_tags($_POST['last']);
				$email = strip_tags($_POST['email']);
				$pass = strip_tags($_POST['pass']);

				$UserController->store($name,$last,$email,$pass);

				break;
			
			default:
				# code...
				break;
		}
	}
	Class UserController
	{
		function get()
		{
			$conn = connect();
			if (!$conn->connect_error) {

				//armado de la consulta
				$query = "select * from users";
				$prepared_query = $conn->prepare($query);
				$prepared_query->execute();

				$results = $prepared_query->get_result();
				$users = $results->fetch_all(MYSQLI_ASSOC);

				if (count($users)>0) {
					return $users;
				}else{
					return array();
				}
				
			}else
				return array();
		}

		function store($name,$last,$email,$pass)
		{
			$conn = connect();
			if (!$conn->connect_error) {
				if($name!=""&& $last!="" && $email!="" && $pass!=""){

					$query = "insert into users (name,lastname,email,password) values (?,?,?,?)";
					$prepared_query =$conn->prepare($query);
					$prepared_query->bind_param('ssss',$name,$last,$email,$pass);
					if($prepared_query->execute()){
						$_SESION['message'] = "Registro creado correctamente";
						$_SESION['status'] = "succes";
						header("Location: ".$_SERVER['HHTP_REFERER']);
					}else{
						$_SESION['message'] = "El registro no pude ser compleado";
						$_SESION['status'] = "error";
						header("Location: ".$_SERVER['HHTP_REFERER']);
					}
				}else{
					$_SESION['message'] = "Verifique la informacion";
					$_SESION['status'] = "error";
					header("Location: ".$_SERVER['HHTP_REFERER']);
				}
			}else{
				$_SESION['message'] = "Problemas con la conexion al servidor";
						$_SESION['status'] = "error";
						header("Location: ".$_SERVER['HHTP_REFERER']);
			}
		}
	}

?>
