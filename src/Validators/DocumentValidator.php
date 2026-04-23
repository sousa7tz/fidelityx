<?php

namespace App\Validators;

class DocumentValidator{
    public static function isValid($document){
        
        $document   =   preg_replace('/\D/', '', (string)$document);

        if(strlen($document) == 11){
            return self::validarCPF($document);
        }
        elseif(strlen($document) == 14){
            return self::validarCNPJ($document);
        }

        return false;
    }

    private static function validarCPF($document){
        
        // bloqueia cpf invalido com todos os dígitos iguais
        // Ex: 11111111111, 00000000000, etc
        if (preg_match('/(\d)\1{10}/', $document)) {
            return false;
        }
    
        // ============================
        // CÁLCULO DOS DÍGITOS
        // ============================
        
        // Loop para calcular os 2 últimos dígitos
        for ($t = 9; $t < 11; $t++) {
            
            $soma = 0;
    
            // Multiplica cada dígito pelos pesos decrescentes
            for ($i = 0; $i < $t; $i++) {
                $soma += $document[$i] * (($t + 1) - $i);
            }
    
            // Aplica regra do módulo 11
            $digito = ((10 * $soma) % 11) % 10;
    
            // Compara com o dígito real do CPF
            if ($document[$t] != $digito) {
                return false;
            }
        }
    
        // se passou por tudo é valido
        return true;
    }

    private static function validarCNPJ($document){
        // bloqueia sequências inválidas (ex: 11111111111111)
        if (preg_match('/(\d)\1{13}/', $document)) {
            return false;
        }

        // PRIMEIRO DÍGITO VERIFICADOR
        
        $tamanho = 12; // base sem os 2 dígitos finais
        $numeros = substr($document, 0, $tamanho);
        
        $soma = 0;
        $peso = 5;
        
        // multiplica com pesos: 5 4 3 2 9 8 7 6 5 4 3 2
        for ($i = 0; $i < $tamanho; $i++) {
            $soma += $numeros[$i] * $peso;
            $peso--;
            
            // quando chega em 2, reinicia em 9
            if ($peso < 2) {
                $peso = 9;
            }
        }
    
        // regra modulo 11
        $resto = $soma % 11;
        $digito1 = ($resto < 2) ? 0 : 11 - $resto;
        
        // verifica o primeiro digito
        if ($document[12] != $digito1) {
            return false;
        }
    
        // segundo digito
        
        $tamanho = 13;
        $numeros = substr($document, 0, $tamanho);
        
        $soma = 0;
        $peso = 6;
        
        // pesos: 6 5 4 3 2 9 8 7 6 5 4 3 2
        for ($i = 0; $i < $tamanho; $i++) {
            $soma += $numeros[$i] * $peso;
            $peso--;
    
            if ($peso < 2) {
                $peso = 9;
            }
        }
        
        $resto = $soma % 11;
        $digito2 = ($resto < 2) ? 0 : 11 - $resto;
    
        // verificar segundo digito
        if ($document[13] != $digito2) {
            return false;
        }
    
        return true;
    }
}