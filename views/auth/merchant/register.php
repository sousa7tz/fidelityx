<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro Lojista | FidelityX</title>
    
    <link rel="stylesheet" href="/css/global.css">
    <link rel="stylesheet" href="/css/components.css">
    <link rel="stylesheet" href="/css/auth.css">
</head>
<body class="auth-page">

    <div class="auth-container" style="max-width: 600px;"> <header class="auth-header">
            <img src="/assets/fidelityx-logo.svg" alt="FidelityX Logo" class="auth-logo">
            <p>Registre sua empresa no ecossistema FidelityX</p>
        </header>

        <form action="index.php?url=merchant/register" method="POST">
            
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
                        <option value="" disabled selected>Selecione o segmento...</option>
                        <option value="alimentacao">Alimentação / Bebidas</option>
                        <option value="beleza">Beleza & Estética</option>
                        <option value="saude">Saúde / Bem-estar</option>
                        <option value="varejo">Varejo / Comércio</option>
                        <option value="servicos">Serviços Gerais</option>
                        <option value="outros">Outros</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="document">CPF ou CNPJ</label>
                <input type="text" name="document" id="document" placeholder="Apenas números" required>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 0 15px;">
                <div class="form-group">
                    <label for="phone">Telefone</label>
                    <input type="text" name="phone" id="phone" placeholder="(11) 99999-9999" required>
                </div>

                <div class="form-group">
                    <label for="address">Endereço Físico da Loja</label>
                    <input type="text" name="address" id="address" placeholder="Av. Principal, 123" required>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 0 15px;">
                <div class="form-group">
                    <label for="city">Cidade</label>
                    <input type="text" name="city" id="city" placeholder="Ex: Cotia" required>
                </div>

                <div class="form-group">
                    <label for="state">Estado</label>
                    <select name="state" id="state" style="width: 100%; padding: 0.75rem; background: var(--bg-card); color: var(--text-main); border: 1px solid var(--border); border-radius: 8px; font-size: 1rem; outline: none;" required>
                        <option value="" disabled selected>Selecione o estado...</option>
                        <option value="AC">AC</option>
                        <option value="AL">AL</option>
                        <option value="AP">AP</option>
                        <option value="AM">AM</option>
                        <option value="BA">BA</option>
                        <option value="CE">CE</option>
                        <option value="DF">DF</option>
                        <option value="ES">ES</option>
                        <option value="GO">GO</option>
                        <option value="MA">MA</option>
                        <option value="MT">MT</option>
                        <option value="MS">MS</option>
                        <option value="MG">MG</option>
                        <option value="PA">PA</option>
                        <option value="PB">PB</option>
                        <option value="PR">PR</option>
                        <option value="PE">PE</option>
                        <option value="PI">PI</option>
                        <option value="RJ">RJ</option>
                        <option value="RN">RN</option>
                        <option value="RS">RS</option>
                        <option value="RO">RO</option>
                        <option value="RR">RR</option>
                        <option value="SC">SC</option>
                        <option value="SP">SP</option>
                        <option value="SE">SE</option>
                        <option value="TO">TO</option>
                    </select>
                </div>
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