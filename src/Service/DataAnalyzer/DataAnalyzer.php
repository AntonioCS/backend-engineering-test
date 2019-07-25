<?php


namespace App\Service\DataAnalyzer;


class DataAnalyzer
{

    /**
     * @var array
     */
    private $metricData = null;

    /**
     * @var int
     */
    private static $CONVERSION_BYTES_TO_MEGABITS_NUM = 131072;

    /**
     * @var int
     */
    private static $PERCENTAGE_BELOW_AVERAGE = 15;

    public function loadFileData(string $filePath) : void
    {
        $data = json_decode(file_get_contents($filePath), true);

        if ($data && isset($data['data'][0]['metricData'])) {
            $this->beenProcessed = false;
            $this->metricData = $data['data'][0]['metricData'];
        } else {
            throw new \LogicException('Invalid data file');
        }
    }

    public function processData() : Result
    {
        $firstValue = $this->bytesToMegabits($this->metricData[0]['metricValue']);
        $min = $firstValue;
        $max = $firstValue;
        $sumTotal = 0;
        $valuesOnly = [];

        foreach ($this->metricData as $data) {
            $value = $this->bytesToMegabits($data['metricValue']);

            if ($value < $min) {
                $min = $value;
            }
            elseif ($value > $max) {
                $max = $value;
            }

            $sumTotal += $value;
            $valuesOnly[] = $value;
        }

        $dateStart = $this->metricData[0]['dtime'];
        $dateEnd = $this->metricData[count($this->metricData)-1]['dtime'];
        $min = round($min, 2);
        $max = round($max, 2);
        $avg = round($sumTotal/count($this->metricData), 2);
        $median = round($this->calculateMedian($valuesOnly), 2);
        $under = $this->checkForUnderPerformance($avg);

        return new Result(new \DateTime($dateStart), new \DateTime($dateEnd), $min, $max, $avg, $median, $under);
    }

    private function checkForUnderPerformance(float $avg) : array
    {
        $res = [];
        $dateStart = null;
        $in = false;
        $badValue = $avg - (($avg*self::$PERCENTAGE_BELOW_AVERAGE)/100);

        foreach ($this->metricData as $k => $data) {
            $value = $this->bytesToMegabits($data['metricValue']);

            if ($in) {
                if ($value >= $badValue) {
                    $res[] = [
                        'start' => $dateStart,
                        'end' => $this->metricData[$k-1]['dtime']
                    ];
                    $in = false;
                }
            } else {
                if ($value < $badValue) {
                    $in = true;
                    $dateStart = $data['dtime'];
                }
            }
        }

        return $res;
    }

    private function calculateMedian(array $values) : float
    {
        $total = count($values);
        $mid = $total/2;

        if ($this->isOdd($total)) {
            return $values[$mid];
        } else {
            return ($values[$mid] + $values[$mid+1]) / 2;
        }
    }

    private function isOdd(float $value) : bool
    {
        return (bool)($value & 1);
    }

    private function bytesToMegabits(float $value) : float
    {
        return $value / self::$CONVERSION_BYTES_TO_MEGABITS_NUM;
    }
}
