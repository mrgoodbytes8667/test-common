<?php


namespace Bytes\Tests\Common\Constraint;


use DateInterval;
use Exception;
use Illuminate\Support\Str;
use InvalidArgumentException;
use PHPUnit\Framework\Constraint\Constraint;

/**
 * Class DateIntervalSame
 * @package Bytes\Tests\Common\Constraint
 */
class DateIntervalSame extends Constraint
{
    /**
     * @var DateInterval
     */
    private $interval;

    /**
     * @var bool
     */
    private bool $skipDays;

    /**
     * DateIntervalSame constructor.
     * @param $interval
     * @param bool $skipDays
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
     *
     * {@inheritdoc}
     */
    protected function matches($response): bool
    {
        return $this->interval->y === $response->y &&
            $this->interval->m === $response->m &&
            $this->interval->d === $response->d &&
            $this->interval->h === $response->h &&
            $this->interval->i === $response->i &&
            $this->interval->s === $response->s &&
            $this->interval->f === $response->f &&
            $this->interval->invert === $response->invert &&
            ($this->skipDays || $this->interval->days === $response->days);
    }

    /**
     * @param DateInterval $response
     *
     * {@inheritdoc}
     */
    protected function failureDescription($response): string
    {
        return $this->toString();
    }

    /**
     * @inheritDoc
     */
    public function toString(): string
    {
        return sprintf('interval is %s', $this->diffIntervalFormatted($this->interval));
    }

    /**
     * Takes the DateInterval and makes a pretty format based on years, months, and days
     *
     * @param DateInterval $interval
     * @return string
     *
     * @link https://www.php.net/manual/en/dateinterval.format.php#96768 Loosely based on a php.net comment
     */
    protected function diffIntervalFormatted(DateInterval $interval)
    {
        $format = array();
        if ($interval->y !== 0) {
            $format[] = '%y ' . $this->pluralize($interval->y, 'year');
        }
        if ($interval->m !== 0) {
            $format[] = '%m ' . $this->pluralize($interval->m, 'month');
        }
        if ($interval->d !== 0) {
            $format[] = '%d ' . $this->pluralize($interval->d, 'day');
        }
        if ($interval->h !== 0) {
            $format[] = '%h ' . $this->pluralize($interval->h, 'hour');
        }
        if ($interval->i !== 0) {
            $format[] = '%i ' . $this->pluralize($interval->i, 'minute');
        }
        if ($interval->s !== 0) {
            if (!count($format)) {
                return 'less than a minute ago';
            } else {
                $format[] = '%s ' . $this->pluralize($interval->s, 'second');
            }
        }

        // We use the two biggest parts
        if (count($format) > 1) {
            $format = array_shift($format) . ' and ' . array_shift($format);
        } else {
            $format = array_pop($format);
        }

        return $interval->format($format);
    }

    /**
     * @param int $number
     * @param string $string
     * @return string
     */
    protected function pluralize(int $number, string $string)
    {
        return $number === 1 ? Str::singular($string) : Str::plural($string);
    }
}