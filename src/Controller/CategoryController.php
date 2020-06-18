<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Category;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use App\Form\CategoryType;

class CategoryController extends AbstractController
{
  /**
   * @Route("/category", name="category")
   * 
   * @Security("is_granted('ROLE_ADMIN')")
   * 
   */
  public function index()
  {
    $em = $this->getDoctrine()->getManager();
    $categories = $em->getRepository(Category::class)->findAll();

    return $this->render('category/index.html.twig', [
      'list' => $categories,
    ]);
  }

  /**
   * @Route("/category/change/{id}", name="category_change")
   * 
   * @Security("is_granted('ROLE_ADMIN')")
   * 
   */
  public function change(Request $request, int $id)
  {
    $em = $this->getDoctrine()->getManager();
    $cat = $em->getRepository(Category::class)->find($id);
    if(!$cat){
      $cat = new Category();
      $title = 'Create category';
    }else {
      $title = 'Edit category';
    }

    $form = $this->createForm(CategoryType::class, $cat);

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()){
      if(is_null($cat->getId())){
        $em->persist($form->getData());
      }
      $em->flush();
      return $this->redirectToRoute('category');
    }

    return $this->render('category/change.html.twig', [
      'title' => $title,
      'cat'  => $cat,
      'form'  => $form->createView(),
    ]);
  }

  /**
   * @Route("/category/delete/{id}", name="category_delete")
   * 
   * @Security("is_granted('ROLE_ADMIN')")
   * 
   */
  public function delete(Request $request, int $id)
  {
    $em = $this->getDoctrine()->getManager();
    $cat = $em->getRepository(Category::class)->find($id);
    if(!$cat){
      throw $this->creatNotFoundException('The category '.$id.' does not exists');
    }

    $form = $this->createForm(CategoryType::class, $cat);

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()){
      $em->remove($form->getData());
      $em->flush();
      return $this->redirectToRoute('category');
    }

    return $this->render('category/delete.html.twig', [
      'title' => 'Delete',
      'cat'  => $cat,
      'form'  => $form->createView(),
    ]);
  }
}
