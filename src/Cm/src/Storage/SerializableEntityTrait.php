<?php

declare(strict_types=1);

namespace Cm\Storage;

trait SerializableEntityTrait
{
    /**
     * Magic method used for serializing of an instance.
     *
     * @return array<string, mixed>
     */
    public function __serialize(): array
    {
        if ($this->offsetExists('data')) {
            return $this->offsetGet('data');
        }
        return [];
    }

    public function __unserialize(array $data): void
    {
        $this->offsetSet('data', $data);
    }
}
