<?php

namespace App\Models;

use App\Database;
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

        foreach ($attributes as $column => $value) {
            if ($column === 'updated_at') $value = currentDateTime();
            if ($column === 'created_at' && !$isUpdate) $value = currentDateTime();
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
            foreach ($fresh->toArray() as $key => $value) {
                $this->$key = $value;
            }
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
     * Retrieve records matching the given conditions.
     *
     * @param array $conditions  e.g., ['author' => 'Mark Twain']
     * @return static[]
     * @throws Exception
     */
    public static function findAllBy(array $params = []): array
    {
        if (!static::$db) throw new Exception("Database connection not set in " . static::class);
        if (empty($params)) return static::findAll();

        if (!isset($params['sort']) || !$params['sort']) $params['sort'] = static::DEFAULT_SORT;
        if (!isset($params['perPage']) || !$params['perPage']) $params['perPage'] = static::DEFAULT_PER_PAGE;
        if (!isset($params['page']) || !$params['page']) $params['page'] = static::DEFAULT_PAGE;

        if (isset($params['page']) && isset($params['perPage'])) {
            $params['limit'] = $params['perPage'];
            $params['offset'] = ($params['page'] - 1) * $params['perPage'];
        }

        $and = '';
        $sort = '';
        $pagination = '';

        if (isset($params['search']) && $params['search']) {
            $search = $params['search'];
            $and .= " AND (";
            $and .= implode(" OR ", array_map(fn($column) => "$column LIKE '%$search%'", static::getSearchableColumns()));
            $and .= " OR ";
            $and .= implode(" OR ", array_map(fn($column) => "levenshtein($column, '$search') < 3", static::getSearchableColumns()));
            $and .= ")";
        }

        if (isset($params['sort']) && $params['sort']) {
            $sort .= " ORDER BY ";
            $sort .= implode(", ", array_map(fn($sort) => "{$sort['column']} {$sort['order']}", $params['sort']));
        }

        if (isset($params['limit']) && $params['limit']) {
            $pagination .= " LIMIT " . $params['limit'];
        }

        if (isset($params['offset']) && $params['offset']) {
            $pagination .= " OFFSET " . $params['offset'];
        }

        $sqlData = "SELECT * FROM " . static::getTableName() . " WHERE 1 = 1 $and $sort $pagination";
        $sqlCount = "SELECT COUNT(*) as count FROM " . static::getTableName() . " WHERE 1 = 1 $and";

        $results = static::$db->query($sqlData);
        $count = static::$db->query($sqlCount);

        return [
            'page' => (int) $params['page'],
            'perPage' => (int) $params['perPage'],
            'lastPage' => (int) ceil($count[0]['count'] / $params['perPage']),
            'total' => (int) isset($count[0]['count']) ? $count[0]['count'] : 0,
            'data' => array_map(fn($row) => new static($row), $results)
        ];
    }

    /**
     * Retrieve the first record matching the given params.
     *
     * @param array $params
     * @return static|null
     * @throws Exception
     */
    public static function findOneBy(array $params): ?static
    {
        $results = static::findAllBy($params);
        return count($results) > 0 ? $results[0] : null;
    }
}
