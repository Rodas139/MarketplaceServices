<?php

namespace App\Helpers;

class OrderHelper
{
    public static function getStatusInPortuguese($status)
    {
        $translations = [
            'pending' => 'Pendente',
            'accepted' => 'Aceite',
            'declined' => 'Recusada',
            'completed' => 'Concluído'
        ];

        return $translations[$status] ?? ucfirst($status);
    }
}
