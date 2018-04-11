<?php

namespace Utils;

/**
 * Classe servant de base pour les fonctions utilitaires.
 *
 * @package web.class.utils
 */
class Utils {

	/**
	 * Retrieves the stacktrace call.
	 *
	 * @param boolean $debugInFile Should it save the stack in file ?
	 * @return string the full stacktrace
	 */
	public static final function callStack($debugInFile = true) {
		$message = print_r(debug_backtrace(), true);

		if ($debugInFile) {
			self::log($message);
		}
		return $message;
	}

	/**
	 * Logs the given message in the php error log file
	 *
	 * @param string $message message to log
	 * @param mixed $time the date put in a prefix if given
	 */
	public static final function log($message, $time = NULL) {
		$messageDebug = str_repeat("-", 30) . "\r\n";
		if ($time != NULL) {
			$messageDebug .= "[" . self::dateJJ_MM_AAAA(true, $time) . "] ";
		}
		$messageDebug .= $message . "\r\n";

		$logPHP = fopen(PATH_LOG_PHP_ERROR, "a");
		fwrite($logPHP, $messageDebug, strlen($messageDebug));
		fclose($logPHP);
	}

	/**
	 * Fonction permettant d'afficher la date sous forme de JJ/MM/ANNE
	 *
	 * @param boolean $heure L'heure est à afficher ? Format HH:MM:SS
	 * @param integer $timestamp Timestamp (temps UNIX) à convertir. Si NULL ce sera le temps courant
	 * @return string La date sous forme JJ/MM/ANNE[ HH:MM:SS]
	 */
	public static final function dateJJ_MM_AAAA($heure = false, $timestamp = NULL) {
		if (is_null($timestamp) || !is_numeric($timestamp)) {
			$timestamp = time();
		}
		return $heure ? date("d/m/Y à H:i:s", $timestamp) : date("d/m/Y", $timestamp);
	}

	/**
	 * Date en format JJ/MM/AAAA transformé en timestamp UNIX
	 *
	 * @param string $date La date sous forme JJ/MM/AAAA. Peut être NULL: il prendra le jour d'aujourd'hui
	 * @return int Timestamp UNIX correspondant à la date
	 */
	public static final function dateTimestampUnix($date = NULL) {
		// $timestamp == "JJ/MM/AAAA"
		if (strpos($date, "/") !== false) {
			$infos = explode("/", $date);
			$jour = $infos[0];
			$mois = $infos[1];
			$annee = $infos[2];
		} else {
			$date = time();

			$jour = date("d", $date);
			$mois = date("m", $date);
			$annee = date("Y", $date);
		}

		return mktime(0, 0, 0, $mois, $jour, $annee);
	}

	/**
	 * Fonction permettant d'afficher la date sous forme littérale (Mercredi 06 Août 2014)
	 *
	 * @param integer $timestamp Timestamp (temps UNIX) à convertir
	 * @param boolean $heure L'heure est à afficher ? Format HH:MM:SS
	 * @return string La date sous forme littérale
	 */
	public static final function dateLitterale($timestamp, $heure = false) {
		$retDate = date('l', $timestamp);
		if ($retDate == "Monday") {
			$jour = "Lundi";
		} else {
			if ($retDate == "Tuesday") {
				$jour = "Mardi";
			} else {
				if ($retDate == "Wednesday") {
					$jour = "Mercredi";
				} else {
					if ($retDate == "Thursday") {
						$jour = "Jeudi";
					} else {
						if ($retDate == "Friday") {
							$jour = "Vendredi";
						} else {
							if ($retDate == "Saturday") {
								$jour = "Samedi";
							} else {
								$jour = "Dimanche";
							}
						}
					}
				}
			}
		}

		$retDate = date('m', $timestamp);
		if ($retDate == 1) {
			$mois = "Janvier";
		} else {
			if ($retDate == 2) {
				$mois = "Février";
			} else {
				if ($retDate == 3) {
					$mois = "Mars";
				} else {
					if ($retDate == 4) {
						$mois = "Avril";
					} else {
						if ($retDate == 5) {
							$mois = "Mai";
						} else {
							if ($retDate == 6) {
								$mois = "Juin";
							} else {
								if ($retDate == 7) {
									$mois = "Juillet";
								} else {
									if ($retDate == 8) {
										$mois = "Août";
									} else {
										if ($retDate == 9) {
											$mois = "Septembre";
										} else {
											if ($retDate == 10) {
												$mois = "Octobre";
											} else {
												if ($retDate == 11) {
													$mois = "Novembre";
												} else {
													$mois = "Décembre";
												}
											}
										}
									}
								}
							}
						}
					}
				}
			}
		}

		$nb = date('d', $timestamp);
		$annee = date('Y', $timestamp);
		$dateComplete = $jour . " " . $nb . " " . $mois . " " . $annee;

		if ($heure) {
			$dateComplete .= " à " . date('H\hi', $timestamp);
		}

		return $dateComplete;
	}

	/**
	 * Allows to send simple mail to people.
	 *
	 * @param string $subject the subject of the mail
	 * @param string $message the message of the mail
	 * @param string $mailTo the receiver mail address
	 * @param string $mailFrom the sender mail address
	 * @param string $mailCopy the copied receiver (comma separated)
	 * @param string $mailHiddenCopy the hidden copied receiver (comma separated)
	 * @return boolean true if mail was sent, false otherwise
	 * @see mail()
	 */
	public static final function sendMail(
		$subject,
		$message,
		$mailTo,
		$mailFrom = MAIL_ADMINISTRATOR,
		$mailCopy = NULL,
		$mailHiddenCopy = NULL
	) {
		$headers = "MIME-version: 1.0\n";
		$headers .= "Content-type: text/html; charset=utf-8\n";
		$headers .= "From: EVEMyAdmin <" . $mailFrom . ">\n";
		if (!is_null($mailCopy)) {
			$headers .= "Cc: " . $mailCopy . "\n";
		}
		if (!is_null($mailHiddenCopy)) {
			$headers .= "Bcc: " . $mailHiddenCopy . "\n";
		}

		return mail($mailTo, $subject, $message, $headers);
	}

	/**
	 * Retrieves the last position of the given character.
	 *
	 * @param string $string the full string
	 * @param string $character the character to find
	 * @return mixed The position of the character in the string, false if not found
	 */
	public static final function lastIndexOf($string, $character) {
		$pos = strpos(strrev($string), $character);
		if ($pos != false) {
			return (strlen($string) - $pos) - 1;
		}
		return false;
	}

	/**
	 * Vérifie que le nombre donné est valable
	 *
	 * @param string $verif Nombre à vérifier
	 * @param string $defaut Nombre par défaut à retourner si il n'est pas correct
	 * @return string Le nombre "vérifié" ou le nombre par défaut si incorrect
	 */
	public static final function verifierNombre($verif, $defaut) {
		if (!empty($verif) && $verif != NULL && is_numeric($verif)) {
			return $verif;
		}
		return $defaut;
	}

	/**
	 * Permet de transformer un caractère (alphabétique) en entier A = 0, Z = 26, AA = 27, ...
	 *
	 * @param string $chaine Chaine à transformer
	 * @return integer Transormation de la chaine en entier
	 */
	public static final function strToInt($chaine) {
		$res = 0;
		$ind = strlen($chaine) - 1;
		for ($i = 0; $i < strlen($chaine); $i++) {
			$char = strtoupper($chaine[$i]);
			if ($char >= 'A' && $char <= 'Z') {
				$valASCII = (ord($char) - 65) + 1;
				$res += $valASCII * pow(26, $ind);
				$ind--;
			}
		}
		return --$res;
	}

	/**
	 * Redirects the user to the given URI.<br>
	 * Uses header method
	 *
	 * @param string $uri destination URI
	 * @see header()
	 */
	public static final function redirect($uri) {
		header("Location: " . $uri);
		die;
	}

	/**
	 * Prints the given word in plural.
	 *
	 * @param int $nb the number
	 * @param string $word the word
	 * @return string the number and the word (with a s if required)
	 */
	function plural($nb, $word) {
		if ($nb > 1) {
			return $nb . " " . str_replace(" ", "s ", $word) . "s";
		}
		return $nb . " " . $word;
	}

	/**
	 * Hashes the given string into SHA512
	 *
	 * @param string $string the string
	 * @param string $salt the salt to apply (if any)
	 * @return string the SHA512 string
	 */
	public static final function sha512($string, $salt = NULL) {
		$hash = !is_null($salt) ?
			self::sha512($salt, NULL) . $string :
			$string;
		return hash("sha512", $hash);
	}

	/**
	 * Calls the htmlentities method for each value of the array
	 *
	 * @param string|array $toEncode string or array to encode.
	 * @return string string encoded
	 * @see htmlentities()
	 * @see html_entity_decode_array_map() to decode
	 */
	public static final function htmlentities_array_map($toEncode) {
		// Loop down the array to encode
		if (is_array($toEncode)) {
			foreach ($toEncode as $subArray) {
				self::htmlentities_array_map($subArray);
			}
		}
		return trim(
			htmlentities($toEncode, ENT_QUOTES, "UTF-8", false),
			" \t\n\r\0\x0B"
		);
	}

	/**
	 * Calls the html_entity_decode method for each value of the array
	 *
	 * @param string|array $toDecode string or array to decode
	 * @return string string decoded
	 */
	public static final function html_entity_decode_array_map($toDecode) {
		// Loop down the array to decode
		if (is_array($toDecode)) {
			foreach ($toDecode as $subArray) {
				self::html_entity_decode_array_map($subArray);
			}
		}
		return html_entity_decode($toDecode, ENT_QUOTES, "UTF-8");
	}

}
