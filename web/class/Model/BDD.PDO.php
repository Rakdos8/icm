<?php

namespace Model;

use Utils\Utils;

/**
 * Interface between MySQL and PDO.
 */
final class BDD extends \PDO {

	/**
	 * @var string current SQL query
	 */
	private $sqlQuery = NULL;

	/**
	 * BDD constructor.
	 */
	public function __construct() {
		try {
			parent::__construct(
				"mysql:dbname=" . constant("DB_NAME") . ";" .
				"host=" . constant("DB_URL") . ";" .
				"port=" . constant("BDD_PORT") . ";" .
				"charset=utf8;",
				constant("DB_LOGIN"),
				constant("DB_PASSWORD"),
				array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'")
			);
		} catch (\PDOException $ex) {
			die("Impossible to connect to Database: " . $ex->getMessage() . " !");
		}
	}

	/**
	 * Executes the given SQL query.
	 *
	 * @param string $sql the SQL query
	 * @return int number of row modified by the query
	 */
	public final function rawExec($sql) {
		$ret = parent::exec($sql);

		if ($ret === false) {
			$this->logSqlError();
			return NULL;
		}
		return $ret;
	}

	/**
	 * Execute the SQL query and returns a bean.
	 *
	 * @param string $sql sql query to send
	 * @param string $className the class name
	 * @return array array of bean that match the SQL query
	 */
	public final function objExec($sql, $className) {
		$prepared = parent::prepare($sql);
		$ret = $prepared->execute();

		if ($ret === false) {
			$this->logSqlError($prepared);
			return NULL;
		}

		return $prepared->fetchAll(\PDO::FETCH_CLASS, $className);
	}

	/**
	 * Logs the SQL error into file + send mail
	 *
	 * @param \PDOStatement $errorSource PDOStatement or NULL if it was a raw query
	 */
	public final function logSqlError($errorSource = NULL) {
		if ($errorSource != NULL && $errorSource instanceof \PDOStatement) {
			$this->sqlQuery = $errorSource->queryString;
		} else {
			$errorSource = $this;
		}
		$errorLog = $errorSource->errorInfo();

		$prefix = "[" . Utils::dateJJ_MM_AAAA(true, time()) . "] ";
		$message = $prefix . "SQLSTATE: " . $errorLog[0] . "\n";
		$message .= $prefix . "Erreur numéro: " . $errorLog[1] . "\n";
		$message .= $prefix . "Message d'erreur: " . $errorLog[2] . "\n";
		$message .= $prefix . "Requête SQL utilisée: '" . $this->sqlQuery . "'\n";
		$message .= Utils::callStack(false);
		$message .= str_repeat("=", 60) . "\n";

		// Write into the log file
		$logSQL = fopen(PATH_LOG_SQL_ERROR, "a");
		fwrite($logSQL, $message, strlen($message));
		fclose($logSQL);

		// Sends an email
		$message = str_replace("\n", "<br>", $message);
		$message = "<html><body><h1>Une erreur SQL est survenue sur le site de EVEMyAdmin !</h1>" . $message;
		$message .= "<br><br>Cette erreur a aussi été loggé dans le fichier " . PATH_LOG_SQL_ERROR . ".</body></html>";
		Utils::sendMail(
			"EMA - Erreur SQL le " . Utils::dateJJ_MM_AAAA(true, time()),
			$message,
			MAIL_DEVELOPER
		);
	}

}
