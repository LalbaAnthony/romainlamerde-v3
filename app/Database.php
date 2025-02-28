<?php

namespace App;

/**
 * Class Database
 * 
 * This class is used to interact with the database.
 */
class Database
{
    /**
     * Hostname of the database server.
     */
    private string $dbHost = '';

    /**
     * Database name.
     */
    private string $dbName = '';

    /**
     * Username to connect to the database.
     */
    private string $dbUser = '';

    /**
     * Password to connect to the database.
     */
    private string $dbPass = '';

    /**
     * Connection to the database.
     */
    private ?PDO $connection = null;

    /**
     * Database class constructor.
     */
    public function __construct(string $dbHost = null, string $dbName = null, string $dbUser = null, string $dbPass = null)
    {
        $this->dbHost = $dbHost ?? DB_HOST;
        $this->dbName = $dbName ?? DB_NAME;
        $this->dbUser = $dbUser ?? DB_USER;
        $this->dbPass = $dbPass ?? DB_PASSWORD;
        $this->connect();
    }

    /**
     * Destructor to close the connection.
     */
    public function __destruct()
    {
        $this->close();
    }

    /**
     * Establishes a connection to the database.
     * 
     * @return void
     * @throws Exception
     */
    private function connect(): void
    {
        try {
            $this->connection = new PDO(
                "mysql:host={$this->dbHost};dbname={$this->dbName}",
                $this->dbUser,
                $this->dbPass
            );
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            throw new Exception("Error connecting to the database: " . $e->getMessage());
        }
    }

    /**
     * Closes the connection to the database.
     * 
     * @return void
     */
    public function close(): void
    {
        $this->connection = null;
    }

    /**
     * Ensures that a connection to the database is established.
     * 
     * @return void
     */
    private function connectOrNot(): void
    {
        if ($this->connection !== null) {
            return;
        }
        $this->connect();
    }

    /**
     * Rebuilds the query with the parameters.
     * 
     * @param string $query SQL request with placeholders.
     * @param array $params Parameters to inject into the request.
     * @return string
     * @throws Exception
     */
    public function mergeQueryAndParams(string $query, array $params = []): string
    {
        // ! This method is unsecure and should be used for nothing but debugging.

        if (!is_string($query)) {
            throw new Exception("Query must be a string.");
        }
        if (empty($params)) {
            return $query;
        }
        if (count($params) !== substr_count($query, '?')) {
            throw new Exception("The number of parameters does not match the number of placeholders in the query.");
        }

        foreach ($params as $key => $value) {
            $query = str_replace($key, "\"" . $value . "\"", $query);
        }

        return $query;
    }

    /**
     * Binds values to a PDO statement.
     * 
     * @param PDOStatement $statement The PDO statement.
     * @param array $params Parameters to bind.
     * @return void
     * @throws Exception
     */
    private static function bindValues(PDOStatement &$statement, array $params): void
    {
        if (!is_array($params)) {
            throw new Exception("Params must be an array.");
        }
        if (empty($params)) {
            return;
        }

        // Handle positional (numeric) parameters.
        if (array_keys($params) === range(0, count($params) - 1)) {
            foreach ($params as $index => $value) {
                // PDO positional parameters are 1-indexed.
                $statement->bindValue($index + 1, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
            }
        } else {
            // Named parameters.
            foreach ($params as $key => $value) {
                $statement->bindValue($key, $value);
            }
        }
    }

    /**
     * Prepares and executes a query, returning the PDO statement.
     * 
     * @param string $query The SQL query.
     * @param array $params Parameters to inject into the query.
     * @return PDOStatement
     * @throws Exception
     */
    private function executeQuery(string $query, array $params = []): PDOStatement
    {
        $this->connectOrNot();

        try {
            $statement = $this->connection->prepare($query);
            self::bindValues($statement, $params);
            $statement->execute();
            return $statement;
        } catch (PDOException $e) {
            if (APP_DEBUG) dd($this->mergeQueryAndParams($query, $params));
            throw new Exception("Error executing the query: " . $e->getMessage());
        }
    }

    /**
     * Executes a query that modifies data (INSERT, UPDATE, DELETE).
     * 
     * @param string $query The SQL query.
     * @param array $params Parameters to inject into the query.
     * @return bool True on success, false on failure.
     * @throws Exception
     */
    public function execute(string $query, array $params = []): bool
    {
        $statement = $this->executeQuery($query, $params);
        return $statement !== false; // Even if rowCount is 0, the query might have executed successfully.
    }

    /**
     * Executes a SELECT query and returns all results as an associative array.
     * This method is used by Model for fetching data.
     * 
     * @param string $query The SQL query.
     * @param array $params Parameters to inject into the query.
     * @return array The resulting rows.
     * @throws Exception
     */
    public function query(string $query, array $params = []): array
    {
        $statement = $this->executeQuery($query, $params);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Fetches the first result of a SELECT query.
     * 
     * @param string $query The SQL query.
     * @param array $params Parameters to inject into the query.
     * @return array|false The first row of the result set, or false if none.
     * @throws Exception
     */
    public function fetchFirst(string $query, array $params = [])
    {
        $statement = $this->executeQuery($query, $params);
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Retrieves the last inserted ID.
     * 
     * @return int
     * @throws Exception
     */
    public function lastInsertId(): int
    {
        $this->connectOrNot();
        try {
            return (int) $this->connection->lastInsertId();
        } catch (PDOException $e) {
            throw new Exception("Error getting the last inserted id: " . $e->getMessage());
        }
    }

    /**
     * Begins a transaction.
     * 
     * @return void
     * @throws Exception
     */
    public function startTransaction(): void
    {
        $this->connectOrNot();
        try {
            $this->connection->beginTransaction();
        } catch (PDOException $e) {
            throw new Exception("Error starting the transaction: " . $e->getMessage());
        }
    }

    /**
     * Commits a transaction.
     * 
     * @return void
     * @throws Exception
     */
    public function commit(): void
    {
        $this->connectOrNot();
        try {
            $this->connection->commit();
        } catch (PDOException $e) {
            throw new Exception("Error committing the transaction: " . $e->getMessage());
        }
    }

    /**
     * Rolls back a transaction.
     * 
     * @return void
     * @throws Exception
     */
    public function rollback(): void
    {
        $this->connectOrNot();
        try {
            $this->connection->rollBack();
        } catch (PDOException $e) {
            throw new Exception("Error rolling back the transaction: " . $e->getMessage());
        }
    }
}
