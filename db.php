<?php
    require_once("config.php");

    class Db {
        private $conexao;
        public $query;
        function __construct(){
            $this->conectar();
        }

        public function conectar(){
            $this->conexao = mysqli_connect(host, user, pass, dbname);
            if(!$this->conexao){
                die("A conexao com o banco de dados falhou: ". mysqli_connect_error());
            } else{
                $select_db = $this->conexao->select_db(dbname);
                if(!$select_db){
                    die("Falha ao selecionar o banco de dados ". mysqli_connect_error());
                }
            }
        }

        public function desconectar(){
            if(isset($this->conexao)){
                mysqli_close($this->conexao);
                unset($this->conexao);
            }
        }
        public function query($sql){
            if (trim($sql != "")) {
              $this->query = $this->conexao->query($sql);
            }
            if (!$this->query)
              die("Erro na query :<pre> " . $sql ."</pre>");
            return $this->query;
         }
      
          public function fetch_array($statement){
            return mysqli_fetch_array($statement);
          }
          public function fetch_assoc($statement){
            return mysqli_fetch_assoc($statement);
          }
          public function num_rows($statement){
            return mysqli_num_rows($statement);
          }
          public function affected_rows(){
            return mysqli_affected_rows($this->conexao);
          }
          public function while_loop($loop){
            global $db;
            $results = array();
            while ($result = $this->fetch_array($loop)) {
              $results[] = $result;
            }
            return $results;
          }
    }

    $db = new Db();
?>