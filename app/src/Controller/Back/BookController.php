<?php

namespace App\Controller\Back;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class BookController
 * @package App\Controller
 *
 * @Route("/books", name="book_")
 */
class BookController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     *
     * Call BookRepository with Autowiring
     */
    public function index(BookRepository $bookRepository)
    {
        return $this->render('back/book/index.html.twig', [
            'books' => $bookRepository->findAll()
        ]);
    }

    /**
     * @Route("/search/{q}", name="search", methods={"GET"})
     */
    public function search($q, BookRepository $bookRepository)
    {
        return $this->render('back/book/search.html.twig', [
            'books' => $bookRepository->search($q, true)
        ]);
    }

    /**
     * @Route("/show/{id}", name="show", methods={"GET"})
     *
     * Call Book with ParamConverter
     */
    public function show(Book $book)
    {
        return $this->render('back/book/show.html.twig', [
            'book' => $book
        ]);
    }

    /**
     * @Route("/new", name="new", methods={"GET", "POST"})
     */
    public function new(Request $request)
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($book);
            $em->flush();

            return $this->redirectToRoute('admin_book_show', [
                'id' => $book->getId()
            ]);
        }

        return $this->render('back/book/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET", "POST"})
     */
    public function edit(Book $book, Request $request)
    {
        $form = $this->createForm(BookType::class, $book);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_book_edit', [
                'id' => $book->getId()
            ]);
        }

        return $this->render('back/book/edit.html.twig', [
            'form' => $form->createView(),
            'book' => $book
        ]);
    }

    /**
     * @Route("/delete/{id}/{token}", name="delete", methods={"GET"})
     */
    public function delete(Book $book, $token)
    {
        if (!$this->isCsrfTokenValid('delete_book' . $book->getName(), $token)) {
            throw new Exception('Invalid CSRF Token');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($book);
        $em->flush();

        return $this->redirectToRoute('admin_book_index');
    }
}
