<?php

namespace App\Models;

use App\Database;
use App\Helpers;
use Exception;

/**
 * Abstract base model class to provide Active Record style functionality.
 */
abstract class Model
{
    /**
     * Default values for properties that are common to all models.
     */
    const DEFAULT_PRIMARY_KEY = 'id';

    /**
     * Default values for methods that accept optional parameters.
     */
    const DEFAULT_PER_PAGE = 10;
    const DEFAULT_PAGE = 1;
    const DEFAULT_SORT = [['column' => 'created_at', 'order' => 'DESC']];

    /**
     * The Database instance.
     *
     * @var Database|null
     */
    protected static ?Database $db = null;

    /**
     * Construct a model with an optional data array.
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->fill($data);
    }

    /**
     * Destructor to clean up the model.
     */
    public function __destruct()
    {
        static::$db = null;
    }

    /**
     * Fill the model's properties from an array.
     *
     * Only properties that already exist in the object will be set.
     *
     * @param array $data
     */
    public function fill(array $data): void
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    /**
     * Save the current model to the database.
     *
     * Performs an UPDATE if the primary key exists, or an INSERT otherwise.
     *
     * @return bool
     * @throws Exception
     */
    public function save(): bool
    {
        if (!static::$db) throw new Exception("Database connection not set in " . static::class);

        $primaryKey = static::getPrimaryKey();
        $isUpdate = isset($this->$primaryKey) && !empty($this->$primaryKey);
        $attributes = $this->toArray();

        foreach ($attributes as $column => &$value) {
            if ($column === 'updated_at') $value = Helpers::currentDateTime();
            if ($column === 'created_at' && !$isUpdate) $value = Helpers::currentDateTime();
        }

        if ($isUpdate) {
            // UPDATE existing record
            $columns = array_keys($attributes);
            $placeholders = implode(", ", array_map(fn($column) => "$column = ?", $columns));
            $params = array_values($attributes);

            $sql = "UPDATE " . static::getTableName() . " SET $placeholders WHERE $primaryKey = ?";
            $params[] = $this->$primaryKey;

            $result = static::$db->execute($sql, $params);
        } else {
            // INSERT new record
            if (array_key_exists($primaryKey, $attributes)) unset($attributes[$primaryKey]);

            $columns = array_keys($attributes);
            $placeholders = implode(", ", array_fill(0, count($columns), "?"));
            $params = array_values($attributes);

            $sql = "INSERT INTO " . static::getTableName() . " (" . implode(", ", $columns) . ") VALUES ($placeholders)";
            $result = static::$db->execute($sql, $params);

            if ($result) $this->$primaryKey = static::$db->lastInsertId();
        }

        if ($result) {
            return true;
        }

        return false;
    }

    /**
     * Delete the current model from the database.
     *
     * @return bool
     * @throws Exception
     */
    public function delete(): bool
    {
        if (!static::$db) throw new Exception("Database connection not set in " . static::class);

        $primaryKey = static::getPrimaryKey();
        if (!isset($this->$primaryKey)) {
            return false;
        }
        $sql = "DELETE FROM " . static::getTableName() . " WHERE $primaryKey = ?";
        return static::$db->execute($sql, [$this->$primaryKey]);
    }

    /**
     * Refresh the current model's data from the database.
     *
     * @return bool
     * @throws Exception
     */
    public function refresh(): bool
    {
        $primaryKey = static::getPrimaryKey();
        if (!isset($this->$primaryKey)) {
            return false;
        }
        $fresh = static::findOne($this->$primaryKey);
        if ($fresh) {
            $this->fill($fresh->toArray());
            return true;
        }
        return false;
    }

    /**
     * Convert the model to an associative array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return get_object_vars($this);
    }

    /**
     * Set the database connection.
     *
     * @param Database $db
     */
    public static function setDatabase(Database $db): void
    {
        static::$db = $db;
    }

    /**
     * Get the database table name.
     *
     * Each child class must implement this.
     *
     * @return string
     */
    abstract public static function getTableName(): string;

    /**
     * Get the columns that should be searchable via a simple search form.
     *
     * Override this in child classes to specify which columns should be searched.
     *
     * @return array
     */
    abstract public static function getSearchableColumns(): array;

    /**
     * Get the primary key column name.
     *
     * Override this in child classes if your primary key differs.
     *
     * @return string
     */
    protected static function getPrimaryKey(): string
    {
        return static::DEFAULT_PRIMARY_KEY;
    }

    /**
     * Retrieve all records from the table.
     *
     * @return static[]
     * @throws Exception
     */
    public static function findAll(): array
    {
        if (!static::$db) throw new Exception("Database connection not set in " . static::class);

        $sql = "SELECT * FROM " . static::getTableName();
        $results = static::$db->query($sql);

        return [
            'page' => 1,
            'perPage' => count($results),
            'lastPage' => 1,
            'total' => count($results),
            'data' => array_map(fn($row) => new static($row), $results)
        ];
    }

    /**
     * Retrieve a record by its primary key.
     *
     * @param int $id
     * @return static|null
     * @throws Exception
     */
    public static function findOne(int $id): ?static
    {
        if (!static::$db) throw new Exception("Database connection not set in " . static::class);

        $sql = "SELECT * FROM " . static::getTableName() . " WHERE " . static::getPrimaryKey() . " = ?";
        $result = static::$db->query($sql, [$id]);

        if ($result && count($result) > 0) {
            return new static($result[0]);
        }

        return null;
    }

    /**
     * Retrieve a record by its primary key.
     *
     * @return static|null
     * @throws Exception
     */
    public static function findOneRandom(): ?static
    {
        if (!static::$db) throw new Exception("Database connection not set in " . static::class);

        $sql = "SELECT * FROM " . static::getTableName() . " ORDER BY RAND() LIMIT 1";
        $result = static::$db->query($sql);

        if ($result && count($result) > 0) {
            return new static($result[0]);
        }

        return null;
    }

    /**
     * Retrieve records matching the given conditions.
     *
     * @param array $params
     * @return static[]
     * @throws Exception
     */
    public static function findAllBy(array $params = []): array
    {
        if (!static::$db) throw new Exception("Database connection not set in " . static::class);
        if (empty($params)) return static::findAll();

        // Set default values for optional parameters.
        if (!isset($params['sort']) || !$params['sort']) $params['sort'] = static::DEFAULT_SORT;
        if (!isset($params['perPage']) || !$params['perPage']) $params['perPage'] = static::DEFAULT_PER_PAGE;
        if (!isset($params['page']) || !$params['page']) $params['page'] = static::DEFAULT_PAGE;

        // Calculate pagination values.
        if (isset($params['page']) && isset($params['perPage'])) {
            $params['limit'] = (int)$params['perPage'];
            $params['offset'] = ((int)$params['page'] - 1) * (int)$params['perPage'];
        }

        $bindings = [];
        $and = '';

        // Build a secure search clause using placeholders.
        if (isset($params['search']) && $params['search']) {
            $search = $params['search'];
            $searchClauseParts = [];
            foreach (static::getSearchableColumns() as $column) {
                // Validate the column name to allow only safe characters.
                if (preg_match('/^[a-zA-Z0-9_]+$/', $column)) {
                    $searchClauseParts[] = "$column LIKE ?";
                    $bindings[] = "%$search%";
                }
            }
            foreach (static::getSearchableColumns() as $column) {
                if (preg_match('/^[a-zA-Z0-9_]+$/', $column)) {
                    $searchClauseParts[] = "levenshtein($column, ?) < 3";
                    $bindings[] = $search;
                }
            }
            if (!empty($searchClauseParts)) {
                $and .= " AND (" . implode(" OR ", $searchClauseParts) . ")";
            }
        }

        // Build a secure sort clause by validating each sort option.
        $sort = '';
        if (isset($params['sort']) && $params['sort']) {
            $sortParts = [];
            foreach ($params['sort'] as $sortOption) {
                $column = $sortOption['column'] ?? '';
                $order = strtoupper($sortOption['order'] ?? '');
                // Only allow column names with alphanumerics and underscores, and orders ASC/DESC.
                if (preg_match('/^[a-zA-Z0-9_]+$/', $column) && in_array($order, ['ASC', 'DESC'])) {
                    $sortParts[] = "$column $order";
                }
            }
            if (!empty($sortParts)) {
                $sort .= " ORDER BY " . implode(", ", $sortParts);
            }
        }

        // Build pagination clause.
        $pagination = '';
        if (isset($params['limit']) && $params['limit']) {
            $limit = (int)$params['limit'];
            $pagination .= " LIMIT $limit";
        }
        if (isset($params['offset']) && $params['offset']) {
            $offset = (int)$params['offset'];
            $pagination .= " OFFSET $offset";
        }

        $table = static::getTableName();
        $sqlData = "SELECT * FROM $table WHERE 1 = 1 $and $sort $pagination";
        $sqlCount = "SELECT COUNT(*) as count FROM $table WHERE 1 = 1 $and";

        $results = static::$db->query($sqlData, $bindings);
        $countResult = static::$db->query($sqlCount, $bindings);
        $totalCount = isset($countResult[0]['count']) ? (int)$countResult[0]['count'] : 0;
        $lastPage = $params['perPage'] > 0 ? (int)ceil($totalCount / $params['perPage']) : 1;

        return [
            'page' => (int)$params['page'],
            'perPage' => (int)$params['perPage'],
            'lastPage' => $lastPage,
            'total' => $totalCount,
            'data' => array_map(fn($row) => new static($row), $results)
        ];
    }

    /**
     * Retrieve the first record matching the given params.
     *
     * @param array $params
     * @return array
     * @throws Exception
     */
    public static function findOneBy(array $params): array
    {
        $result = static::findAllBy($params);

        if (isset($result['data']) && count((array) $result['data']) > 0) {
            $result['data'] = array_slice((array) $result['data'], 0, 1);
        }

        return $result;
    }
}
