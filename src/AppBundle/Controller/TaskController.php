<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Project;
use AppBundle\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\Route;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class TaskController extends Controller {

  /**
   * @Route("/", name="task_list",requirements={"_format"="json"})
   * @Method("GET")
   */
  public function listAction() {
    $tasks = $this->getDoctrine()
      ->getRepository('AppBundle:Task')
      ->findAll();

    //json format
    $em = $this->getDoctrine()->getEntityManager();
    $query = $em->createQuery(
      'SELECT ta,pr.projectName            
            FROM AppBundle:Task ta
            INNER JOIN AppBundle:Project pr
            WHERE IDENTITY(ta.project) = pr.id'
    );

    $tasks_result = $query->getArrayResult();

    $data = [
      'tasks' => $tasks_result,
    ];

    $tasks_json_encode = json_encode($data);

    return $this->render('task/index.html.twig', [
      'tasks' => $tasks,
      'tasks_json_decode' => $this->task_twig_json_decode($tasks_json_encode),
    ]);
  }

  public function task_twig_json_decode($json = NULL) {
    if (isset($json)) {
      return json_decode($json, TRUE);
    }
  }

  /**
   * @Route("/task/create", name="task_create")
   */
  public function createTaskAction(Request $request) {
    $task = new Task();

    $form = $this->createFormBuilder($task)
      ->add('task_name', TextType::class, [
        'attr' => [
          'class' => 'form-control',
          'style' => 'margin-bottom:15px',
        ],
      ])
      ->add('project', EntityType::class, [
        'class' => 'AppBundle:Project',
        'choice_label' => 'projectName',
        'expanded' => FALSE,
        'multiple' => FALSE,
        'attr' => ['class' => 'form-control', 'style' => 'margin-bottom:15px'],
      ])
      ->add('description', TextareaType::class, [
        'attr' => [
          'class' => 'form-control',
          'style' => 'margin-bottom:15px',
        ],
      ])
      ->add('due_date', DateTimeType::class, [
        'attr' => [
          'class' => 'formcontrol',
          'style' => 'margin-bottom:15px',
        ],
      ])
      ->add('status', ChoiceType::class, [
        'choices' => [
          'Low' => 'Low',
          'Normal' => 'Normal',
          'High' => 'High',
        ],
        'attr' => ['class' => 'form-control', 'style' => 'margin-bottom:15px'],
      ])
      ->add('save', SubmitType::class, [
        'label' => 'Create Task',
        'attr' => [
          'class' => 'btn btn-primary',
          'style' => 'margin-bottom:15px',
        ],
      ])
      ->getForm();

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      // Get Data
      $task_name = $form['task_name']->getData();
      $project = $form['project']->getData();
      $description = $form['description']->getData();
      $due_date = $form['due_date']->getData();
      $status = $form['status']->getData();

      $task->setTaskName($task_name);
      $task->setProject($project);
      $task->getDescription($description);
      $task->setDueDate($due_date);
      $task->setStatus($status);

      $em = $this->getDoctrine()->getManager();

      $em->persist($task);
      $em->flush();
      $em->clear();

      $this->addFlash(
        'notice',
        'Task Added'
      );

      return $this->redirectToRoute('task_list');
    }

    return $this->render('task/create.html.twig', [
      'form' => $form->createView(),
    ]);
  }

  /**
   * @Route("/task/edit/{id}", name="task_edit")
   */
  public function editAction($id, Request $request) {
    $task = $this->getDoctrine()
      ->getRepository('AppBundle:Task')
      ->find($id);

    $task->setTaskName($task->getTaskName());
    $task->setProject($task->getProject());
    $task->getDescription($task->getDescription());
    $task->setDueDate($task->getDueDate());
    $task->setStatus($task->getStatus());

    $form = $this->createFormBuilder($task)
      ->add('task_name', TextType::class, [
        'attr' => [
          'class' => 'form-control',
          'style' => 'margin-bottom:15px',
        ],
      ])
      ->add('project', EntityType::class, [
        'class' => 'AppBundle:Project',
        'choice_label' => 'projectName',
        'expanded' => FALSE,
        'multiple' => FALSE,
        'attr' => ['class' => 'form-control', 'style' => 'margin-bottom:15px'],
      ])
      ->add('description', TextareaType::class, [
        'attr' => [
          'class' => 'form-control',
          'style' => 'margin-bottom:15px',
        ],
      ])
      ->add('due_date', DateTimeType::class, [
        'attr' => [
          'class' => 'formcontrol',
          'style' => 'margin-bottom:15px',
        ],
      ])
      ->add('status', ChoiceType::class, [
        'choices' => [
          'Low' => 'Low',
          'Normal' => 'Normal',
          'High' => 'High',
        ],
        'attr' => ['class' => 'form-control', 'style' => 'margin-bottom:15px'],
      ])
      ->add('save', SubmitType::class, [
        'label' => 'Update Task',
        'attr' => [
          'class' => 'btn btn-primary',
          'style' => 'margin-bottom:15px',
        ],
      ])
      ->getForm();

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      // Get Data
      $task_name = $form['task_name']->getData();
      $project = $form['project']->getData();
      $description = $form['description']->getData();
      $due_date = $form['due_date']->getData();
      $status = $form['status']->getData();

      $em = $this->getDoctrine()->getManager();
      $task = $em->getRepository('AppBundle:Task')->find($id);

      $task->setTaskName($task_name);
      $task->setProject($project);
      $task->getDescription($description);
      $task->setDueDate($due_date);
      $task->setStatus($status);

      $em->flush();
      $em->clear();

      $this->addFlash(
        'notice',
        'Task Updated'
      );

      return $this->redirectToRoute('task_list');
    }

    return $this->render('task/edit.html.twig', [
      'task' => $task,
      'form' => $form->createView(),
    ]);
  }

  /**
   * @Route("/task/details/{id}", name="task_details")
   */
  public function detailsAction($id) {
    $task = $this->getDoctrine()
      ->getRepository('AppBundle:Task')
      ->find($id);


    return $this->render('task/details.html.twig', [
      'task' => $task,
    ]);
  }

  /**
   * @Route("/task/delete/{id}", name="task_delete")
   */
  public function deleteAction($id) {
    $em = $this->getDoctrine()->getManager();
    $task = $em->getRepository('AppBundle:Task')->find($id);

    $em->remove($task);
    $em->flush();
    $em->clear();

    $this->addFlash(
      'notice',
      'Task Removed'
    );

    return $this->redirectToRoute('task_list');
  }

}
