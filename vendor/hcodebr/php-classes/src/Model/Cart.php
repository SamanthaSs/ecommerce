<?php

namespace Hcode\Model;

use \Hcode\DB\Sql;
use \Hcode\{Mailer, Model};

class Category extends Model 
{
	const SESSION = "Cart";

	public static function getFromSession()
	{
		$cart = new Cart();

		if(isset($_SESSION[Cart::SESSION]) && (int)$_SESSION[Cart::SESSION]['idcart'] > 0)
		{
			$cart->get((int)$_SESSION[Cart::SESSION]['idcart']);
		} else
		{
			$cart->getFromSession();

			if (!(int)$cart->getidcart() > 0) {
				$data = [
					'dessessionid'=>session_id()
				];
			}
		}
	}

	public function getFromSession(int $idcart)
	{
		$sql = new Sql();

		$results = $sql->select("SELECT * FROM tb_carts WHERE dessessionid = :dessessionid",[
			':dessessionid'=>$idcart
		]);

		if (count($results) > 0) {
			$this->setData($results[0]);
		}
	}

	public function get(int $idcart)
	{
		$sql = new Sql();

		$results = $sql->select("SELECT * FROM tb_carts WHERE idcart = :idcart",[
			':idcart'=>$idcart
		]);

		if (count($results) > 0) {
			$this->setData($results[0]);
		}
	}

	public function save()
	{
		$sql = new Sql();

		$results = $sql->select("CALL sp_carts_save(:idcart, :dessessionid, :iduser, :desszipcode, :vlfreight, :nrdays)",[
			':idcart'=>$this->getidcart(),
			':dessessionid'=>$this->getdessessionid(),
			':iduser'=>$this->getiduser(),
			':desszipcode'=>$this->getdesszipcode(),
			':vlfreight'=>$this->getvlfreight(),
			':nrdays'=>$this->getnrdays()
		]);

		$this->setData($results[0]);
	}	
}	
