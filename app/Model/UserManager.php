<?php

declare(strict_types=1);

namespace App\Model;

use Nette;
use Nette\Security\Authenticator;
use Nette\Security\IIdentity;
use Nette\Security\SimpleIdentity;
use Nette\Database\Explorer;
use Nette\Security\Passwords;
use Nette\Security\AuthenticationException;

/**
 * Model for user management.
 * @package App/Model
 */
final class UserManager implements Authenticator
{
    use Nette\SmartObject;

    private const
        TABLE_NAME = 'users',
        COLUMN_ID = 'id',
        COLUMN_NAME = 'username',
        COLUMN_PASSWORD_HASH = 'password',
        COLUMN_EMAIL = 'email',
        COLUMN_ROLE = 'role';

    /**
     * Constructor with dependency injection of database and password service.
     * @param Explorer $database
     * @param Passwords $passwords
     */
    public function __construct(private Explorer $database, private Passwords $passwords)
    {
    }

    /**
     * Performs authentication of the user.
     * @param string $username Username.
     * @param string $password Password.
     * @return IIdentity Identity object of authenticated user.
     * @throws AuthenticationException If username or password is incorrect.
     */
    public function authenticate(string $username, string $password): IIdentity
    {
        $row = $this->database->table(self::TABLE_NAME)
            ->where(self::COLUMN_NAME, $username)
            ->fetch();

        if (!$row) {
            throw new AuthenticationException(
                'The username or password is incorrect, try again.',
                self::IDENTITY_NOT_FOUND
            );
        } elseif (!$this->passwords->verify($password, $row[self::COLUMN_PASSWORD_HASH])) {
            throw new AuthenticationException(
                'The username or password is incorrect, try again.',
                self::INVALID_CREDENTIAL
            );
        } elseif ($this->passwords->needsRehash($row[self::COLUMN_PASSWORD_HASH])) {
            $row->update([self::COLUMN_PASSWORD_HASH => $this->passwords->hash($password)]);
        }

        $arr = $row->toArray();
        unset($arr[self::COLUMN_PASSWORD_HASH]);
        return new SimpleIdentity($row[self::COLUMN_ID], $row[self::COLUMN_ROLE], $arr);
    }

    /**
     * Adds a new user to the database.
     * @param string $username New user's username.
     * @param string $email New user's email address.
     * @param string $password New user's password.
     * @return void
     * @throws DuplicateNameException If user with passed username is already present.
     * @throws Nette\Utils\AssertionException If email's format is malformed.
     */
    public function add(string $username, string $email, string $password): void
    {
        Nette\Utils\Validators::assert($email, 'email');
        try {
            $this->database->table(self::TABLE_NAME)->insert([
                self::COLUMN_NAME => $username,
                self::COLUMN_PASSWORD_HASH => $this->passwords->hash($password),
                self::COLUMN_EMAIL => $email,
            ]);
        } catch (Nette\Database\UniqueConstraintViolationException $e) {
            throw new DuplicateNameException;
        }
    }
}

/**
 * Exception for attempted registration of a new user whose name is already present in the database.
 * @package App/Model
 */
class DuplicateNameException extends \Exception
{
    protected $message = 'User with this username already exists.';
}
