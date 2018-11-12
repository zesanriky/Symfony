<?php

namespace App\Controller\Api;

use App\Service\DateTimeService;
use Carbon\Carbon;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Exception\ValidatorException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Rest\Route("/api/v1")
 */
class DateTimeController extends FOSRestController
{
	/**
	 * @Rest\Get("/current/{format}", name="api.current.date_time",defaults={"format"="l jS \\of F Y h:i:s A"})
	 *
	 * @SWG\Tag(name="Current DateTime")
	 *
	 * @SWG\Response(
	 *     response="200",
	 *     description="Returns the current date-time with a given format, if no format was provided the default one will be rendered",
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
	 *              "message": "The format provided is invalid, please make sure to provide the correct format, NOTE: case are sensitive"
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
	 *     type="string",default="default", description="The date-time format desired, default format is   `l jS \\of F Y h:i:s A`")
	 *
	 *
	 *
	 * @param Request $request
	 *
	 * @param DateTimeService $date_time_service
	 * @param ValidatorInterface $validator
	 *
	 * @return View
	 */
    public function current(Request $request, DateTimeService $date_time_service, ValidatorInterface $validator): View
    {
    	$format = trim( $request->get( 'format' ));

	    $this->requestValidation( $format, $validator );

        return View::create([
        	"current" => $date_time_service->getCurrent($format),
	        "format" => $format
        ],Response::HTTP_OK);
    }

	/**
	 * Validate the incoming Request format
	 *
	 * @param string $format
	 * @param ValidatorInterface $validator
	 *
	 */
	private function requestValidation( string $format, ValidatorInterface $validator ): void
	{

		$format_constraints = new Choice( DateTimeService::COMMON_FORMATS );

		$format_constraints->message = 'The format provided is invalid, please make sure to provide the correct format, NOTE: case are sensitive';

		$errors = $validator->validate( $format, $format_constraints );

		if ( count( $errors ) > 0 ) {

			throw new ValidatorException( $errors[0]->getMessage(), Response::HTTP_BAD_REQUEST );

		}
	}
}
