<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Author;
use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BookController extends AbstractController
{
    /**
     * @Route("/api/search_books", name="search_books", methods={"POST"})
     */
    public function searchBooks(Request $request, EntityManagerInterface $em, ValidatorInterface $validator)
    {
        // Récupérer les paramètres de la requête JSON
        $data = json_decode($request->getContent(), true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Invalid JSON input.'
            ], Response::HTTP_BAD_REQUEST);
        }

        $title = $data['title'] ?? null;
        $publicationDate = $data['publication_date'] ?? null;
        $authorName = $data['author'] ?? null;
        $categoryName = $data['category'] ?? null;

        // Validation des paramètres
        $constraints = [];
        if ($title && strlen($title) < 3) {
            $constraints[] = 'Title must be at least 3 characters long.';
        }
        
        if ($publicationDate && !\DateTime::createFromFormat('Y-m-d', $publicationDate)) {
            $constraints[] = 'Publication date is invalid.';
        }

        if ($constraints) {
            return new JsonResponse([
                'status' => 'error',
                'message' => implode(' ', $constraints)
            ], Response::HTTP_BAD_REQUEST);
        }

        // Construire la requête de recherche dynamique
        $queryBuilder = $em->getRepository(Book::class)->createQueryBuilder('b')
            ->leftJoin('b.author', 'a')
            ->leftJoin('b.categories', 'c')
            ->distinct();

        if ($title) {
            $queryBuilder->andWhere('b.title LIKE :title')
                         ->setParameter('title', '%'.$title.'%');
        }

        if ($publicationDate) {
            $queryBuilder->andWhere('b.publicationDate = :publication_date')
                         ->setParameter('publication_date', $publicationDate);
        }

        if ($authorName) {
            $queryBuilder->andWhere('a.firstName LIKE :author_name OR a.lastName LIKE :author_name')
                         ->setParameter('author_name', '%'.$authorName.'%');
        }

        if ($categoryName) {
            $queryBuilder->andWhere('c.name LIKE :category_name')
                         ->setParameter('category_name', '%'.$categoryName.'%');
        }

        // Exécuter la requête pour récupérer les livres filtrés
        $books = $queryBuilder->getQuery()->getResult();

        // Si aucun livre n'est trouvé, retourner une réponse avec un message approprié
        if (empty($books)) {
            return new JsonResponse([
                'status' => 'success',
                'message' => 'No books found matching your criteria.'
            ], Response::HTTP_OK);
        }

        // Retourner les résultats au format JSON
        return new JsonResponse([
            'status' => 'success',
            'message' => 'Books found successfully.',
            'data' => $books
        ], Response::HTTP_OK);
    }
}
