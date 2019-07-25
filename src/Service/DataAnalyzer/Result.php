<?php


namespace App\Service\DataAnalyzer;


class Result
{

    /**
     * @var \DateTime
     */
    private $dateStart = null;

    /**
     * @var \DateTime
     */
    private $dateEnd = null;

    /**
     * @var float
     */
    private $min = 0.0;

    /**
     * @var float
     */
    private $max = 0.0;

    /**
     * @var float
     */
    private $avg = 0.0;

    /**
     * @var float
     */
    private $med = 0.0;

    /**
     * @var array
     */
    private $underPerformant = [];

    /**
     * Result constructor.
     * @param \DateTime $dateStart
     * @param \DateTime $dateEnd
     * @param float $min
     * @param float $max
     * @param float $avg
     * @param float $med
     * @param array $underPerformant
     */
    public function __construct(
        \DateTime $dateStart,
        \DateTime $dateEnd,
        float $min,
        float $max,
        float $avg,
        float $med,
        array $underPerformant
    ) {
        $this->dateStart = $dateStart;
        $this->dateEnd = $dateEnd;
        $this->min = $min;
        $this->max = $max;
        $this->avg = $avg;
        $this->med = $med;
        $this->underPerformant = $underPerformant;
    }

    /**
     * @return \DateTime
     */
    public function getDateStart(): \DateTime
    {
        return $this->dateStart;
    }

    /**
     * @return \DateTime
     */
    public function getDateEnd(): \DateTime
    {
        return $this->dateEnd;
    }

    /**
     * @return float
     */
    public function getMin(): float
    {
        return $this->min;
    }

    /**
     * @return float
     */
    public function getMax(): float
    {
        return $this->max;
    }

    /**
     * @return float
     */
    public function getAvg(): float
    {
        return $this->avg;
    }

    /**
     * @return float
     */
    public function getMedian(): float
    {
        return $this->med;
    }

    /**
     * @return array
     */
    public function getUnderPerformant(): array
    {
        return $this->underPerformant;
    }
}
