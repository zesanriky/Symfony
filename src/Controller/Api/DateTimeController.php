<?php

namespace App\Controller\Api;

use App\Service\DateTimeService;
use Carbon\Carbon;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Psr\Log\LoggerInterface;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Profiler\Profiler;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Exception\ValidatorException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Rest\Route("/api/v1")
 */
class DateTimeController extends FOSRestController
{

	/**
	 * @var ValidatorInterface
	 */
	private $validator;

	public function __construct(ValidatorInterface $validator)
	{

		$this->validator = $validator;
	}

	/**
	 * @Rest\Get("/current/{format}", name="api.current.date_time",defaults={"format"="l jS \\of F Y h:i:s A"})
	 *
	 * @SWG\Tag(name="Current DateTime")
	 *
	 * @SWG\Response(
	 *     response="200",
	 *     description="Returns the current datetime with a given format, if no format was provided the default one will be rendered",
	 *     examples={
	 *          "application/json": {
	 *              "current": "Monday 12th of November 2018 12:01:40 PM",
	 *              "format": "default"
	 *          }
	 *     },
	 * ),
	 *
	 * @SWG\Response(
	 *     response="400",
	 *     description="Http Bad request response thrown when the format provided don't match one of the common formats",
	 *     examples={
	 *          "application/json": {
	 *              "code": 400,
	 *              "message": " You have to choose one of the formats provided, NOTE: case are sensitive"
	 *          }
	 *     },
	 * )
	 *
	 * @SWG\Parameter(name="format",in="path",
	 *     enum={
	 *     "default","Atom","Cookie","Iso8601",
	 *     "ISO","Iso8601Zulu","DateTimeLocal",
	 *     "Rfc822","Rfc850","Rfc1036","Rfc1123",
	 *     "Rfc2822","Rfc3339","Rfc7231","Rss","Rss","W3c"},
	 *     type="string",default="default", description="The datetime format desired, default format is **`l jS \\of F Y h:i:s A`**")
	 *
	 *
	 *
	 * @param Request $request
	 *
	 * @param DateTimeService $date_time_service
	 *
	 * @return View
	 */
    public function current(Request $request, DateTimeService $date_time_service): View
    {
    	$format = trim( $request->get( 'format' ));

	    $this->validateFormat( $format);

        return View::create([
        	"current" => $date_time_service->getCurrent($format),
	        "format" => $format
        ],Response::HTTP_OK);
    }

	/**
	 * @Rest\Post("/current/{format}/log", name="api.current.log.date_time",defaults={"format"="Y-m-d H:i:s"})
	 * @SWG\Tag(name="Current DateTime")
	 *
	 * @SWG\Response(
	 *     response="201",
	 *     description="JSON response with 'logged' property set to true if the datetime logged successfully",
	 *     examples={
	 *          "application/json": {
	 *              "logged": true,
	 *          }
	 *     },
	 * ),
	 *
	 * @SWG\Response(
	 *     response="400",
	 *     description="Http Bad request response thrown when the format provided don't match one of the common formats, or datetime arg is invalid",
	 *     examples={
	 *          "application/json": {
	 *              "code": 400,
	 *              "message": "You have to choose one of the formats provided. NOTE: case are sensitive"
	 *          }
	 *     },
	 * )
	 *
	 * @SWG\Parameter(name="format",in="path",
	 *     enum={
	 *     "default","Atom","Cookie","Iso8601",
	 *     "ISO","Iso8601Zulu","DateTimeLocal",
	 *     "Rfc822","Rfc850","Rfc1036","Rfc1123",
	 *     "Rfc2822","Rfc3339","Rfc7231","Rss","W3c"},
	 *     type="string",default="default", description="The datetime format desired, default format is **`Y-m-d H:i:s`**."
	 * )
	 *
	 * @SWG\Parameter(name="datetime",in="body",
	 *     @SWG\Schema(
	 *          type="object",
	 *          @SWG\Property(property="datetime", type="string")
	 *     ), description="The current datetime in **`Y-m-d H:i:s`** format."
	 * )
	 *
	 *
	 *
	 * @param Request $request
	 *
	 * @param DateTimeService $date_time_service
	 *
	 * @return View
	 */
    public function log(Request $request, DateTimeService $date_time_service): View
    {

    	$format = trim( $request->get( 'format' ));
    	$datetime = trim( $request->get( 'datetime' ));

	    $this->validateFormat( $format)->validateDateTime($datetime);

	    $date_time_service->log($format, $datetime);

        return View::create([
        	"logged" => true
        ],Response::HTTP_CREATED)->setHeader( 'Symfony-Debug-Toolbar-Replace', 1);
    }


	/**
	 * @param string $format
	 *
	 * @return DateTimeController
	 */
	private function validateFormat(string $format)
	{
		$constraint = new Choice([
			'choices' => DateTimeService::COMMON_FORMATS,
			'message' => "You have to choose one of the formats provided. NOTE: case are sensitive"
		]);

		return $this->validate($format, $constraint);
	}

	/**
	 * @param string $datetime
	 *
	 * @return DateTimeController
	 */
	private function validateDateTime(string $datetime)
	{
		return $this->validate($datetime, new DateTime());
	}

	/**
	 * @param $field
	 * @param $constraint
	 *
	 * @return $this
	 */
	private function validate( $field, $constraint)
	{
		$errors = $this->validator->validate( $field, $constraint );

		if ( count( $errors ) > 0 ) {

			throw new ValidatorException( $errors, Response::HTTP_BAD_REQUEST );

		}

		return $this;
	}
}
