<?php

namespace GeekBrains\LevelTwo\Blog\Http;


abstract class Response
{

    protected const SUCCESS = true;



    public function send(): void
    {
        $data = ['success' => static::SUCCESS] + $this->payload();
        header('Content-Type: application/json');
        // Кодируем данные в JSON и отправляем их в теле ответа
        echo json_encode($data, JSON_THROW_ON_ERROR);
    }
    // Декларация абстрактного метода,
    // возвращающего полезные данные ответа
    abstract protected function payload(): array;
}
