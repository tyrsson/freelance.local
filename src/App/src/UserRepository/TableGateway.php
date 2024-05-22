<?php

declare(strict_types=1);

namespace App\UserRepository;

use Axleus\Db;
use Laminas\Hydrator\ReflectionHydrator;
use Mezzio\Authentication\Exception;
use Mezzio\Authentication\UserInterface;
use Mezzio\Authentication\UserRepositoryInterface;
use Webmozart\Assert\Assert;

use function password_verify;

final class TableGateway extends Db\AbstractRepository implements UserRepositoryInterface
{
    /**
     * @var callable
     * @psalm-var callable(string, array<int|string, string>, array<string, mixed>): UserInterface
     */
    private $userFactory;

    public function __construct(
        protected Db\TableGateway $gateway,
        callable $userFactory,
        $hydrator = new ReflectionHydrator(),
    ) {
        parent::__construct($gateway, $hydrator);
        // Provide type safety for the composed user factory.
        $this->userFactory = static function (
            string $identity,
            array $roles = [],
            array $details = []
        ) use ($userFactory): UserInterface {
            Assert::allString($roles);
            Assert::isMap($details);

            return $userFactory($identity, $roles, $details);
        };
    }

    public function authenticate(string $credential, ?string $password = null): ?UserInterface
    {
        $user = $this->findOneByUsername($credential);
        $hash = $user->getHash();

        $this->checkBcryptHash($hash);
        if (password_verify($password, $hash)) {
            return ($this->userFactory)($credential, ['Administrator'], ['identity' => $credential]);
        }
        return null;
    }

    /**
     * Check bcrypt usage for security reason
     *
     * @throws Exception\RuntimeException
     */
    protected function checkBcryptHash(string $hash): void
    {
        if (0 !== strpos($hash, '$2y$')) {
            throw new Exception\RuntimeException(
                'The provided hash has not been created with a supported algorithm. Please use bcrypt.'
            );
        }
    }
}
