# Romain la merde (V2)

## 🚀 Quickstart

PHP.

## 📦 Config

- Fill the `config.php` file.
- Config the `RewriteBase` in the `.htaccess` file.

## ⌨️ Code

### Models

Usage example:

```php
// --- Creating a new Quote ---
$quote = new Quote([
    // ...
    'created_at' => date('Y-m-d H:i:s')
]);
$quote->save();  // Inserts a new record and sets $quote->id

// --- Retrieving a Quote by ID ---
$quote = Quote::findOne($quote->id);
var_dump($quote);
```