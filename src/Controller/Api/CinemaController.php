<?php
namespace App\Controller\Api;

use App\Entity\Cinema;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Movie;

/**
 * @Route("/api/cinemas")
 */
class CinemaController extends ApiController
{
    public function __construct(EntityManagerInterface $em, SerializerInterface $serializer)
    {
        parent::__construct($em, $serializer, Cinema::class);
    }

    /**
     * @Route("/", methods="GET", name="api.cinema.find.all")
     * @param Request $request
     * @return JsonResponse
     */
	public function index(Request $request)
	{
        $data = $this->serialize($this->getData($request));
        $response = $this->respond($data)
            ->setEtag("Movies")
            ->setPublic()
            ->setSharedMaxAge(1800)
        ;
        $response->isNotModified($request);
        return $response;
    }

    /**
     * @Route("/{id<\d+>}", methods="GET", name="api.cinema.find.id")
     * @Route("/{slug<\S+>}", methods="GET", name="api.cinema.find.slug")
     * @param Request $request
     * @param Cinema $object
     * @return JsonResponse
     */
    public function find(Request $request, Cinema $object = null)
    {
        if(!$object)
            return $this->respondNotFound("Cinema do not exists.");

        $data = $this->serialize($object);
        $response =  $this->respond($data)
            ->setEtag("Cinema ~".$object->getId()." ~ ".$object->getUpdatedAt()->format("Y-m-d_H:i:s"))
            ->setLastModified($object->getUpdatedAt())
            ->setSharedMaxAge(1800)
            ->setPublic()
        ;
        $response->isNotModified($request);
        return $response;

    }

}