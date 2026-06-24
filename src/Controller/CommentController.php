<?php

/**
 * Comment controller.
 */

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\Type\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Class CommentController.
 */
#[Route('/comment')]
class CommentController extends AbstractController
{
    /**
     * Add comment to post.
     *
     * @param Post                   $post    Post entity
     * @param Request                $request HTTP request
     * @param EntityManagerInterface $em      Entity manager
     *
     * @return Response HTTP response
     */
    #[Route('/post/{id}/add', name: 'comment_add', methods: ['POST'])]
    public function add(Post $post, Request $request, EntityManagerInterface $em): Response
    {
        $comment = new Comment();
        $comment->setPost($post);

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($comment);
            $em->flush();

            $this->addFlash('success', 'Komentarz został dodany.');
        } else {
            $this->addFlash('error', 'Nie udało się dodać komentarza.');
        }

        return $this->redirectToRoute('post_view', [
            'id' => $post->getId(),
        ]);
    }

    /**
     * Delete comment.
     *
     * @param Comment                $comment Comment entity
     * @param Request                $request HTTP request
     * @param EntityManagerInterface $em      Entity manager
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/delete', name: 'comment_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Comment $comment, Request $request, EntityManagerInterface $em): Response
    {
        if (!$this->isCsrfTokenValid('delete'.$comment->getId(), $request->request->get('_token'))) {
            $this->addFlash('error', 'Invalid CSRF token.');

            return $this->redirectToRoute('post_view', [
                'id' => $comment->getPost()->getId(),
            ]);
        }

        $postId = $comment->getPost()->getId();

        $em->remove($comment);
        $em->flush();

        $this->addFlash('success', 'Komentarz został usunięty.');

        return $this->redirectToRoute('post_view', [
            'id' => $postId,
        ]);
    }
}
