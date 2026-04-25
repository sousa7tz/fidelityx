<?php 


namespace App\Controllers;
use App\Models\MerchantModel;
use App\Validators\DocumentValidator;
class MerchantController {
    private $db;
    private $merchantModel;

    public function __construct($db) {
        $this->db = $db;
        $this->merchantModel = new MerchantModel($this->db);
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
        
        $isValid = DocumentValidator::isValid($document);
        
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

            $this->merchantModel->create($data);

             // se deu certo, redireciona usuario para o login
            header('Location: index.php?url=merchant/login&success=cadastrado');
            exit;
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

        $merchant = $this->merchantModel->findByEmail($email);



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

        $_SESSION['merchant_id']     = $merchant['id'];
        $_SESSION['merchant_name']   = $merchant['owner_name'];
        $_SESSION['store_name']      = $merchant['store_name'];


        header('Location: index.php?url=merchant/dashboard&success=logged');
        exit;
    }
}


?>