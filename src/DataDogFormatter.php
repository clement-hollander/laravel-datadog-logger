<?php

namespace Myli\DatadogLogger;

use DateTime;
use Monolog\Formatter\JsonFormatter;

/**
 * Class DataDogFormatter
 *
 * @package   Myli\DatadogLogger
 * @author    Aurélien SCHILTZ <aurelien@myli.io>
 * @copyright 2016-2019 Myli
 * @license   http://www.opensource.org/licenses/mit-license.html  MIT License
 */
class DataDogFormatter extends JsonFormatter
{

    const LARAVEL_LOG_DATETIME_KEY = 'datetime';

    /**
     * Appends every variable needed by DataDog
     *
     * @TODO Handle trace_id for projects linked with DataDog APM
     *
     * @param array $record
     *
     * @return string
     * @see  \Monolog\Formatter\JsonFormatter::format()
     * @see  https://docs.datadoghq.com/logs/processing/#reserved-attributes
     */
    public function format(array $record)
    {
        if (isset($record[self::LARAVEL_LOG_DATETIME_KEY]) &&
            ($record[self::LARAVEL_LOG_DATETIME_KEY] instanceof DateTime)) {
            /**
             * @var DateTime $dateTimeObj
             */
            $dateTimeObj              = $record[self::LARAVEL_LOG_DATETIME_KEY];
            $record['published_date'] = $dateTimeObj->format(DateTime::ISO8601);
        }

        $record['application'] = 'connectors';
        $record['channel']     = 'connectors';
        $record['source']      = 'application';
        $record['service']     = 'application';
        $record['hostname']    = gethostname();

        return parent::format($record);
    }
}
