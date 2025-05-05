<?php

namespace App\Models;

/**
 * The Sentence model represents a record in the "sentence" table.
 */
class Sentence extends Model
{
    public ?int $id = null;
    public int $quote_id = null;
    public ?int $user_id = null;
    public int $arrangement = 0;
    public string $content = '';
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
        return 'sentence';
    }

    /**
     * Get the columns that should be searchable via a simple search form.
     *
     * @return string
     */
    public static function getSearchableColumns(): array
    {
        return ['content'];
    }

    /**
     * Return the user associated with the sentence.
     *
     * @return User
     */
    public function getUser(): User
    {
        if ($this->user_id === null) return new User();
        return User::findOne($this->user_id);
    }

    /**
     * Return the quote associated with the sentence.
     *
     * @return Quote
     */
    public function getQuote(): Quote
    {
        if ($this->quote_id === null) return new Quote();
        return Quote::findOne($this->quote_id);
    }
}
