<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;

class ProjectController extends Controller
{
  /**
   * @Route("/projects", name="project_list",requirements={"_format"="json"})
   * @Method("GET")
   */
  public function listAction(Request $request)
  {
    $projects = $this->getDoctrine()
      ->getRepository('AppBundle:Project')
      ->findAll();

    //json format
    $em = $this->getDoctrine()->getEntityManager();
    $query = $em->createQuery(
      'SELECT pr
            FROM AppBundle:Project pr'
    );

    $projects_result = $query->getArrayResult();

    $data = [
      'projects'=>$projects_result
    ];

    $projects_json_encode = json_encode($data);

    return $this->render('project/index.html.twig', [
      'projects' => $projects,
      'projects_json_decode' => $this->project_twig_json_decode($projects_json_encode)
    ]);

  }

  public function project_twig_json_decode($json=null){
    if(isset($json)) {
        return json_decode($json, TRUE);
    }

  }

}
