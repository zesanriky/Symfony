<?php
/**
 * Created by PhpStorm.
 * User: naimo
 * Date: 11/12/18
 * Time: 10:27 AM
 */

namespace App\Service;


use Carbon\Carbon;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\OptionsResolver\Exception\InvalidArgumentException;
use Symfony\Component\Validator\Exception\ValidatorException;

class DateTimeService {

	const FORMAT = "l jS \\of F Y h:i:s A";

	const COMMON_FORMATS = [
			"default",
	        "Atom",
	        "Cookie",
	        "Iso8601",
	        "ISO",
	        "Iso8601Zulu",
	        "DateTimeLocal",
	        "Rfc822",
	        "Rfc850",
	        "Rfc1036",
	        "Rfc1123",
	        "Rfc2822",
	        "Rfc3339",
	        "Rfc7231",
	        "Rss",
	        "Rss",
	        "W3c",
    ];
	/**
	 * @var LoggerInterface
	 */
	private $logger;
	/**
	 * @var ContainerInterface
	 */
	private $container;

	public function __construct(LoggerInterface $logger,ContainerInterface $container)
	{
		$this->logger = $logger;

		$this->container = $container;
	}


	/**
	 * Get the current date-time for a given format
	 *
	 * @param string|null $format
	 *
	 * @return mixed
	 */
	public function getCurrent( string $format )
	{
		return $this->getDateWithGivenFormat( Carbon::now(), $format);

	}

	public function log( $format, $datetime )
	{
		$this->logger->info(
			$this->getDateWithGivenFormat(
				Carbon::parse( $datetime),
				$format
			)
		);
	}

	private function getDateWithGivenFormat( Carbon $date, $format ): string
	{
		return ($format !== "default") ?
			$date->{'to'.$format.'String'}() :
			$date->format( self::FORMAT) ;
	}

}