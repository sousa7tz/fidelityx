<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro Profissional | FidelityX</title>
    
    <link rel="stylesheet" href="/css/global.css">
    <link rel="stylesheet" href="/css/components.css">
    <link rel="stylesheet" href="/css/auth.css">
</head>
<body class="auth-page">

    <div class="auth-container" style="max-width: 600px;"> <header class="auth-header">
            <img src="/assets/fidelityx-logo.svg" alt="FidelityX Logo" class="auth-logo">
            <p>Registre sua empresa no ecossistema FidelityX</p>
        </header>

        <form action="/api/register-merchant" method="POST">
            
            <div class="form-group">
                <label for="owner_name">Nome do Responsável</label>
                <input type="text" name="owner_name" id="owner_name" placeholder="Quem responderá pela conta?" required>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 0 15px;">
                <div class="form-group">
                    <label for="shop_name">Nome da Loja (Fantasia)</label>
                    <input type="text" name="shop_name" id="shop_name" placeholder="Ex: Burguer do Bairro" required>
                </div>

                <div class="form-group">
                    <label for="category">Segmento</label>
                    <select name="category" id="category" style="width: 100%; padding: 0.75rem; background: var(--bg-card); color: var(--text-main); border: 1px solid var(--border); border-radius: 8px; font-size: 1rem; outline: none;">
                        <option value="alimentacao">Alimentação / Bebidas</option>
                        <option value="beleza">Beleza & Estética</option>
                        <option value="saude">Saúde / Bem-estar</option>
                        <option value="varejo">Varejo / Comércio</option>
                        <option value="servicos">Serviços Gerais</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="document">CPF ou CNPJ</label>
                <input type="text" name="document" id="document" placeholder="Apenas números" required>
            </div>

            <div class="form-group">
                <label for="address">Endereço Físico da Loja</label>
                <input type="text" name="address" id="address" placeholder="Av. Principal, 123 - Centro, Cotia/SP" required>
            </div>

            <div style="border-top: 1px solid var(--border); margin: 2rem 0 1.5rem 0; position: relative;">
                <span style="position: absolute; top: -12px; left: 50%; transform: translateX(-50%); background: var(--bg-card); padding: 0 10px; font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase;">Acesso ao Sistema</span>
            </div>

            <div class="form-group">
                <label for="email">E-mail Comercial</label>
                <input type="email" name="email" id="email" placeholder="seu@email.com" required>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 0 15px;">
                <div class="form-group">
                    <label for="password">Senha</label>
                    <input type="password" name="password" id="password" placeholder="Mínimo 6 dígitos" required>
                </div>

                <div class="form-group">
                    <label for="password_confirm">Confirmar Senha</label>
                    <input type="password" name="password_confirm" id="password_confirm" placeholder="Repita a senha" required>
                </div>
            </div>

            <button type="submit" class="btn-primary" style="margin-top: 1rem;">Criar Minha Conta Profissional</button>
        </form>

        <footer class="auth-footer">
            <p>Já é parceiro? <a href="/index.php?url=merchant/login">Entrar no painel</a></p>
        </footer>
    </div>

</body>
</html>