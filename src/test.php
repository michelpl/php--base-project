<?php

//echo 1 . PHP_EOL;

/*id_cliente
id_apartamento
data_hora_visita*/

(int) $idCliente = 5;
(int) $idApartamento = 350;
(string) $dataHoraVisita = "2023-03-01 15:00:00";

$visitas = [];


agendaVisita($idCliente, $idApartamento, $dataHoraVisita);

(int) $idCliente = 2;
(int) $idApartamento = 350;
(string) $dataHoraVisita = "2023-03-01 15:00:00";

agendaVisita($idCliente, $idApartamento, $dataHoraVisita);

(int) $idCliente = 1;
(int) $idApartamento = 987;
(string) $dataHoraVisita = "2023-03-02 14:00:00";


agendaVisita($idCliente, $idApartamento, $dataHoraVisita);


//visitas exclusivas
//cancelar uma visita
//marcar como realizada

function agendaVisita(
    int $idCliente, 
    int $idApartamento, 
    string $dataHoraVisita
): bool
{
    $dataHoraKey = 2;
    $idApartamentoKey = 1;
    $id = rand(1, 9999);

    foreach ($GLOBALS['visitas'] as $key => $visita) {
        if ($visita[$idApartamentoKey] == $idApartamento && $visita[$dataHoraKey] = $dataHoraVisita) {
            print_r("Apartamento: " . $idApartamento . " na data hora: " . $dataHoraVisita . " já está com outra visita agendada");
            return false;
        }
        echo $key . PHP_EOL;
    }

    $GLOBALS['visitas'][$id] = [$idCliente, $idApartamento, $dataHoraVisita, 'agendado'];

    print_r($GLOBALS['visitas']);

    return true;
}