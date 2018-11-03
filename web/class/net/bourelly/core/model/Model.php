<?php

namespace net\bourelly\core\model;

use net\bourelly\core\utils\handler\ErrorHandler;

/**
 * Handles every bean in the DataBase
 *
 * @package net\bourelly\core\model
 */
abstract class Model {

	/**
	 * Primary field name
	 * @var string
	 */
	protected $primaryField;

	/**
	 * Unique fields (columns names)
	 * @var array
	 */
	protected $uniqueFields;

	/**
	 * @var string $table the table name
	 */
	protected $table;

	/**
	 * Creates a common net.bourelly.core.model bean
	 *
	 * @param string $table the table name with the schema
	 * @param array $uniqueFields list of unique field (column names)
	 * @param string $primaryField the name of column of the primary key
	 */
	public function __construct(
			string $table,
			array $uniqueFields = array(),
			string $primaryField = "id"
	) {
		$this->table = $table;
		$this->uniqueFields = $uniqueFields;
		$this->primaryField = $primaryField;
	}

	/**
	 * Only allow to update fields, not creating magic ones.
	 *
	 * @param string $name the name of the field
	 * @param mixed $value the value of the field
	 */
	public function __set(string $name, $value): void {
		$properties = self::getProperties($this);
		if (array_key_exists($name, $properties)) {
			$this->{$name} = $value;
		}
	}

	/**
	 * @return string the class name
	 */
	public final function __toString() {
		return get_class($this);
	}

	/**
	 * Hides non public fields.
	 *
	 * @return array the array of info to print
	 */
	public function __debugInfo() {
		return self::getProperties($this);
	}

	/**
	 * Inserts new line in DataBase.<br>
	 * In case of duplicate entry, it will update non unique fields.
	 *
	 * @param bool $ignore should it be an insert ignore ? (false by default)
	 * @return bool true if the insert is done, false otherwise
	 */
	public function insert(
			bool $ignore = false
	): bool {
		$properties = self::getProperties($this);
		$columns = array_keys($properties);
		$values = array_values($properties);

		$columnOnUpdate = array();
		foreach ($columns as $column) {
			if (strcmp($column, $this->primaryField) == 0 ||
				in_array($column, $this->uniqueFields)
			) {
				continue;
			}
			$columnOnUpdate[] = $column . " = VALUES(" . $column . ")";
		}

		$sql = "
	INSERT " . ($ignore ? "IGNORE " : "") . "INTO
	" . $this->table . "
	(" . implode(", ", $columns) . ")
	VALUES
	(" . implode(", ", MySQL::createBindingArray($columns)) . ")
	ON DUPLICATE KEY UPDATE
	" . implode(", ", $columnOnUpdate) . ";";

		$db = new MySQL();
		$statement = $db->prepare($sql);
		for ($i = 0; $i < count($values); $i++) {
			$value = $values[$i];
			if (is_bool($value)) {
				$values[$i] = intval($value);
			}
		}
		$status = $statement->execute($values);
		$statement = NULL;

		// Sets back the ID
		if ($status) {
			$this->{$this->primaryField} = $db->lastInsertId();
		}
		return $status;
	}

	/**
	 * Updates the current bean from DataBase
	 *
	 * @return bool true if the insert is done, false otherwise
	 */
	public function update(): bool {
		$properties = self::getProperties($this);
		$columns = array_keys($properties);
		$values = array_values($properties);

		$setArray = array();
		for ($i = 0; $i < count($properties); $i++) {
			$column = $columns[$i];
			if (strcmp($column, $this->primaryField) == 0 ||
				in_array($column, $this->uniqueFields)
			) {
				// Remove the value for binding
				unset($values[$i]);
				continue;
			}
			$setArray[] = $column . " = ?";
		}
		$values = array_values($values);

		$sql = "
	UPDATE
		" . $this->table . "
	SET
		" . implode(", ", $setArray) . "
	WHERE
		" . $this->primaryField . " = ?
	;";
		// Adds the primary key binding at the end
		$values[] = $this->{$this->primaryField};

		$db = new MySQL();
		$statement = $db->prepare($sql);
		$status = $statement->execute($values);
		$statement = NULL;

		return $status;
	}

	/**
	 * Removes the current bean from Database
	 *
	 * @return bool true if the delete is done, false otherwise
	 */
	public function delete(): bool {
		$sql = "
	DELETE FROM
		" . $this->table . "
	WHERE
		" . $this->primaryField . " = ?
	;";

		$db = new MySQL();
		$statement = $db->prepare($sql);
		$status = $statement->execute(array($this->{$this->primaryField}));
		$statement = NULL;

		return $status;
	}

	/**
	 * Replace any string by its enum value.
	 *
	 * @param string $column the column name
	 * @param string $enumClassName the class name (with namespace) of the BasicEnum
	 */
	protected final function replaceStringByEnums(
			string $column,
			string $enumClassName
	) {
		$this->{$column} = new $enumClassName($this->{$column});
	}

	/**
	 * Retrieves properties of the given object.
	 *
	 * @param object $object any object
	 * @return array the properties on its column name and its value
	 */
	private static function getProperties($object): array {
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
