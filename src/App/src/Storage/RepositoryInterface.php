<?php

declare(strict_types=1);

namespace App\Storage;

use Laminas\Config\Factory;
use Laminas\Config\Writer\WriterInterface;
use Laminas\Config\Writer\PhpArray;

interface RepositoryInterface
{
    public const STORAGE_PATH = __DIR__ . '/../../../../data/storage/';

    public function setWriter(WriterInterface $writer): void;
    public function getWriter(): WriterInterface;
    public function loadFiles(string $path = self::STORAGE_PATH): array;
    public function loadTemplateData(string $template): array;
    public function save(EntityInterface|array $data): bool;
}
