<?php

namespace App\Models;
use Exception;

/**
 * The Quote model represents a record in the "quote" table.
 */
class Quote extends Model
{
    public ?int $id = null;
    public ?int $place_id = null;
    public ?int $drug_id = null;
    public ?string $date = null;
    public ?string $explanation = '';
    public bool $is_highlighted = false;
    public bool $is_verified = false;
    public bool $is_deleted = false;
    public ?string $updated_at = null;
    public ?string $created_at = null;

    /**
     * A collection of Category objects associated with this quote.
     *
     * @var Category[]
     */
    protected array $categories = [];

    /**
     * Return the associated database table name.
     *
     * @return string
     */
    public static function getTableName(): string
    {
        return 'quote';
    }

    /**
     * Get the columns that should be searchable via a simple search form.
     *
     * @return string
     */
    public static function getSearchableColumns(): array
    {
        return ['explanation'];
    }

    /**
     * Returns the collection of categories for this quote.
     * If not loaded yet, it lazy-loads them from the database.
     *
     * @return Category[]
     * @throws Exception
     */
    public function getCategories(): array
    {
        // Return already loaded categories.
        if (!empty($this->categories)) return $this->categories;

        if ($this->id === null) throw new Exception("Quote must be saved before loading categories.");

        $sql = "SELECT c.* FROM category c 
                INNER JOIN quote_category qc ON c.id = qc.category_id
                WHERE qc.quote_id = ?";
        $results = static::$db->query($sql, [$this->id]);
        $this->categories = array_map(fn($row) => new Category($row), $results);
        return $this->categories;
    }

    /**
     * Attaches a Category to this quote.
     * Inserts a record into the pivot table (quote_category).
     *
     * @param Category $category
     * @return bool True if successful.
     * @throws Exception
     */
    public function attachCategory(Category $category): bool
    {
        if ($this->id === null) throw new Exception("Quote must be saved before attaching categories.");
        if ($category->id === null) throw new Exception("Category must be saved before attaching.");

        // Optional: Check if the association already exists.
        $sqlCheck = "SELECT * FROM quote_category WHERE quote_id = ? AND category_id = ?";
        $existing = static::$db->query($sqlCheck, [$this->id, $category->id]);
        if (!empty($existing)) {
            return true; // Already attached.
        }

        $sql = "INSERT INTO quote_category (quote_id, category_id) VALUES (?, ?)";
        $result = static::$db->execute($sql, [$this->id, $category->id]);
        if ($result) {
            // Update local collection if already loaded.
            $this->categories[] = $category;
        }
        return $result;
    }

    /**
     * Detaches a Category from this quote.
     * Deletes the record from the pivot table (quote_category).
     *
     * @param Category $category
     * @return bool True if successful.
     * @throws Exception
     */
    public function detachCategory(Category $category): bool
    {
        if ($this->id === null) throw new Exception("Quote must be saved before detaching categories.");
        if ($category->id === null) throw new Exception("Category must be saved before detaching.");

        $sql = "DELETE FROM quote_category WHERE quote_id = ? AND category_id = ?";
        $result = static::$db->execute($sql, [$this->id, $category->id]);

        // Optionally update the local categories collection.
        $this->categories = array_filter($this->categories, fn($cat) => $cat->id !== $category->id);
        return $result;
    }

    /**
     * Syncs the categories for this quote.
     * Removes all existing category associations and attaches the provided ones.
     *
     * @param Category[] $categories
     * @return bool True if successful.
     * @throws Exception
     */
    public function syncCategories(array $categories): bool
    {
        if ($this->id === null) {
            throw new Exception("Quote must be saved before syncing categories.");
        }

        // Remove all existing associations.
        $sqlDelete = "DELETE FROM quote_category WHERE quote_id = ?";
        $result = static::$db->execute($sqlDelete, [$this->id]);
        if (!$result) {
            return false;
        }

        // Attach new categories.
        foreach ($categories as $category) {
            if (!$this->attachCategory($category)) {
                return false;
            }
        }

        // Update local collection.
        $this->categories = $categories;
        return true;
    }

    /**
     * Returns the Place associated with this quote.
     *
     * @return Place
     */
    public function getPlace(): Place
    {
        if ($this->place_id === null) return new Place();
        return Place::findOne($this->place_id);
    }

    /**
     * Returns the Drug associated with this quote.
     *
     * @return Drug
     */
    public function getDrug(): Drug
    {
        if ($this->drug_id === null) return new Drug();
        return Drug::findOne($this->drug_id);
    }

    /**
     * Returns the collection of sentences for this quote.
     *
     * @return Sentence[]
     */
    public function getSentences(): array
    {
        return Sentence::findAll(['quote_id' => $this->id]);
    }

    /**
     * Returns the collection of users who have contributed sentences to this quote.
     *
     * @return User[]
     */
    public function getUsers(): array
    {
        $sql = "SELECT u.* FROM user u
                INNER JOIN sentence s ON u.id = s.user_id
                WHERE s.quote_id = ?";
        $results = static::$db->query($sql, [$this->id]);
        return array_map(fn($row) => new User($row), $results);
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
