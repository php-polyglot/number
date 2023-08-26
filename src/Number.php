<?php

declare(strict_types=1);

namespace Polyglot\Number;

final class Number
{
    /** @var float|int */
    private $number;
    private int $integer;
    private int $fraction;
    private int $fractionDigits;
    private int $fractionShort;
    private int $fractionShortDigits;
    private int $exponent;
    private ?string $raw;

    public static function create($value): self
    {
        if ($value instanceof self) {
            return $value;
        }

        if (is_object($value) && method_exists($value, '__toString')) {
            $value = (string)$value;
        }

        if (is_countable($value)) {
            $value = count($value);
        }

        if (is_bool($value)) {
            $value = $value ? 1 : 0;
        }

        if (is_int($value)) {
            return self::fromInt($value);
        }

        if (is_float($value)) {
            return self::fromFloat($value);
        }

        if (is_string($value)) {
            return self::fromString($value);
        }

        return self::fromInt(0);
    }

    public static function fromInt(int $value): self
    {
        return new self($value, 0, 0);
    }

    public static function fromString(string $value): self
    {
        preg_match('#^(?<integer>-?\d*)(\.(?<fraction>\d+))?([ec](?<exponent>\d+))?$#ui', $value, $matches);
        if (empty($matches)) {
            return new self(0, 0, 0);
        }
        $integer = $matches['integer'];
        $fraction = $matches['fraction'] ?? '';
        $exponent = $matches['exponent'] ?? 0;

        return new self((int)$integer, (int)$fraction, strlen($fraction), (int)$exponent);
    }

    public static function fromFloat(float $value): self
    {
        $string = (string)$value;
        if ($value == (int)$value && strpos($string, '.') === false) {
            $string = sprintf('%s.0', $string);
        }
        return self::fromString($string);
    }

    private function __construct(int $integer, int $fraction, int $fractionDigits, int $exponent = 0)
    {
        $raw = (string)$integer;
        if ($fraction > 0) {
            $raw = sprintf('%s.%d', $raw, $fraction);
        }

        if ($exponent !== 0) {
            $raw = sprintf('%sc%d', $raw, $exponent);
            $factor = pow(10, $exponent);
            if ($fractionDigits === 0) {
                $fractionIntPart = 0;
            } else {
                $fractionString = str_pad((string)$fraction, $fractionDigits, '0', STR_PAD_LEFT);
                if ($fractionDigits < $exponent) {
                    $fractionString .= str_repeat('0', $exponent - $fractionDigits);
                }
                $fractionIntPart = (int)substr($fractionString, 0, $exponent);
                $fractionString = substr($fractionString, $exponent);
                $fraction = (int)$fractionString;
                $fractionDigits = strlen($fractionString);
            }
            $integer = $integer * $factor + $fractionIntPart;
        }

        $this->raw = $raw;
        $this->exponent = $exponent;

        $this->integer = $integer;
        $this->fraction = $fraction;
        $this->fractionDigits = $fractionDigits;

        if ($fractionDigits === 0) {
            $this->fractionShort = 0;
            $this->fractionShortDigits = 0;
            $this->number = $this->integer;
        } else {
            $fractionString = str_pad((string)$fraction, $fractionDigits, '0', STR_PAD_LEFT);
            $fractionShortString = rtrim($fractionString, '0');
            $this->fractionShort = (int)$fractionShortString;
            $this->fractionShortDigits = strlen($fractionShortString);
            $this->number = (float)$this->integer + $this->fractionShort / pow(10, $this->fractionShortDigits);
        }
    }

    /**
     * @return float|int
     */
    public function number()
    {
        return $this->number;
    }

    public function integer(): int
    {
        return $this->integer;
    }

    public function fraction(bool $full = true): int
    {
        if ($full) {
            return $this->fraction;
        }
        return $this->fractionShort;
    }

    public function fractionDigits(bool $full = true): int
    {
        if ($full) {
            return $this->fractionDigits;
        }
        return $this->fractionShortDigits;
    }

    public function exponent(): int
    {
        return $this->exponent;
    }

    public function __toString(): string
    {
        return $this->raw;
    }
}
