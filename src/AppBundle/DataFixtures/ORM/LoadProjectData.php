<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Project;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadProjectData implements FixtureInterface {

  public function load(ObjectManager $manager) {

    $project = (new Project())
      ->setProjectName('Book Library')
      ->setDescription('Online books');

    $manager->persist($project);
    $manager->flush();
    $manager->clear();

    $project = (new Project())
      ->setProjectName('Games')
      ->setDescription('Online games');

    $manager->persist($project);
    $manager->flush();
    $manager->clear();

    $project = (new Project())
      ->setProjectName('Social Network')
      ->setDescription('For online communication between people');

    $manager->persist($project);
    $manager->flush();
    $manager->clear();

    $project = (new Project())
      ->setProjectName('Online Banking')
      ->setDescription('System for online pay');

    $manager->persist($project);
    $manager->flush();
    $manager->clear();

    $project = (new Project())
      ->setProjectName('Customer System')
      ->setDescription('Web project for management system for customers of the Insurance company');

    $manager->persist($project);
    $manager->flush();
    $manager->clear();
    
  }
}