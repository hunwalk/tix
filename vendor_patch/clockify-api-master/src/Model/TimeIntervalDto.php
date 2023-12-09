<?php

declare(strict_types=1);

namespace JDecool\Clockify\Model;

use DateTimeImmutable;

class TimeIntervalDto
{
    private $duration;
    private $end;
    private $start;

    public static function fromArray(array $data): self
    {
        return new self(
            $data['start'] ? new DateTimeImmutable($data['start']) : null,
            $data['end'] ? new DateTimeImmutable($data['end']) : null,
            $data['duration']
        );
    }

    public function __construct(
        $start,
        $end,
        $duration
    )
    {
        $this->duration = $duration;
        $this->start = $start;
        $this->end = $end;
    }

    public function duration(): string
    {
        return $this->duration;
    }

    public function end()
    {
        return $this->end;
    }

    public function start()
    {
        return $this->start;
    }
}
