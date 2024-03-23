<?php

declare(strict_types=1);

namespace App\Storage;

use Laminas\Config\Writer\WriterInterface;

final class AbstractRepository implements RepositoryInterface
{
    protected ?string $name;
    protected ?string $path;

    public function setName(?string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setPath(?string $path): self
    {
        $this->path = $path;
        return $this;
    }
    public function setWriter(WriterInterface $writer): void { }

    public function getWriter(): WriterInterface { }

    public function loadFiles(string $path = self::STORAGE_PATH): array { }

    public function loadTemplateData(string $template): array { }

    public function save(array $data): bool { }

}
