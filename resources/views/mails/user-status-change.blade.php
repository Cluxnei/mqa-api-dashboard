@if($user->isActive())
    Parabéns, {{ $user->name }} sua conta foi <b>aprovada</b> por nossos administradores, faça login no app!
@else
    Sua conta foi temporáriamente suspensa, você não podera acessar o app!
    <br>
    Caso tenha alguma dúvida, contate nossos administradores por email
    <a href="mailto:suporte@maosquealimentam.com.br">suporte@maosquealimentam.com.br</a>
@endif