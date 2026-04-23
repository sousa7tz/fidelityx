<?php 


namespace App\Controllers;
class MerchantController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }
    
    // RENDERS ------------------------------------------------------------
    public function renderRegister() {
            // a array $_SERVER guarda tudo que vem na requisição.
            // se for POST, é porque o usuário clicou o botão (está tentando registrar).
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $this->handleRegister(); // então, joga pro método de registro.
                return;
            }
            // se o método não for POST, vai ser GET, só carrega ou recarrega a página normalmente. 
            require_once __DIR__ . '/../../views/auth/merchant/register.php';


    }

    public function renderLogin() {
        // explicação geral no método renderRegister().
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleLogin();
            return;
        }
        
        require_once __DIR__ . '/../../views/auth/merchant/login.php';

    }

    

    // HANDLES -----------------------------------------------------------

    private function validarCPF($document) {
        // remover tudo que não seja um número usando regex (expressão regular). '/\D/' representa TUDO que não seja um número 0-9.
        //                      "/\D/" |  ''   |    $document
        //                      remove, substitui, declara na variavel
        $document = preg_replace('/\D/', '', (string)$document);
    
        // cpf precisa ter exatamente 11 dígitos
        if (strlen($document) != 11) {
            return false;
        }
    
        // bloqueia cpf invalido com todos os dígitos iguais
        // Ex: 11111111111, 00000000000, etc
        if (preg_match('/(\d)\1{10}/', $document)) {
            return false;
        }
    
        // ============================
        // CÁLCULO DOS DÍGITOS
        // ============================
        
        // Loop para calcular os 2 últimos dígitos
        for ($t = 9; $t < 11; $t++) {
            
            $soma = 0;
    
            // Multiplica cada dígito pelos pesos decrescentes
            for ($i = 0; $i < $t; $i++) {
                $soma += $document[$i] * (($t + 1) - $i);
            }
    
            // Aplica regra do módulo 11
            $digito = ((10 * $soma) % 11) % 10;
    
            // Compara com o dígito real do CPF
            if ($document[$t] != $digito) {
                return false;
            }
        }
    
        // se passou por tudo é valido
        return true;
    }

    private function validarCNPJ($document) {
        // Remove tudo que não for número
        $document = preg_replace('/\D/', '', $document);
        
        // CNPJ precisa ter 14 dígitos
        if (strlen($document) != 14) {
            return false;
        }
    
        // Bloqueia sequências inválidas (ex: 11111111111111)
        if (preg_match('/(\d)\1{13}/', $document)) {
            return false;
        }
    
        // ============================
        // PRIMEIRO DÍGITO VERIFICADOR
        // ============================
        
        $tamanho = 12; // base sem os 2 dígitos finais
        $numeros = substr($document, 0, $tamanho);
        
        $soma = 0;
        $peso = 5;
        
        // Multiplica com pesos: 5 4 3 2 9 8 7 6 5 4 3 2
        for ($i = 0; $i < $tamanho; $i++) {
            $soma += $numeros[$i] * $peso;
            $peso--;
            
            // Quando chega em 2, reinicia em 9
            if ($peso < 2) {
                $peso = 9;
            }
        }
    
        // Regra do módulo 11
        $resto = $soma % 11;
        $digito1 = ($resto < 2) ? 0 : 11 - $resto;
        
        // Verifica o primeiro dígito
        if ($document[12] != $digito1) {
            return false;
        }
    
        // ============================
        // SEGUNDO DÍGITO VERIFICADOR
        // ============================
        
        $tamanho = 13;
        $numeros = substr($document, 0, $tamanho);
        
        $soma = 0;
        $peso = 6;
        
        // Pesos: 6 5 4 3 2 9 8 7 6 5 4 3 2
        for ($i = 0; $i < $tamanho; $i++) {
            $soma += $numeros[$i] * $peso;
            $peso--;
    
            if ($peso < 2) {
                $peso = 9;
            }
        }
        
        $resto = $soma % 11;
        $digito2 = ($resto < 2) ? 0 : 11 - $resto;
    
        // Verifica o segundo dígito
        if ($document[13] != $digito2) {
            return false;
        }
    
        return true;
    }

    public function handleRegister(){
        // trazer dados do input
        $owner_name =   filter_input(INPUT_POST, 'owner_name');
        $store_name =   filter_input(INPUT_POST, 'shop_name');
        $address    =   filter_input(INPUT_POST, 'address');
        $state      =   filter_input(INPUT_POST, 'state');
        $city       =   filter_input(INPUT_POST, 'city');
        $email      =   filter_input(INPUT_POST, 'email');
        $phone      =   filter_input(INPUT_POST, 'phone');
        $category   =   filter_input(INPUT_POST, 'category');
        $document   =   filter_input(INPUT_POST, 'document');
        $password   =   $_POST['password'] ?? '';
        
        // tratamento dos input masks vindos do front-end.
        $document   = preg_replace('/\D/', '', (string)$document);
        $phone      = preg_replace('/\D/', '', (string)$phone);
        
        $isValid = false;

        if (strlen($document) == 11) {
            $isValid = $this->validarCPF($document); // guardando o resultado do metodo
        } elseif (strlen($document) == 14) {
            $isValid = $this->validarCNPJ($document); // same thing
        }
        
        // se nao for valido, ja para a requisicao
        if (!$isValid) {
            header('Location: index.php?url=merchant/register&error=documento_invalido');
            exit; // sempre dar exit após redir
        }

        
        // verificacao dos dados

        if (!$owner_name || !$store_name || !$address || !$state || !$city || !$email || !$phone || !$category || !$document || strlen($password) < 6 ){
            header('Location: index.php?url=merchant/register&error=campos_invalidos');
            exit;
        }
        

        // segurança e salvar
        // fazendo hash da senha
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        try { // salvando no banco
            $cpf = strlen($document) === 11 ? $document : null;
            $cnpj = strlen($document) === 14 ? $document : null;

            // prepared statements
            $data = [
                ':on'        => $owner_name,
                ':sn'        => $store_name,
                ':address'   => $address,
                ':state'     => $state,
                ':city'      => $city,
                ':email'     => $email,
                ':phone'     => $phone,
                ':category'  => $category,
                ':cpf'       => $cpf,
                ':cnpj'      => $cnpj,
                ':password_hash' => $hashedPassword
            ];

             // se deu certo, redireciona usuario para o login
            header('Location: index.php?url=merchant/login&success=cadastrado');
        } catch (\PDOException $e) {
            $sqlState = $e->errorInfo[0] ?? null;
            $driverCode = (int)($e->errorInfo[1] ?? 0);

            error_log('[MerchantController::handleRegister] PDOException: ' . $e->getMessage());

            // duplicidade (email/cpf/cnpj já cadastrados)
            if ($sqlState === '23000' && $driverCode === 1062) {
                header('Location: index.php?url=merchant/register&error=ja_cadastrado');
                exit;
            }

            // valor nulo em campo obrigatório no banco
            if ($sqlState === '23000' && $driverCode === 1048) {
                header('Location: index.php?url=merchant/register&error=campos_obrigatorios');
                exit;
            }

            // valor maior que o tamanho da coluna
            if ($sqlState === '22001' || $driverCode === 1406) {
                header('Location: index.php?url=merchant/register&error=dados_muito_longos');
                exit;
            }

            // formato incompativel com o tipo da coluna
            if ($sqlState === '22007' || $sqlState === '22018' || $driverCode === 1292) {
                header('Location: index.php?url=merchant/register&error=formato_invalido');
                exit;
            }

            // falha de conexao com o banco
            if ($driverCode === 2002 || $driverCode === 2006 || $sqlState === 'HY000') {
                header('Location: index.php?url=merchant/register&error=banco_indisponivel');
                exit;
            }

            // erros nao mapeados
            header('Location: index.php?url=merchant/register&error=erro_servidor');
            exit;
        }

    }
    


    public function handleLogin(){
        $email      =    filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password   =    $_POST['password'] ?? '';

        if (!$email || !$password) {
            header('Location: index.php?url=merchant/login&error=campos_obrigatorios');
            exit;
        }

        $sql = 'SELECT id, owner_name, store_name, password_hash, status from merchants WHERE email = :email';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':email'    =>  $email,
        ]);
        $merchant = $stmt->fetch(\PDO::FETCH_ASSOC); // ESSA LINHA É PRA GERAR UM ARRAY COM OS RESULTADOS DA QUERIE



        if(!$merchant || !password_verify($password, $merchant['password_hash'])){
            header('Location: index.php?url=merchant/login&error=credenciais_invalidas');
            exit;
        }

        // FUTURAMENTE, SERÁ IMPLEMENTADA A LÓGICA DE CONTAS ATIVAS/INATIVAS:

        /*
        if($merchant['status'] === 'inactive'){
            header('Location: index.php?url=merchant/login&error=conta_inativa');
            exit;
        }
        */

        $SESSION['merchant_id']     = $merchant['id'];
        $SESSION['merchant_name']   = $merchant['owner_name'];
        $SESSION['store_name']      = $merchant['store_name'];


        header('Location: index.php?url=merchant/dashboard&success=logged');
        exit;
    }
}


?>