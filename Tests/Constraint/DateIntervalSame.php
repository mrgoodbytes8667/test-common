<?php

namespace Bytes\Tests\Common\Constraint;

use DateInterval;
use Exception;
use InvalidArgumentException;
use PHPUnit\Framework\Constraint\Constraint;

/**
 * Class DateIntervalSame.
 */
class DateIntervalSame extends Constraint
{
    /**
     * @var DateInterval
     */
    private $interval;

    private readonly bool $skipDays;

    /**
     * DateIntervalSame constructor.
     *
     * @throws Exception
     */
    public function __construct($interval, bool $skipDays)
    {
        if (is_string($interval)) {
            $interval = new DateInterval($interval);
        } elseif (!($interval instanceof DateInterval)) {
            throw new InvalidArgumentException('Constructor requires a DateInterval or a DateInterval string spec.');
        }

        $this->interval = $interval;
        $this->skipDays = $skipDays;
    }

    /**
     * @param DateInterval $response
     */
    protected function matches($response): bool
    {
        return $this->interval->y === $response->y
            && $this->interval->m === $response->m
            && $this->interval->d === $response->d
            && $this->interval->h === $response->h
            && $this->interval->i === $response->i
            && $this->interval->s === $response->s
            && $this->interval->f === $response->f
            && $this->interval->invert === $response->invert
            && ($this->skipDays || $this->interval->days === $response->days);
    }

    /**
     * @param DateInterval $response
     */
    protected function failureDescription($response): string
    {
        return $this->toString();
    }

    public function toString(): string
    {
        return sprintf('interval is %s', $this->diffIntervalFormatted($this->interval));
    }

    /**
     * Takes the DateInterval and makes a pretty format based on years, months, and days.
     *
     * @return string
     *
     * @see https://www.php.net/manual/en/dateinterval.format.php#96768 Loosely based on a php.net comment
     */
    protected function diffIntervalFormatted(DateInterval $interval)
    {
        $format = [];
        if (0 !== $interval->y) {
            $format[] = '%y '.$this->pluralize($interval->y, 'year');
        }

        if (0 !== $interval->m) {
            $format[] = '%m '.$this->pluralize($interval->m, 'month');
        }

        if (0 !== $interval->d) {
            $format[] = '%d '.$this->pluralize($interval->d, 'day');
        }

        if (0 !== $interval->h) {
            $format[] = '%h '.$this->pluralize($interval->h, 'hour');
        }

        if (0 !== $interval->i) {
            $format[] = '%i '.$this->pluralize($interval->i, 'minute');
        }

        if (0 !== $interval->s) {
            if (!count($format)) {
                return 'less than a minute ago';
            } else {
                $format[] = '%s '.$this->pluralize($interval->s, 'second');
            }
        }

        // We use the two biggest parts
        if (count($format) > 1) {
            $format = array_shift($format).' and '.array_shift($format);
        } else {
            $format = array_pop($format);
        }

        return $interval->format($format);
    }

    /**
     * @return string
     */
    protected function pluralize(int $number, string $string)
    {
        return $number.(1 !== $number ? 's' : '');
    }
}
