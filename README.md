## NDS - Desafio Prático

#### Requisitos Gerais
1. Utilizar Laravel 8. [Instalação Laravel](https://laravel.com/docs/8.x/installation#installation-via-composer)
2. Utilizar Migrations para criar as tabelas no banco e utilizar o timestamps e softDeletes. [Doc Laravel sobre Migrations](https://laravel.com/docs/8.x/migrations#introduction)
3. O backend será uma API Rest.
4. O frontend utilizará o React.

#### [1º Etapa](https://github.com/IvanovAlmeida/nds_practice_challenge/tree/etapa_1)
- [ ] Cadastrar usuário (incluindo senha e armazenar como hash. [Doc Laravel sobre Hash](https://laravel.com/docs/8.x/hashing))
    - [ ] Usuário deve ter os atributos: nome(min: 2 caracteres, max: 80 caracteres), senha(min: 6 caracteres), email(validar se email) e data de nascimento(obrigatório).
- [ ] Alterar usuário (exeto a senha)
- [ ] Alterar senha do usuário
- [ ] Visualizar usuário (obter suas informações - exceto senha)
- [ ] Implementar validações para os campos recebidos nas requisições antes de inserir ou alterar o registro. [Doc Laravel sobre validação](https://laravel.com/docs/8.x/validation#quick-writing-the-validation-logic)

#### 2º Etapa - Refatorar para uso do padrão Repository.
- [ ] Remover as chamadas das model direto dos controllers e utilizar o padrão Repository.
- [ ] O repository deve realizar apenas as chamadas ao banco como consultas, inserções e atualizações.

#### 3º Etapa - Refatorar para uso de Interfaces e Injeção de Dependência
- [ ] Definir as interfaces necessárias(repositories e outras classes).
- [ ] Realizar a implementação de injeção de dependência.

#### 4º Etapa - Refatorar para uso de Services, Interfaces e UnitOfWork
- [ ] A definir
