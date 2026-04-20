## Problema encontrado

A validação de CPF/CNPJ baseada apenas na lógica matemática (dígitos verificadores) garante que o documento é estruturalmente válido, mas não assegura que ele exista de fato ou esteja vinculado a uma pessoa/empresa real.

Para validar existência, situação cadastral e titularidade, seria necessário integrar com serviços externos (APIs da Receita ou provedores privados). Isso implica custos, aumento de latência e maior complexidade na aplicação, o que não é adequado para o escopo atual do projeto.

## Decisão

Optou-se por não integrar APIs externas neste momento.

## Solução adotada

- [x] Sanitizar o input (remoção de máscara e caracteres inválidos)
- [x] Validar CPF/CNPJ usando dígitos verificadores
- [x] Rejeitar sequências inválidas (ex: 11111111111)
- [x] Armazenar apenas o valor normalizado (somente números)
- [x] Responsabilizar o usuário pela veracidade dos dados informados
- [x] Verificação de duplicidade no banco (UNIQUE)

## Possíveis melhorias futuras

- [ ] Integração com API externa para validação real (Receita/terceiros)
- [ ] Validação adicional via e-mail ou outro mecanismo de confirmação