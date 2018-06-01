<?php
  /*
   * PDO Database Class
   * Connect to database
   * Create prepared statements
   * Bind values
   * Return rows and results
   */
  class Database {
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;

    private $dbh;
    private $stmt;
    private $error;

    public function __construct(){
      // Set DSN
      $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
      $options = array(
        PDO::ATTR_PERSISTENT => true,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
      );

      // Create PDO instance
      try{

        $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);

      } catch(PDOException $e){

        $this->error = $e->getMessage();

        echo $this->error;
      }

    }
    
    //prepare statement with query
    public function query($sql){
        $this->stmt = $this->dbh->prepare($sql);
    }

    //bind values
    public function bind($param, $value, $type = null){
      if(is_null($type)){
        switch(true){

          case is_init($value):
            $type = PDO::PARAM_INIT;
            break;
            
          case is_bool($value):
            $type = PDO::PARAM_BOOL;
            break;

          case is_null($value):
            $type = PDO::PARAM_NULL;
            break;

          default:
            $type = PDO::PARAM_STR;
          
        }
      }

      $this->stmt->bindValue($param, $value, $type);
    }

    //Execute the prepare statement
    public function execute(){
      return $this->stmt->execute();
    }

    //Get Result Set as array of Objects
    public function resultSet(){
      $this->execute();
      return $this->stmt->fetchAll(PDO::FETCH_OBJ);
    }

    //single result
    public function single(){
      $this->execute();
      return $this->stmt->fetch(PDO::FETCH_OBJ);
    }

    //Get Row Count

    public function rowCount(){
      return $this->stmt->rowCount();
    }

  }