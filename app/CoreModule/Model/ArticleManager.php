<?php

declare(strict_types=1);

namespace App\CoreModule\Model;

use App\Model\DatabaseManager;
use Nette\Database\Table\ActiveRow;
use Nette\Database\Table\Selection;
use Nette\Utils\ArrayHash;

/**
 * Model for article management.
 * @package App\CoreModule\Model
 */
class ArticleManager extends DatabaseManager
{
    /** Constants for database access. */
    const
        TABLE_NAME = 'article',
        COLUMN_ID = 'article_id',
        COLUMN_URL = 'url';

    /**
     * Retrieves all articles ordered by their id.
     * @return Selection List of all articles.
     */
    public function getArticles(): Selection
    {
        return $this->database
            ->table(self::TABLE_NAME)
            ->order(self::COLUMN_ID . ' DESC');
    }

    /**
     * Retrieves an article by its url.
     * @param string $url URL address of an article.
     * @return ActiveRow|null First article found or false if article with given URL does not exist.
     */
    public function getArticle(string $url): ActiveRow|null
    {
        return $this->database
            ->table(self::TABLE_NAME)
            ->where(self::COLUMN_URL, $url)
            ->fetch();
    }

    /**
     * Inserts an article if its id is not set, otherwise updates an article with given ID.
     * @param array|ArrayHash $article Article properties (values).
     * @return void
     */
    public function saveArticle(array|ArrayHash $article): void
    {
        if (empty($article[self::COLUMN_ID])) {
            unset($article[self::COLUMN_ID]);
            $this->database
                ->table(self::TABLE_NAME)
                ->insert($article);
        } else {
            $this->database
                ->table(self::TABLE_NAME)
                ->where(self::COLUMN_ID, $article[self::COLUMN_ID])
                ->update($article);
        }
    }

    /**
     * Removes an article with given URL.
     * @param string $url URL address.
     * @return void
     */
    public function removeArticle(string $url): void
    {
        $this->database
            ->table(self::TABLE_NAME)
            ->where(self::COLUMN_URL, $url)
            ->delete();
    }
}