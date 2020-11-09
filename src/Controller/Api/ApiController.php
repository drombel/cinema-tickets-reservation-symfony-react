<?php
namespace App\Controller\Api;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\ {
	JsonResponse,
	Request
};

class ApiController
{

	/**
	 * @var integer HTTP status code - 200 (OK) by default
	 */
	protected int $statusCode = 200;

	/**
	 * Gets the value of statusCode.
	 *
	 * @return integer
	 */
	public function getStatusCode(): int 
	{
		return $this->statusCode;
	}

	/**
	 * Sets the value of statusCode.
	 *
	 * @param integer $statusCode the status code
	 *
	 * @return self
	 */
	protected function setStatusCode(int $statusCode): self
	{
		$this->statusCode = $statusCode;

		return $this;
	}

	/**
	 * Returns a JSON response
	 *
	 * @param array $data
	 * @param array $headers
	 *
	 * @return JsonResponse
	 */
	public function respond(array $data, array $headers = []): JsonResponse
	{
		return new JsonResponse($data, $this->getStatusCode(), $headers);
	}

	/**
	 * Sets an error message and returns a JSON response
	 *
	 * @param string $errors
	 * @param array $headers
	 *
	 * @return JsonResponse
	 */
	public function respondWithErrors(string $errors, array $headers = []): JsonResponse
	{
		$data = [
			'errors' => $errors,
		];

		return new JsonResponse($data, $this->getStatusCode(), $headers);
	}

	/**
	 * Returns a 401 Unauthorized http response
	 *
	 * @param string $message
	 *
	 * @return JsonResponse
	 */
	public function respondUnauthorized(string $message = 'Not authorized!'): JsonResponse
	{
		return $this->setStatusCode(401)->respondWithErrors($message);
	}

	/**
	 * Returns a 422 Unprocessable Entity
	 *
	 * @param string $message
	 *
	 * @return JsonResponse
	 */
	public function respondValidationError(string $message = 'Validation errors'): JsonResponse
	{
		return $this->setStatusCode(422)->respondWithErrors($message);
	}

	/**
	 * Returns a 404 Not Found
	 *
	 * @param string $message
	 *
	 * @return JsonResponse
	 */
	public function respondNotFound(string $message = 'Not found'): JsonResponse
	{
		return $this->setStatusCode(404)->respondWithErrors($message);
	}

	/**
	 * Returns a 201 Created
	 *
	 * @param array $data
	 *
	 * @return JsonResponse
	 */
	public function respondCreated(array $data = []): JsonResponse
	{
		return $this->setStatusCode(201)->respond($data);
	}

	// this method allows us to accept JSON payloads in POST requests 

	protected function transformJsonBody(Request $request)
	{
		$data = json_decode($request->getContent(), true);

		if (json_last_error() !== JSON_ERROR_NONE) {
			return null;
		}

		if ($data === null) {
			return $request;
		}

		$request->request->replace($data);

		return $request;
	}


    const LIMIT_MAX = 50;
    const LIMIT_DEFAULT = 10;
    const PAGE_DEFAULT = 1;

    protected EntityManagerInterface $em;
	protected SerializerInterface $serializer;
	protected ServiceEntityRepository $repo;
	protected $className;

    /**
     * ApiController constructor.
     * @param EntityManagerInterface $em
     * @param SerializerInterface $serializer
     * @param string $className
     */
    public function __construct(EntityManagerInterface $em, SerializerInterface $serializer, $className = '')
    {
        $this->em = $em;
        $this->serializer = $serializer;
        $this->className = $className;
        $this->repo = $this->em->getRepository($className);
    }

    /**
     * @param Request $request
     * @return array
     */
    protected function getLimitAndPage(Request $request): array
    {
        $limit = (int)($request->query->get('limit') ?? $request->request->get('limit') ?? self::LIMIT_DEFAULT);
        $limit = min(max($limit, 1), self::LIMIT_MAX);
        $offset = (int)($request->query->get('page') ?? $request->request->get('page') ?? self::PAGE_DEFAULT);
        $offset = max($offset, 1);
        $offset = ($offset - 1) * $limit;
        return [$limit, $offset];
    }

    /**
     * @param Request $request
     * @return array
     */
    protected function getParams(Request $request): array
    {
        $fields = $this->em->getClassMetadata($this->className)->getColumnNames();
        $params = ($request->request->all() != [] ? $request->request->all() : $request->query->all());
        $params = array_filter($params, function ($key) use ($fields) {
            return \in_array($key, $fields);
        }, ARRAY_FILTER_USE_KEY);

        return $params;
    }

    /**
     * @param Request $request
     * @return array|null
     */
    protected function getSort(Request $request): ?array
    {
        $fields = $this->em->getClassMetadata($this->className)->getColumnNames();
        $sort = $request->query->get('sort') ?? $request->request->get('sort') ?? null;
        if ($sort){
            $sort = array_filter($sort, function ($value, $key) use ($fields) {
                return \in_array($key, $fields) && \in_array(strtolower($value), ['desc', 'asc']);
            }, ARRAY_FILTER_USE_BOTH);
        }else{
            $sort = \in_array('createdAt', $fields) ? ['createdAt' => 'DESC'] : null;
        }

        return $sort;
    }

    /**
     * @param Request $request
     * @return array
     */
    protected function getData(Request $request): array
    {
        return $this->repo->findBy(
            $this->getParams($request),
            $this->getSort($request),
            ...$this->getLimitAndPage($request)
        );
    }

    protected function serialize($data)
    {
        if (is_array($data)){
            return array_map(function ($object) {
                return $this->serializer->toArray($object, SerializationContext::create()->enableMaxDepthChecks());
            }, $data);
        }else{
            return $this->serializer->toArray($data, SerializationContext::create()->enableMaxDepthChecks());
        }
    }
}