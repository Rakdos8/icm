<?php

namespace Model;

/**
 * Handles every bean in the DataBase
 */
abstract class Model {

	/**
	 * Primary field name
	 * @var string
	 */
	protected $pkID;

	/**
	 * Array of field name and its value
	 * @var array
	 */
	protected $tuple = array();

	/**
	 * Creates a common Model bean
	 *
	 * @param array $values array with field name and values
	 */
	public function __construct($values = NULL) {
		if (!is_null($values)) {
			$this->tuple = $values;
		}
	}

	/**
	 * @param string $name the field name
	 * @return string the value of the field name, NULL if not found
	 */
	public final function __get($name) {
		if (isset($this->tuple[$name])) {
			return $this->tuple[$name];
		}
		return NULL;
	}

	/**
	 * @param string $name the field name
	 * @param string $value the new value of the field
	 */
	public final function __set($name, $value) {
		$this->tuple[$name] = $value;
	}

	/**
	 * @return string the class name
	 */
	public final function __toString() {
		return get_class($this);
	}

	/**
	 * Removes the current bean from Database
	 *
	 * @return integer the number of deleted bean, -1 in case of error
	 */
	public final function delete() {
		$lienBDD = new BDD();

		if ($lienBDD != NULL) {
			$clauseWhere = array();
			foreach ($this->tuple as $champ => $valeur) {
				$clauseWhere[] = $champ . " = " . $lienBDD->encodeEtSecurise($valeur, false);
			}
			$sql = "
				DELETE FROM
					" . get_class($this) . "
				WHERE
					" . implode(" AND ", $clauseWhere) . "
			";

			$nbLigneAffecte = $lienBDD->exec($sql);
			if ($nbLigneAffecte !== NULL) {
				return $nbLigneAffecte;
			}
		}
		return -1;
	}

	/**
	 * Updates the current bean from DataBase
	 *
	 * @return integer the number of update bean, -1 in case of error
	 */
	public final function update() {
		$lienBDD = new BDD();

		if ($lienBDD != NULL) {
			$clauseSet = array();
			foreach ($this->tuple as $champ => $valeur) {
				if ($champ != $this->pkID) {
					// Sécurisation des valeurs à mettre à jour !
					$clauseSet[] = $champ . " = " . $lienBDD->encodeEtSecurise($valeur, false);
				}
			}

			$sql = "
				UPDATE
					" . get_class($this) . " 
				SET
					" . implode(", ", $clauseSet) . "
				WHERE
					" . $this->pkID . " = " . $lienBDD->encodeEtSecurise($this->tuple[$this->pkID], false) . "
				;";

			$nbLigneAffecte = $lienBDD->exec($sql);
			if ($nbLigneAffecte !== NULL) {
				return $nbLigneAffecte;
			}
		}
		return -1;
	}

	/**
	 * Inserts new line in DataBase.
	 *
	 * @param boolean $returnPkId should we return the inserted ID ?
	 * @return integer the number of inserted bean, -1 in case of error
	 */
	public final function insert($returnPkId = false) {
		$lienBDD = new BDD();

		if ($lienBDD != NULL) {
			$champs = array_keys($this->tuple);
			$valeurs = array_values($this->tuple);

			// Sécurisation des valeurs à insérer !
			foreach ($valeurs as &$valeur) {
				$valeur = $lienBDD->encodeEtSecurise($valeur);
			}

			$sql = "
				INSERT INTO
					" . get_class($this) . "
				(
					" . implode(", ", $champs) . "
				)
				VALUES
				( 
					" . implode(", ", $valeurs) . " 
				)
				;";
		}

		$nbLigneAffecte = $lienBDD->exec($sql);
		if ($nbLigneAffecte !== NULL) {
			return $returnPkId ? $lienBDD->lastInsertId() : $nbLigneAffecte;
		}
		return -1;
	}

}
