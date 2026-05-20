@extends(app_email_layout())

@section('content')
    <h5>Bem-vindo à área de gestão!</h5>
    <p>
        Olá {{ $user->name }},
        <br/>
        Foi-lhe dado acesso à área de gestão do software projetoBase com o perfíl {{ @$user->roles()->first()->display_name }}.
    </p>
    <table style="width: 100%;
    border: 1px solid #ddd;
    padding: 5px 10px 5px 20px;
    background: #eee;">
        <tr>
            <td>
                <p>
                    Acesso: <b>{{ route('admin.login') }}</b>
                    <br/>
                    E-mail: <b>{{ $user->email }}</b>
                    <br/>
                    Palavra-passe: <b>{{ $user->uncrypted_password }}</b>
                </p>
            </td>
            <td>
                <a href="{{ route('admin.login') }}" class="button-link" style="float: right">Entrar na Área de Gestão</a>
            </td>
        </tr>
    </table>
    <p>
        Depois de iniciar sessão, poderá alterar a sua palavra-passe nas definições de conta.
    </p>
@stop
