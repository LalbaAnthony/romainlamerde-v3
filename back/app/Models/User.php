<?php

namespace App\Models;

use App\Helpers;

/**
 * The User model represents a record in the "user" table.
 */
class User extends Model
{
    public int $id = null;
    public string $name = '';
    public string $color = '';
    public ?string $birthdate = '';
    public ?string $token = '';
    public ?string $password = '';
    public ?string $last_login = '';
    public bool $is_admin = false;
    public bool $is_deleted = false;
    public ?string $updated_at = null;
    public ?string $created_at = null;

    /**
     * Return the associated database table name.
     *
     * @return string
     */
    public static function getTableName(): string
    {
        return 'user';
    }

    /**
     * Get the columns that should be searchable via a simple search form.
     *
     * @return string
     */
    public static function getSearchableColumns(): array
    {
        return ['name', 'color', 'birthdate'];
    }

    /**
     * Udpate the users login time.
     * 
     * @return void
     */
    public function updateLastLogin(): void
    {
        $this->last_login = Helpers::currentDateTime();
        $this->save();
    }
}
