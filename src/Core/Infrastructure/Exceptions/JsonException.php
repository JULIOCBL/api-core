<?php


namespace Src\Core\Infrastructure\Exceptions;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use RuntimeException;
use Throwable;

/**
 * Clase personalizada para manejar excepciones estructuradas en formato JSON.
 * Esta clase permite lanzar errores con atributos adicionales como título,
 * código HTTP, código interno y fuente del error, facilitando el manejo uniforme
 * de errores en APIs.
 */
class JsonException extends RuntimeException
{
    /**
     * @param string $type Tipo de error (ej. LogLevel::WARNING).
     * @param string $title Título breve del error.
     * @param string $message Descripción detallada del error.
     * @param int $code Código HTTP de respuesta (ej. 401, 404, 500).
     * @param string $source Fuente opcional del error (ej. nombre del módulo).
     * @param int $error_code Código interno personalizado (default: 1000).
     * @param RuntimeException|null $previous Excepción previa para chaining.
     */
    public function __construct(
        protected string $type,
        protected string $title,
        string $message,
        int $code = 0,
        protected string $source = '',
        protected int $error_code = 1000,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Obtiene la fuente de la excepción.
     *
     * @return string
     */
    public function getSource(): string
    {
        return $this->source;
    }

    /**
     * Obtiene el título del error.
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Obtiene el código HTTP de estado.
     *
     * @return int
     */
    public function getStatus(): int
    {
        return $this->code;
    }

    /**
     * Obtiene el mensaje de detalle del error.
     *
     * @return string
     */
    public function getDetail(): string
    {
        return $this->message;
    }

    /**
     * Obtiene el código interno personalizado del error.
     *
     * @return int
     */
    public function getErrorCode(): int
    {
        return $this->error_code;
    }

    /**
     * Genera una excepción personalizada formateada para ser utilizada por el sistema.
     *
     * @return mixed
     */
    public function getException(): Request
    {
        $array = [
            'ip' => true,
            'report' => true,
            'title' => Str::ascii($this->getTitle()),
            'detail' => Str::ascii($this->getDetail()),
            'code' => (string) $this->getErrorCode(),
        ];

        if (!empty($this->getSource())) {
            $array['source'] = $this->getSource();
        }

        if (!empty($this->getStatus())) {
            $array['status'] = $this->getStatus();
        }

        return customException($this->type, new Request($array));
    }
}
