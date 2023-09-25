<?php
/**
 * @author      Paweł Pakos <address @ example.com>
 * @version     1.0
 * @since       1.0
 */
class AuthBasic
{
	// Metoda generująca odcisk palca
	public function genFingerprint($algo)
	{
		if ($algo === null)
			$algo = 'sha512';
		$fp = hash_hmac($algo, $_SERVER['HTTP_USER_AGENT'], hash($algo, $_SERVER['REMOTE_ADDR'], true));
		return $fp;
	}

	// Metoda do tworzenia losowego kodu uwierzetylniania
	public function createCode($length = 6, $min = 1, $max = 999999)
	{
		$max = ($length > strlen($max)) ? str_pad($max, $length, 9, STR_PAD_RIGHT) : substr($max, 0, $length);
		return str_pad(mt_rand($min, $max), $length, '0', STR_PAD_LEFT);
	}

	/**  Metoda do tworzenia tokenu uwierzetylniania
	 * @param string $email Zmienna zawierająca email.
	 * @param mixed $id Zmienna zawierająca id
	*/
	public function createAuthToken($email, $id)
	{
		$authCode = $this->createCode();
		$authDate = date("Y-m-d");
		$authHours = date("H:i:s");
		$addrIp = '127.0.0.1'; # TODO ->code
		$opSys = 'Linux'; # TODO @see whichBrowser
		$browser = 'FF'; # TODO @see whichBrowser
		$cont = array(
			'emlAuth' => $email,
			'authCode' => $authCode,
			'authDate' => $authDate,
			'authHour' => $authHours,
			'addrIp' => $addrIp,
			'reqOs' => $opSys,
			'reqBrw' => $browser
		);
		// Rozpoczęcie operacji na bazie danych (
		#TODO: db->put())
		$tbl = 'cmsWebsiteAuth';
		$cols = 'session_id, usrId, addrIp, fingerprint, dateTime, content, email, authCode';
		$vals = '1234567890,$id,$addrIp,hash_hmac(sha512+USER_AGENT+hash()+TRUE),$dt,0,$eml,$code';
		$file = dirname(__FILE__) . '/db.txt';
		file_put_contents($file, serialize($cont));
		$fData = file_get_contents($file);
		// Sprawdzenie, czy dane zostały poprawnie zapisane
		#var_dump(unserialize($fData));
		// Zakończenie operacji na bazie danych
		#TODO: db->put()
		$tok = (unserialize($fData) == $cont) ? 0 : 'err:1045';
		$resp = ($tok === 0) ? $cont : false;
		return $resp;
	}

	/** Metoda do porównywania kodów uwierzetylniania
	 * 
	 * @param mixed $emlAuth Zmienna kodu uwierzetelniania email
	 * @param mixed $idAudh Zmienna kodu uwierzetelniania id
	 * @param mixed $authCode Zmienna kodu uwierzetelniania codu uwierzetelniania
	 * @return bool zwrucenie true dla rez jeśli jest tablicą 
	 */
	public function compAuthCode($emlAuth, $idAuth, $authCode)
	{
		$tbl = 'cmsSessionAuth';
		$sql = 'dateTime';
		$opt['where'] = "email='{$emlAuth}' AND idZgl={$idAuth} AND authCode='{$authCode}'";
		$res = $this->dbc->get(2, $sql, $tbl, $opt);
		if (is_array($res))
			$res = true;
		Event::log('sql', (__METHOD__), null, $this->dbc->dbgInfo());
		return $res;
	}

	/** Metoda do autoryzacji na podstawie adresu 
	 * @param mixed $person Pierwsza zmienna funkcji DoAuthByEmail 
	 * @param string $email Zmienna zawierająca email
	 */
	public function doAuthByEmail($person, $email)
	{
		#TODO: Implementacja autoryzacji na podstawie adresu email
	}

	/**
	 * Metoda do sprawdzania, czy żądanie jest ważne
	 * @param mixed $person Pierwsza zmienna funkcji checkIfValidRequest 
	 * @param string $email Zmienna zawierająca email
	 */
	public function checkIfValidReqest($person, $email)
	{
		#TODO:Implementacja sprawdzania ważności żądania
	}

	/** Prywatna metoda do sprawdzania ważności żądania 2-faktorowej autoryzacji
	 * @param mixed $emlAuth Pierwsza zmienna funkcji checkIfValidRequest2f 
	 * @param mixed $idAuth Druga zmienna funkcji checkIfValidRequest2f
	 */
	private function checkIfValidReqest2f($emlAuth, $idAuth)
	{
		#TODO:Implementacja sprawdzania ważności żądania 2-faktorowej autoryzacji
	}

	/** Metoda do weryfikacji kodu szybkiej rejestracji
	 * @param mixed $cdeNo Pierwszy parametr funkcji VerifyQuickRegCode
	 */
	public function verifyQuickRegCode($codeNo)
	{
		// Implementacja weryfikacji kodu szybkiej rejestracji
	}
}
?>
