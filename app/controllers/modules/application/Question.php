<?php
  class Question {
    public static function getAllVanilla() {
      // Issue query to get all questions with vanilla flag on.
      
      // Parse data from query and make array of questions with data
      
      // Return array
    }

    public static function search($query) {
      // Issue query to model

      // Create (array of?) question from returned data and return it
    }

    public static function createCustom() {
      // Construct question with parameters

      // Pass (question object or raw data?) to model to store in database with
      // custom flag

      // Return created question

    }

    public static function createVanilla() {
      // Construct question with parameters

      // Pass (question object or raw data?) to model to store in database with
      // vanilla flag

      // Return created question
    }

    public static function getById($id) {
      // Ask model to get it
      
      // If model can't get it, return false or -1 or something

      // If model can get it, parse and return question
    }

    public static function delete() {

    }

  }
?>