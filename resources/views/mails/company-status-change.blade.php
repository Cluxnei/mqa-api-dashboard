@if($company->isActive())
    A empresa {{ $company->name }} foi <b>aprovada</b> por nossos administradores!
    <br>
    Agora você pode receber e fazer doações!
@else
    Sua empresa, {{ $company->name }}, foi temporáriamente suspensa, você não poderá fazer e receber doações com ela
    <br>
    Mas você ainda pode suas outras empresas normalmente.
    <br>
    Caso tenha alguma dúvida, contate nossos administradores por email
    <a href="mailto:suporte@maosquealimentam.com.br">suporte@maosquealimentam.com.br</a>
@endif