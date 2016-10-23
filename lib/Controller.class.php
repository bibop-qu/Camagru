<?php

require_once("lib/Model.class.php");
require_once("lib/View.class.php");

class Controller
{
	private $model;
	private $view;

	public function	__construct()
	{
		$this->model = new Model();
		$this->view = new View();
	}

	public function	header()
	{
		$data = $this->model->header();
		$this->view->header($data);
	}

	public function home()
	{
		if (isset($_SESSION["login"]))
		{
			$this->profile();
		}
		else
		{
			$this->view->home();
		}
	}

	public function create()
	{
		$this->view->create();
	}

	public function add()
	{
		if ($_POST["login"] == "") {echo "Bad login"; die;}
		if ($_POST["password"] == "") {echo "Bad password"; die;}
		if ($_POST["mail"] == "") {echo "Bad mail"; die;}
		if ($_POST["password"] != $_POST["confirmation"]) { echo "Password doesn't match"; die;}
		$token = md5(uniqid());
		if ($this->model->add($token) == 1)
		{
			mail($_POST['mail'], "Validation de votre compte CAMAGRU", "Rendez-vous sur le lien suivant pour valider votre compte:\r\n" . "http://" . $_SERVER['SERVER_NAME'] . ":" . $_SERVER['SERVER_PORT'] . "/index.php?page=user_validate&token=" . $token);
			header("Location: index.php");
		}
		else
			echo "Error";
	}

	public	function user_validate()
	{
		echo "Your account is now valid";
		$this->model->user_validate();
	}

	public function connect()
	{
		if ($this->model->connect() == 1)
		{
//			if ($this->model->user_is_valid($_POST['login']))
			if (1)
			{
				$_SESSION["login"] = $_POST["login"];
				header("Location: index.php");
			}
			else
				echo "You have to validate your account with the validation link sent by mail";
		}
		else
			echo "Wrong password or login";
	}

	public function logout()
	{
		session_destroy();
		header("Location: index.php");
	}

	public function	profile()
	{
		$this->is_connected();
		$this->header();
		$data = $this->model->profile();
		$posts = $this->model->get_user_posts();
		$this->view->profile($data, $posts);
	}

	public function main()
	{
		$this->is_connected();
		$data = scandir("filters");
		unset($data[0]);
		unset($data[1]);
		$this->header();
		$posts = $this->model->get_user_posts();
		$this->view->main($data, $posts);
	}

	public function upload()
	{
		$this->is_connected();
		$id = $this->model->user_id($_SESSION['login']);
		if (!file_exists('posts'))
			mkdir('posts');
		if ($_POST['filter'] == "void")
		{
			echo "Please select one filter";
			die;
		}
		if ($_FILES['picture']['name'] != null)
		{
			$name = 'posts/' . $id . "_" . time();
			copy($_FILES['picture']['tmp_name'], $name);
			$old_size = getimagesize($name);
			if ($old_size['mime'] == 'image/png') $old_img = imagecreatefrompng($name);
			else if ($old_size['mime'] == 'image/gif') $old_img = imagecreatefromgif($name);
			else if ($old_size['mime'] == 'image/jpeg' || $old_size['mime'] == 'image/jpg') $old_img = imagecreatefromjpeg($name);
			else { echo "Please insert a valid image (jpg, png, gif)"; die; }
			$new_width = 320;
			$reduction = ($new_width * 100 / $old_size[0]);
			$new_height = ($old_size[1] * $reduction / 100);
			if ($new_height > 240)
			{
				$new_height = 240;
				$reduction = ($new_height * 100 / $old_size[1]);
				$new_width = ($old_size[0] * $reduction / 100);
			}
			$new_img = imagecreatetruecolor($new_width, $new_height);
			imagecopyresampled($new_img, $old_img, 0, 0, 0, 0, $new_width, $new_height, $old_size[0], $old_size[1]);
			imagedestroy($old_img);
			$name = 'posts/' . $id . time() . '.png';
			if (imagepng($new_img, $name) == false) { echo "error"; die;}
			$img2 = imagecreatefrompng("filters/" . $_POST['filter']);
			imagecopyresized($new_img, $img2, 0, 0, 0, 0, imagesx($new_img), imagesy($new_img), imagesx($img2), imagesy($img2));
			imagecopy($new_img, $img2, 0, 0, 0, 0, 340, 240);
			imagepng($new_img, $name);
			$this->model->upload($name);
			header('Location: index.php?page=main');
		}
		else if ($_POST['img'])
		{
			$name = 'posts/' . $id . "_". time() . '.png';
			$file = fopen($name, 'w+');
			fwrite($file, base64_decode($_POST['img']));
			fclose($file);
			$img1 = imagecreatefrompng($name);
			$img2 = imagecreatefrompng("filters/" . $_POST['filter']);
			imagecopyresized($img1, $img2, 0, 0, 0, 0, imagesx($img1), imagesy($img1), imagesx($img2), imagesy($img2));
			imagepng($img1, $name);
			$this->model->upload($name);
			header('Location: index.php?page=main');
		}
		else if ($_POST['img'] == null && $_FILES['picture']['name'] == null)
		{
			echo "Take a picture or select one to upload";
			die;
		}
	}

	public	function gallery()
	{
		$this->is_connected();
		$this->header();
		if (isset($_GET['number']))
			$nb = (int) $_GET['number'];
		else
			$nb = 0;
		$data = $this->model->gallery($nb);
		$this->view->gallery($data, $this->model->number_page());
	}

	public	function get_like($post)
	{
		$this->is_connected();
		$data = $this->model->get_like($post);
		return($data);
	}

	public	function get_like_user($post)
	{
		$this->is_connected();
		$data = $this->model->get_like_user($_SESSION['login'], $post);
		return($data);
	}

	public	function like()
	{
		$this->is_connected();
		if ($this->get_like_user($_GET['post']) == 0)
			echo $this->model->like($_SESSION['login'], $_GET['post']);
		else
			echo "You already like it :D";
	}

	public	function comment()
	{
		$this->is_connected();
		$this->model->comment($_SESSION['login'], $_POST['post'], $_POST['content']);
		$owner = $this->model->get_owner($_POST['post']);
		$mail = $this->model->get_mail($owner);
		mail($mail, "CAMAGRU: " . $_SESSION['login'] . " a commente votre photo.", $_SESSION['login'] . " a commente votre photo:\r\n" . $_POST['content']);
		$this->gallery();
	}

	public	function get_comments($post)
	{
		$this->is_connected();
		return $this->model->get_comments($post);
	}

	public	function pwd_change()
	{
		$this->is_connected();
		if ($_POST['new'] != $_POST['confirmation'])
			die;
		$this->model->pwd_change($_SESSION['login'], $_POST['old'], $_POST['new']);
		header("location: index.php?page=home");
	}

	public	function is_connected()
	{
		if (!isset($_SESSION['login']))
		{
			$this->home();
			die;
		}
	}

	public	function remove_post()
	{
		if ($this->model->get_owner($_GET['post']) == $_SESSION['login'])
		{
			$this->model->remove_post($_GET['post']);
			unlink($_GET['post']);
			header('Location:  index.php?page=main');
		}
		else
		{
			echo "You are not the owner of this picture";
			die;
		}
	}

	public	function fargot_password()
	{
		$this->view->fargot_password();
	}

	public	function renew_password()
	{
		$return = $this->model->email_exist($_POST['email']);
		if ($return == 1)
		{
			$token = md5(uniqid());
			$pass = substr(md5(uniqid()), 0, 8);
			if ($this->model->renew_password($token, hash('whirlpool', $pass), $_POST['email']) == true)
			{
				mail($_POST['email'], "Changement de votre mot de passe CAMAGRU",
				"Rendez-vous sur le lien suivant pour valider votre nouveau mot de passe:\r\n" .
				"http://" . $_SERVER['SERVER_NAME'] . ":" . $_SERVER['SERVER_PORT'] . "/index.php?page=password_validate&token=" . $token .
				"\r\n\r\nNouveau mot de passe: " . $pass);
				header('Location: index.php');
			}
			else
			{
				echo "error in sql request";
			}
		}
		else if ($return === null)
			echo "error in sql request";
		else
			echo "mail doesn't exist";
	}

	public	function password_validate()
	{
		echo "Your new password send by mail is now valid.";
		$this->model->user_validate();
	}
}
?>
