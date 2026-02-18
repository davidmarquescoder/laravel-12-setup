<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Enums\RoleEnum;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AddFirstDevUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add-first';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cria o primeiro usuário dev do sistema';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔐 Criando o primeiro usuário desenvolvedor...');
        $this->newLine();

        // Verifica se já existe um usuário com a role de desenvolvedor
        $developerExists = User::role(RoleEnum::DEVELOPER->value)->exists();

        if ($developerExists) {
            $this->error('❌ Já existe um usuário com a role de desenvolvedor no sistema!');
            $this->warn('Por questões de segurança, não é permitido criar múltiplos usuários desenvolvedores através deste comando.');
            return Command::FAILURE;
        }

        // Coleta os dados do usuário
        $firstName            = $this->ask('Primeiro nome');
        $lastName             = $this->ask('Sobrenome');
        $cpf                  = $this->ask('CPF (apenas números)');
        $email                = $this->ask('E-mail');
        $password             = $this->secret('Senha (mínimo 8 caracteres)');
        $passwordConfirmation = $this->secret('Confirme a senha');

        // Remove caracteres não numéricos do CPF
        $cpf = preg_replace('/[^0-9]/', '', $cpf);

        // Gera o nome completo
        $name = trim($firstName . ' ' . $lastName);

        // Validação dos dados
        $validator = Validator::make([
            'first_name'            => $firstName,
            'last_name'             => $lastName,
            'cpf'                   => $cpf,
            'email'                 => $email,
            'password'              => $password,
            'password_confirmation' => $passwordConfirmation,
        ], [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name'  => ['required', 'string', 'max:255'],
            'cpf'        => ['required', 'string', 'size:11', 'unique:users', 'regex:/^[0-9]{11}$/'],
            'email'      => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password'   => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'first_name.required' => 'O primeiro nome é obrigatório.',
            'last_name.required'  => 'O sobrenome é obrigatório.',
            'cpf.required'        => 'O CPF é obrigatório.',
            'cpf.size'            => 'O CPF deve conter 11 dígitos.',
            'cpf.regex'           => 'O CPF deve conter apenas números.',
            'cpf.unique'          => 'Este CPF já está cadastrado.',
            'email.required'      => 'O e-mail é obrigatório.',
            'email.email'         => 'O e-mail deve ser válido.',
            'email.unique'        => 'Este e-mail já está em uso.',
            'password.required'   => 'A senha é obrigatória.',
            'password.min'        => 'A senha deve ter no mínimo 8 caracteres.',
            'password.confirmed'  => 'As senhas não coincidem.',
        ]);

        if ($validator->fails()) {
            $this->newLine();
            $this->error('❌ Erro na validação dos dados:');
            foreach ($validator->errors()->all() as $error) {
                $this->error('  • ' . $error);
            }
            return Command::FAILURE;
        }

        try {
            // Cria o usuário
            $user = User::create([
                'name'              => $name,
                'first_name'        => $firstName,
                'last_name'         => $lastName,
                'cpf'               => $cpf,
                'email'             => $email,
                'password'          => Hash::make($password),
                'email_verified_at' => now(),
            ]);

            // Atribui a role de desenvolvedor
            $user->assignRole(RoleEnum::DEVELOPER->value);

            $this->newLine();
            $this->info('✅ Usuário desenvolvedor criado com sucesso!');
            $this->newLine();
            $this->table(
                ['Campo', 'Valor'],
                [
                    ['ID', $user->id],
                    ['Nome Completo', $user->name],
                    ['Primeiro Nome', $user->first_name],
                    ['Sobrenome', $user->last_name],
                    ['CPF', $this->formatCpf($user->cpf)],
                    ['E-mail', $user->email],
                    ['Role', RoleEnum::DEVELOPER->value],
                    ['Criado em', $user->created_at->format('d/m/Y H:i:s')],
                ]
            );

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->newLine();
            $this->error('❌ Erro ao criar o usuário: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }

    /**
     * Formata o CPF para exibição
     */
    private function formatCpf(string $cpf): string
    {
        return substr($cpf, 0, 3) . '.' .
               substr($cpf, 3, 3) . '.' .
               substr($cpf, 6, 3) . '-' .
               substr($cpf, 9, 2);
    }
}
