<?php

class Model
{
	public function header()
	{
		global $pdo;
		$req = $pdo->prepare("select count(*) as nb_users from tb_users");
		$req->execute();
		$tab = $req->fetch();
		return ($tab);
	}
	
	public	function add($token)
	{
		global $pdo;
		$req = $pdo->prepare("SELECT count(*) as exist FROM tb_users WHERE login=?");
		$req->execute([$_POST["login"]]);
		$tab = $req->fetch();
		if ($tab["exist"] == 0)
		{
			$req = $pdo->prepare("SELECT count(*) as exist FROM tb_users WHERE mail=?");
			$req->execute([$_POST["mail"]]);
			$tab = $req->fetch();
			if ($tab["exist"] == 0)
			{
				$req = $pdo->prepare("INSERT INTO tb_users (login, password, mail, token) VALUES (?, ?, ?, ?)");
				if ($req->execute([$_POST["login"], hash("whirlpool", $_POST["password"]), $_POST["mail"], $token]))
					return 1;
				else
					return 0;
			}
			else
				return 0;
		}
		else
			return 0;
	}

	public	function user_validate()
	{
		global $pdo;
		$req = $pdo->prepare("UPDATE tb_users SET token='' WHERE token=?");
		$req->execute([$_GET['token']]);
	}

	public	function user_is_valid($login)
	{
		global $pdo;
		$req = $pdo->prepare("SELECT count(*) as valid from tb_users WHERE login=? and token=''");
		$req->execute([$login]);
		$result = $req->fetch();
		return($result['valid']);
	}

	public	function user_id($login)
	{
		global $pdo;
		$req = $pdo->prepare("SELECT * from tb_users WHERE login=?");
		$req->execute([$login]);
		$result = $req->fetch();
		return($result['id']);
	}

	public	function connect()
	{
		global $pdo;
		$req = $pdo->prepare("SELECT count(*) as exist FROM tb_users WHERE login=? AND password=?");
		$req->execute([$_POST["login"], hash('whirlpool', $_POST["password"])]);
		$tab = $req->fetch();
		return $tab["exist"];
	}

	public	function profile()
	{
		global $pdo;
		$req = $pdo->prepare("SELECT * FROM tb_users WHERE login=?");
		$req->execute([$_SESSION["login"]]);
		return ($req->fetch());
	}

	public	function upload($name)
	{
		global $pdo;
		$req = $pdo->prepare("INSERT INTO tb_posts (owner, posts) VALUES (?, ?)");
		$req->execute([$_SESSION['login'], $name]);
	}

	public	function gallery($page)
	{
		global $pdo;
		$req = $pdo->prepare('SELECT * FROM tb_posts LIMIT 10 OFFSET :off');
		$req->bindValue(':off', (int) ($page * 10), PDO::PARAM_INT);
		if (!$req->execute()) echo "Fail in sql request";
		return $req->fetchAll();
	}

	public	function get_like($post)
	{
		global $pdo;
		$req = $pdo->prepare('Select count(*) as likes from tb_likes where posts=?;');
		$req->execute([$post]);
		$tab = $req->fetch();
		return $tab['likes'];
	}

	public	function get_like_user($user, $post)
	{
		global $pdo;
		$req = $pdo->prepare('Select count(*) as result from tb_likes where posts=? and login=?;');
		if (!$req->execute([$post, $user]))
			echo "Fail";
		$tab = $req->fetch();
		return $tab['result'];
	}

	public	function like($user, $post)
	{
		global $pdo;
		$req = $pdo->prepare('INSERT INTO tb_likes (login, posts) VALUES (?, ?);');
		return $req->execute([$user, $post]);
	}

	public	function comment($user, $post, $content)
	{
		global $pdo;
		$req = $pdo->prepare('INSERT INTO tb_comments (login, posts, content) VALUES (?, ?, ?)');
		$req->execute([$user, $post, $content]);
	}

	public	function get_comments($post)
	{
		global $pdo;
		$req = $pdo->prepare('SELECT * FROM tb_comments WHERE posts=?;');
		$req->execute([$post]);
		$tab = $req->fetchAll();
		return $tab;
	}

	public	function pwd_change($login, $old, $new)
	{
		global $pdo;
		$req = $pdo->prepare('SELECT password FROM tb_users WHERE login=?');
		$req->execute([$login]);
		$tab = $req->fetch();
		if ($tab['password'] == hash('whirlpool', $old))
		{
			$req = $pdo->prepare('UPDATE tb_users SET password=? WHERE login=?');
			$req->execute([hash('whirlpool', $new), $login]);
		}
	}

	public	function get_owner($post)
	{
		global $pdo;
		$req = $pdo->prepare('SELECT owner FROM tb_posts WHERE posts=?');
		$req->execute([$post]);
		$tab = $req->fetch();
		return ($tab['owner']);
	}

	public	function get_mail($login)
	{
		global $pdo;
		$req = $pdo->prepare('SELECT mail FROM tb_users WHERE login=?');
		$req->execute([$login]);
		$tab = $req->fetch();
		return ($tab['mail']);
	}

	public	function number_page()
	{
		global $pdo;
		$req = $pdo->prepare('SELECT count(*) as nbr FROM tb_posts');
		$req->execute([]);
		$tab = $req->fetch();
		$nbr = $tab['nbr'];
		return ((int)($nbr / 10));
	}

	public	function get_user_posts()
	{
		global $pdo;
		$req = $pdo->prepare('SELECT * FROM tb_posts WHERE owner=?');
		$req->execute([$_SESSION['login']]);
		return ($req->fetchAll());
	}

	public	function remove_post($post)
	{
		global $pdo;
		$req = $pdo->prepare('DELETE FROM tb_posts WHERE posts=?;');
		return ($req->execute([$post]));
	}

	public	function email_exist($email)
	{
		global $pdo;
		$req = $pdo->prepare('SELECT count(*) as exist FROM tb_users WHERE mail=?');
		if ($req->execute([$email]) == true)
		{
			$tab = $req->fetch();
			return ($tab['exist']);
		}
		else 
			return (null);
	}

	public	function renew_password($token, $pass, $email)
	{
		global $pdo;
		$req = $pdo->prepare('UPDATE tb_users SET password=?, token=? WHERE mail=?');
		if ($req->execute([$pass, $token, $email]) == true)
			return true;
		else
			return false;
	}
}
?>
