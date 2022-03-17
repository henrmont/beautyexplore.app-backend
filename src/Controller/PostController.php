<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Postfile;
use App\Entity\Postlike;
use App\Service\DataFormat;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api')]
class PostController extends AbstractController
{
    #[Route('/register/post', name: 'register_post')]
    public function registerPost(ManagerRegistry $doctrine, Request $request, DataFormat $df, UserInterface $user): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $con = $doctrine->getConnection();
        $request = $df->transformJsonBody($request);

        try {
            $con->beginTransaction();
            $em = $doctrine->getManager();

            $post = new Post();
            $post->setUserId($user->getId());
            $post->setDescription($request->get('description'));
            $post->setTags($request->get('tags'));
            if ($request->get('public') == 2) {
                $post->setPublic(false);
                $post->setCustons($request->get('custons'));
            } else {
                $post->setPublic($request->get('public'));
            }
            $post->setComments($request->get('comment'));
            $post->setExpire($request->get('expire'));
            if ($request->get('expire')) {
                $post->setExpireAt(new \DateTimeImmutable($request->get('time')));
                $post->setErase($request->get('erase'));
            }else{
                $post->setErase(false);
            }
            $post->setCreatedAt(new \DateTimeImmutable());
            $post->setUpdatedAt(new \DateTimeImmutable());
            $em->persist($post);

            $em->flush();

            foreach ($request->get('files') as $vlr) {
                $postfile = new Postfile();
                $postfile->setPostId($post->getId());
                $postfile->setUrl($vlr);
                $postfile->setCreatedAt(new \DateTimeImmutable());
                $postfile->setUpdatedAt(new \DateTimeImmutable());

                $em->persist($postfile);
                $em->flush();
            }

            $con->commit();
        
            return $this->json([
                'message' => 'Post inserido com sucesso',
            ]);
        } catch (\Exception $e) {
            $con->rollback();
            return $this->json([
                'message' => 'Erro no sistema',
            ]);
        }
    }

    #[Route('/get/posts', name: 'get_posts')]
    public function getPosts(ManagerRegistry $doctrine, UserInterface $user, SerializerInterface $serializer): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        try {
            $posts = $doctrine->getRepository(Post::class)->getPost();

            foreach ($posts as $chv => $vlr) {
                $posts[$chv]['files'] = $doctrine->getRepository(Postfile::class)->getPostFiles($vlr['id']);
            }

            $serialized = $serializer->serialize($posts,'json');

            return JsonResponse::fromJsonString($serialized);
        } catch (\Exception $e) {
            return $this->json([
                'message' => 'Erro no sistema',
            ]);
        }
    }

    #[Route('/get/likes/{id}', name: 'get_likes')]
    public function getLikes(ManagerRegistry $doctrine, SerializerInterface $serializer, $id): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        try {
            $likes = $doctrine->getRepository(Postlike::class)->findBy(['post_id' => $id]);

            $serialized = $serializer->serialize($likes,'json');

            return JsonResponse::fromJsonString($serialized);
        } catch (\Exception $e) {
            return $this->json([
                'message' => 'Erro no sistema',
            ]);
        }
    }

    #[Route('/get/liked/{id}', name: 'get_liked')]
    public function getLiked(ManagerRegistry $doctrine, UserInterface $user, SerializerInterface $serializer, $id): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        try {
            $liked = $doctrine->getRepository(Postlike::class)->findOneBy([
                'post_id' => $id,
                'user_id' => $user->getId()
            ]);

            $serialized = $serializer->serialize($liked,'json');

            return JsonResponse::fromJsonString($serialized);
        } catch (\Exception $e) {
            return $this->json([
                'message' => 'Erro no sistema',
            ]);
        }
    }

    #[Route('/like/{id}', name: 'like_post')]
    public function likePost(ManagerRegistry $doctrine, Request $request, DataFormat $df, UserInterface $user, $id): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        
        $con = $doctrine->getConnection();
        
        try {
            $liked = $doctrine->getRepository(Postlike::class)->findOneBy([
                'post_id' => $id,
                'user_id' => $user->getId()
            ]);

            $con->beginTransaction();
            $em = $doctrine->getManager();

            if (!$liked) {
                $like = new Postlike();
                $like->setPostId($id);
                $like->setUserId($user->getId());
                $like->setCreatedAt(new \DateTimeImmutable());
                $like->setUpdatedAt(new \DateTimeImmutable());
    
                $em->persist($like);
            } else {
                $em->remove($liked);
            }
            
            $em->flush();

            $con->commit();
        
            return $this->json([
                'message' => 'Post curtido com sucesso',
            ]);
        } catch (\Exception $e) {
            $con->rollback();
            return $this->json([
                'message' => 'Erro no sistema',
            ]);
        }
    }

}
