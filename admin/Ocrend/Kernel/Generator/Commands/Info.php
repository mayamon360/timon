<?php

namespace Ocrend\Kernel\Generator\Commands;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Información inicial de comandos
 * 
 */
class Info extends Command {

    protected function configure() {
        $this
        ->setName('info')
        ->setDescription('Da información acerca del generador')
        ->setHelp('Este comando se ocupa de generar información acerca de la consola');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        global $config;

        $output->writeln([
            '',
            'COMANDOS - ' . $config['build']['name'],
            '',
            '--------------------------------------',
            '',
            'app:',
            '   app:m [Nombre] [Argumentos] [Opciones] Crea un modelo',
            '   app:v [Nombre] [Argumentos] [Opciones] Crea nueva vista',
            '   app:c [Nombre] [Argumentos] [Opciones] Crea nuevo controlador',
            '   argumentos: El orden es irrelevante y deben ir juntos, sin separacion entre ellos ',
            '       m: Crea un modelo',
            '       v: Crea una vista',
            '       c: Crea un controlador',
            '   opciones: El orden es irrelevante y deben estar separadas entre espacios',
            '       --db: Si se crea un modelo, es capaz de conectar con la base de datos',
            '       --ajax: Si se crea una vista y modelo, se realiza una conexion por ajax',
            '',
            'users:',
            '   users:install Instala todo el sistema de usuarios'
        ]);
    }
}