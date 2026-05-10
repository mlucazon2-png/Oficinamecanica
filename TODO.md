# TODO - Correções (sem quebrar o projeto)

- [ ] Ajustar `VeiculoController@store` para não travar o cadastro quando o usuário não tiver perfil em `clientes`
  - Ideia: se `user->cliente` for null, redirecionar para `clientes.edit`/`clientes.create` ou retornar erro orientando o usuário.
- [ ] (Opcional) Criar uma rota/página simples para completar CPF/telefone para o cliente logado
- [ ] Testar manualmente: Login como usuário role=cliente e tentar `POST /veiculos`

