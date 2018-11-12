<?php
/**
 * Created by PhpStorm.
 * User: naimo
 * Date: 11/12/18
 * Time: 10:27 AM
 */

namespace App\Service;


use Carbon\Carbon;
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
	 * Get the current date-time for a given format
	 *
	 * @param string|null $format
	 *
	 * @return mixed
	 */
	public function getCurrent( string $format )
	{
		return ($format !== "default") ?
			Carbon::now()->{'to'.$format.'String'}() :
			Carbon::now()->format( self::FORMAT) ;

	}

}