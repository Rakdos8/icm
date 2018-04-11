<?php

namespace Model;

use Utils\ErrorHandler;

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
	 * @var string $table the table name
	 */
	protected $table;

	/**
	 * Creates a common Model bean
	 *
	 * @param string $table the table name with the schema
	 * @param string $pkID the name of column of the primary key
	 */
	public function __construct($table, $pkID = "id") {
		$this->table = $table;
		$this->pkID = $pkID;
	}

	/**
	 * @return string the class name
	 */
	public final function __toString() {
		return get_class($this);
	}

	/**
	 * Inserts new line in DataBase.
	 *
	 * @return integer the number of inserted bean
	 */
	public final function insert() {
		$db = new MySQL();

		$properties = self::getProperties($this);
		$columns = array_keys($properties);
		$values = array_values($properties);

		// Quoting all value which is not null and a string in case
		foreach ($values as &$value) {
			if (is_null($value)) {
				$value = "NULL";
			} else if (is_string($value)) {
				$value = $db->quote($value);
			}
		}

		//TODO: IGNORE + ON DUPLICATE
		$sql = "
	INSERT INTO
	" . $this->table . "
	(" . implode(", ", $columns) . ")
	VALUES
	(" . implode(", ", $values) . ");";

		$nbInsert = $db->rawExec($sql);
		if ($nbInsert !== NULL) {
			$this->{$this->pkID} = $db->lastInsertId();
			return $nbInsert;
		}
		return -1;
	}

	/**
	 * Removes the current bean from Database
	 *
	 * @return integer the number of deleted bean, -1 in case of error
	 */
	public final function delete() {
		$lienBDD = new MySQL();

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
		$lienBDD = new MySQL();

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
	 * @param object $object any object
	 * @return array the properties on its column name and its value
	 */
	private static function getProperties($object) {
		$properties = array();
		try {
			$reflect = new \ReflectionClass(get_class($object));
			foreach ($reflect->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
				$properties[$property->getName()] = $property->getValue($object);
			}
		} catch (\ReflectionException $ex) {
			ErrorHandler::logException($ex);
		}
		return $properties;
	}

}
