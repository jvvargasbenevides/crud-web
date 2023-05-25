<!DOCTYPE html>
<html>
<head>
    <title>CRUD de Clientes</title>
</head>
<body>
    <h1>CRUD de Clientes</h1>

    <?php
    // Configuração do banco de dados
    $servername = "localhost";
    $username = "seu_usuario";
    $password = "sua_senha";
    $dbname = "clientes";

    // Conecta ao banco de dados
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Falha na conexão com o banco de dados: " . $conn->connect_error);
    }

    // Processamento do formulário
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Verifica se é uma operação de criação ou atualização
        if ($_POST["operacao"] == "criar") {
            $nome = $_POST["nome"];
            $email = $_POST["email"];
            $telefone = $_POST["telefone"];

            // Insere um novo cliente no banco de dados
            $sql = "INSERT INTO clientes (nome, email, telefone) VALUES ('$nome', '$email', '$telefone')";
            if ($conn->query($sql) === TRUE) {
                echo "Cliente criado com sucesso.";
            } else {
                echo "Erro ao criar o cliente: " . $conn->error;
            }
        } elseif ($_POST["operacao"] == "atualizar") {
            $id = $_POST["id"];
            $nome = $_POST["nome"];
            $email = $_POST["email"];
            $telefone = $_POST["telefone"];

            // Atualiza um cliente existente no banco de dados
            $sql = "UPDATE clientes SET nome='$nome', email='$email', telefone='$telefone' WHERE id=$id";
            if ($conn->query($sql) === TRUE) {
                echo "Cliente atualizado com sucesso.";
            } else {
                echo "Erro ao atualizar o cliente: " . $conn->error;
            }
        }
    }

    // Exclusão de cliente
    if (isset($_GET["excluir"])) {
        $id = $_GET["excluir"];

        // Exclui o cliente do banco de dados
        $sql = "DELETE FROM clientes WHERE id=$id";
        if ($conn->query($sql) === TRUE) {
            echo "Cliente excluído com sucesso.";
        } else {
            echo "Erro ao excluir o cliente: " . $conn->error;
        }
    }

    // Formulário de criação/atualização
    function renderForm($nome = '', $email = '', $telefone = '', $operacao = 'criar', $id = '') {
        echo '
        <form action="" method="POST">
            <input type="hidden" name="id" value="' . $id . '">
            <input type="hidden" name="operacao" value="' . $operacao . '">
            <label for="nome">Nome:</label>
            <input type="text" name="nome" value="' . $nome . '" required><br><br>
            <label for="email">Email:</label>
            <input type="email" name="email" value="' . $email . '" required><br><br>
            <label for="telefone">Telefone:</label>
            <input type="text" name="telefone" value="' . $telefone . '" required><br><br>
            <input type="submit" value="Salvar">
        </form>';
    }

    // Lista os clientes existentes
    $sql = "SELECT * FROM clientes";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<h2>Lista de Clientes:</h2>";
        echo "<ul>";
        while ($row = $result->fetch_assoc()) {
            echo "<li>";
            echo "Nome: " . $row["nome"] . " | Email: " . $row["email"] . " | Telefone: " . $row["telefone"];
            echo " | <a href='index.php?excluir=" . $row["id"] . "'>Excluir</a>";
            echo " | <a href='index.php?editar=" . $row["id"] . "'>Editar</a>";
            echo "</li>";
        }
        echo "</ul>";
    } else {
        echo "Não há clientes cadastrados.";
    }

    // Formulário de criação ou edição
    if (isset($_GET["editar"])) {
        $id = $_GET["editar"];

        // Obtém as informações do cliente para preencher o formulário de edição
        $sql = "SELECT * FROM clientes WHERE id=$id";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        renderForm($row["nome"], $row["email"], $row["telefone"], "atualizar", $id);
    } else {
        renderForm();
    }

    // Fecha a conexão com o banco de dados
    $conn->close();
    ?>

</body>
</html>
