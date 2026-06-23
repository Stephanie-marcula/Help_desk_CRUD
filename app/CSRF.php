<?php

class CSRF
{
    public static function token(): string
    {
        if (empty($_SESSION['_token'])) {
            $_SESSION['_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['_token'];
    }

    public static function campo(): string
    {
        return '<input type="hidden" name="_token" value="' . self::token() . '">';
    }

    public static function validar(): bool
    {
        $enviado = $_POST['_token'] ?? '';
        $valido  = $enviado !== '' && hash_equals($_SESSION['_token'] ?? '', $enviado);

        if ($valido) {
            unset($_SESSION['_token']);
        }

        return $valido;
    }
}
