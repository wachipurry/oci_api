<?php

namespace App\Controller;

use App\Repository\PeliculasRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PelicuasController
 * @package App\Controller
 *
 * @Route(path="/api/")
 */
class CinemaController
{
    private $peliculasRepository;

    public function __construct(peliculasRepository $peliculasRepository)
    {
        $this->peliculasRepository = $peliculasRepository;
    }

    /**
     * @Route("pelicula", name="add_pelicula",methods={"POST"})
     */
    public function add(Request $request): JsonResponse
    {
        $voidResponse = "";
        $data = json_decode($request->getContent(), true);
        $nombre = $data['nombre'];
        $genero = $data['genero'];
        $descripcion = $data['descripcion'];
        if (empty($nombre)) {
            $voidResponse . " Falta el nombre de la pelicula. ";
        }
        if (empty($genero)) {
            $voidResponse . " Falta el genero de la pelicula. ";
        }
        if (empty($descripcion)) {
            $voidResponse . " Falta la descripcion de la pelicula. ";
        }
        if ($voidResponse != "") {
            throw new NotFoundHttpException($voidResponse);
        }

        $this->peliculasRepository->savePelicula($nombre, $genero, $descripcion);
        return new JsonResponse(['status' => 'Pelicula añadida'], Response::HTTP_CREATED);
    }

    /**
     * @Route("pelicula/{id}", name="get_pelicula", methods={"GET"})
     */
    public function get($id): JsonResponse
    {
        $pelicula = $this->peliculasRepository->findOneBy(['id' => $id]);

        $data = [
            'id' => $pelicula->getId(),
            'nombre' => $pelicula->getNombre(),
            'genero' => $pelicula->getGenero(),
            'descripcion' => $pelicula->getDescripcion(),
        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }


    /**
     * @Route("peliculas", name="get_peliculas", methods={"GET"})
     */
    public function getAll(): JsonResponse
    {
        $data = [];
        $peliculas = $this->peliculasRepository->findAll();

        foreach ($peliculas as $pelicula) {
            $data[] = [
                'id' => $pelicula->getId(),
                'nombre' => $pelicula->getNombre(),
                'genero' => $pelicula->getGenero(),
                'descripcion' => $pelicula->getDescripcion(),
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }
    /**
     * @Route("pelicula/{id}", name="update_pelicula", methods={"PUT"})
     */
    public function update($id, Request $request): JsonResponse
    {
        $pelicula = $this->peliculasRepository->findOneBy(['id' => $id]);
        $data = json_decode($request->getContent(), true);

        empty($data['nombre']) ? true : $pelicula->setNombre($data['nombre']);
        empty($data['genero']) ? true : $pelicula->setGenero($data['genero']);
        empty($data['descripcion']) ? true : $pelicula->setDescripcion($data['descripcion']);

        $updatedPelicula = $this->peliculasRepository->updatePelicula($pelicula);

        return new JsonResponse(['status' => 'Pelicula actualizada'], Response::HTTP_OK);
    }

    /**
     * @Route("pelicula/{id}", name="delete_pelicula", methods={"DELETE"})
     */
    public function delete($id): JsonResponse
    {
        $pelicula = $this->peliculasRepository->findOneBy(['id' => $id]);

        if(!empty($pelicula)){
            $this->peliculasRepository->removePelicula($pelicula);

            return new JsonResponse(['status' => 'Pelicula eliminada'], Response::HTTP_OK);
        }
        return new JsonResponse(['status' => 'Pelicula no existente'], Response::HTTP_OK);

    }
}
