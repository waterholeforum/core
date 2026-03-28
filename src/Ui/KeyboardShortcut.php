<?php

namespace Waterhole\Ui;

class KeyboardShortcut
{
    public function __construct(
        public string $id,
        public array $keys,
        public string $description,
        public string $category = 'navigation',
        public array $scopes = [],
    ) {}

    public function toPayload(): array
    {
        return array_filter(
            [
                'id' => $this->id,
                'keys' => $this->keys,
                'scopes' => $this->scopes,
            ],
            fn($value) => $value !== null && $value !== [],
        );
    }
}
