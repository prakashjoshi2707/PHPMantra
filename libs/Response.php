<?php
  namespace libs;

  /**
   *
   */
  class Response
  {
      public function toJson()
      {
          return json_encode($this);
      }
      public function toArray()
      {
          return (array)$this;
      }
  }
