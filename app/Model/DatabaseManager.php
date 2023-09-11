<?php

declare(strict_types=1);

namespace App\Model;

use Nette\Database\Explorer;
use Nette\SmartObject;

/**
 * Base model for all other database models.
 * Provides access to the database.
 * @package App\Model
 */
class DatabaseManager
{
    use SmartObject;

    /**
     * Constructor with dependency injection.
     * @param Explorer $database Injected service
     */
    public function __construct(protected Explorer $database)
    {
    }
}