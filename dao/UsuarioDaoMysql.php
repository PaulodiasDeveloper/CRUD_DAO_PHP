<?php
require_once 'models/Usuario.php';

class UsuarioDaoMysql implements UsuarioDAO
{
    private $pdo;

    public function __construct(PDO $driver)
    {
        $this->pdo = $driver;
    }

    public function add(Usuario $u)
    {
        $sql = $this->pdo->prepare("INSERT INTO usuarios (nome, email) VALUES (:nome, :email)");
        $sql->bindValue(':nome', $u->getNome());
        $sql->bindValue(':email', $u->getEmail());
        $sql->execute();

        $u->setId($this->pdo->lastInsertId());
        return $u;
    }

    public function findAll()
    {
        $array = [];

        $sql = $this->pdo->query("SELECT * FROM usuarios"); // Fazer consulta
        if ($sql->rowCount() > 0) {               // Verificar se tem algum item
            $data = $sql->fetchAll();

            foreach ($data as $item) {            // Cria/tranforma em objetos e enviar para o array
                $u = new Usuario();
                $u->setId($item['id']);
                $u->setNome($item['nome']);
                $u->setEmail($item['email']);

                $array[] = $u;
            }
        }

        return $array;
    }

    public function findByEmail($email)
    {
        $sql = $this->pdo->prepare("SELECT * FROM usuarios WHERE email = :email");
        $sql->bindValue(':email', $email);
        $sql->execute();
        if ($sql->rowCount() > 0) {
            $data = $sql->fetch();
            $u = new Usuario();
            $u->setId($data['id']);
            $u->setNome($data['nome']);
            $u->setEmail($data['email']);
            return $u;
        } else {
            return false;
        }
    }

    public function findById($id)
    {
        $sql = $this->pdo->prepare("SELECT * FROM usuarios WHERE id = :id");
        $sql->bindValue(':id', $id);
        $sql->execute();
        if ($sql->rowCount() > 0) {

            $data = $sql->fetch();
            $u = new Usuario();
            $u->setId($data['id']);
            $u->setNome($data['nome']);
            $u->setEmail($data['email']);
            return $u;
        } else {
            return false;
        }
    }

    function update(Usuario $u)
    {
        $sql = $this->pdo->prepare("UPDATE usuarios SET nome = :nome, email = :email WHERE id = :id");
        $sql->bindValue(':id', $u->getId());
        $sql->bindValue(':nome', $u->getNome());
        $sql->bindValue(':email', $u->getEmail());
        $sql->execute();

        return true;
    }
    function delete(Usuario $id)
    {

    }
}