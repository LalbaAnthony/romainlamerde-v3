<?php

namespace App\Models;

/**
 * The Category model represents a record in the "category" table.
 */
class Category extends Model
{
    public ?int $id = null;
    public string $label = '';
    public ?string $description = '';
    public bool $is_verified = false;
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
        return 'category';
    }

    /**
     * Get the columns that should be searchable via a simple search form.
     *
     * @return string
     */
    public static function getSearchableColumns(): array
    {
        return ['label', 'description'];
    }

    /**
     * Udpate the category's verification status.
     * 
     * @return void
     */
    public function verify(bool $is_verified = false): void
    {
        $this->is_verified = $is_verified;
        $this->save();
    }
}
