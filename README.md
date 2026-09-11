
## ORCE

    ![PHP](https://img.shields.io/badge/php-%23777BB4.svg?&logo=php&logoColor=white)
    ![Laravel](https://img.shields.io/badge/Laravel-%23FF2D20.svg?logo=laravel&logoColor=white)
    ![Vue.js](https://img.shields.io/badge/Vue.js-4FC08D?logo=vuedotjs&logoColor=fff)
    ![Vuetify](https://img.shields.io/badge/Vuetify-1867C0?logo=vuetify&logoColor=fff)



Sistema de gestão de orçamentos de serviços de telecomunicação para usinas de energia!
Um Cliente entra em contato para contratar internet em N usinas. É realizado um orçamento dessas usinas com a cotação dos N fornecedores (provedores de internet) em cada cidade em que a usina se encontra, considerando valores de adesão, mensalidades, impostos, variados custos e prazos.

## Funcionalidades

- Cadastro de fornecedores, clientes, serviços, usinas, orçamentos e cotações;
- Importação de planilhas com informação de usinas;
- Cálculo de valores de imposto à medida em que a cotação é realizada;
- Visualização de usinas pela API do Gmaps;

## Abaixo alguns destaques

## Visualização de usinas pelo Gmaps
Iterei sobre cada usina com suas coordenadas no componente de marcação do mapa do Gmaps.
<img width="400" height="225" alt="mapa" src="https://github.com/user-attachments/assets/0be30b0d-72f7-4479-8334-ca62295b0c55" />

## Cotações
Usufrui a reatividade do Vue/Vuetify no cálculo dos impostos ao preencher o formulário da cotação.
<img width="1000" height="563" alt="cotacoes" src="https://github.com/user-attachments/assets/cdda1ab4-8df2-4408-ac14-51e5ac977e50" />

## Usinas
Usinas podem participar em N Orçamentos. Utilizei a tabela do Vuetify com expanded-rows para melhor visualização.
<img width="1000" height="563" alt="usinas" src="https://github.com/user-attachments/assets/23884fff-cd0c-49fe-92fc-a96af22e3063" />

## Importação de usinas via planilha
Uma das formas que o usuário iria importar os dados seria via planilha. E, como elas não possuiam um padrão, decidi fazer uma importação "dinâmica" em que se define qual será o valor de cada coluna da planilha. Na importação já poderia vincular essas usinas a um orçamento de determinado cliente.
<img width="1000" height="563" alt="importacao" src="https://github.com/user-attachments/assets/07d46f4e-5f61-4ceb-a043-0aab61e8d356" />

