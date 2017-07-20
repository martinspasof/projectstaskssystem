<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\Project;

/**
 * Task
 *
 * @ORM\Table(name="task")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TaskRepository")
 */
class Task {

  /**
   * @var int
   *
   * @ORM\Column(name="id", type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $id;

  /**
   * @var string
   *
   * @ORM\Column(name="task_name", type="string", length=255, unique=true)
   */
  private $taskName;

  /**
   * @var int
   *
   * @ORM\ManyToOne(targetEntity="Project", inversedBy="projects")
   * @ORM\JoinColumn(name="project_id", referencedColumnName="id", nullable=false)
   * @ORM\JoinColumn(onDelete="CASCADE")
   */
  private $project;

  /**
   * @var string
   *
   * @ORM\Column(name="description", type="string", length=255)
   */
  private $description;

  /**
   * @var \DateTime
   *
   * @ORM\Column(name="due_date", type="datetime")
   */
  private $dueDate;

  /**
   * @var string
   *
   * @ORM\Column(name="status", type="string", length=255)
   */
  private $status;


  /**
   * Get id
   *
   * @return int
   */
  public function getId() {
    return $this->id;
  }

  /**
   * Set taskName
   *
   * @param string $taskName
   *
   * @return Task
   */
  public function setTaskName($taskName) {
    $this->taskName = $taskName;

    return $this;
  }

  /**
   * Get taskName
   *
   * @return string
   */
  public function getTaskName() {
    return $this->taskName;
  }

  /**
   * Set project
   *
   * @param integer $project
   *
   * @return Task
   */
  public function setProject($project) {
    $this->project = $project;

    return $this;
  }

  /**
   * Get project
   *
   * @return int
   */
  public function getProject() {
    return $this->project;
  }

  /**
   * Set description
   *
   * @param string $description
   *
   * @return Task
   */
  public function setDescription($description) {
    $this->description = $description;

    return $this;
  }

  /**
   * Get description
   *
   * @return string
   */
  public function getDescription() {
    return $this->description;
  }

  /**
   * Set dueDate
   *
   * @param \DateTime $dueDate
   *
   * @return Task
   */
  public function setDueDate($dueDate) {
    $this->dueDate = $dueDate;

    return $this;
  }

  /**
   * Get dueDate
   *
   * @return \DateTime
   */
  public function getDueDate() {
    return $this->dueDate;
  }

  /**
   * Set status
   *
   * @param string $status
   *
   * @return Task
   */
  public function setStatus($status) {
    $this->status = $status;

    return $this;
  }

  /**
   * Get status
   *
   * @return string
   */
  public function getStatus() {
    return $this->status;
  }
}

