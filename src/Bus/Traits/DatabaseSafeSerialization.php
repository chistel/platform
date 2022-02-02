<?php
namespace Platform\Bus\Traits;

use Platform\Database\Contracts\Selector;
use Platform\Database\Database;

trait DatabaseSafeSerialization
{
    /** @var string */
    public $database;

    /**
     * Store the default connection before serializing job
     */
    public function __sleep()
    {
        $this->database = app(Selector::class)->current()->name();

        return array_unique(array_merge(['database'], $this->completeSleep()));
    }

    /**
     * Restore the default connection before unserializing
     */
    public function __wakeup()
    {
        app(Selector::class)->select($this->database());

        $this->completeWakeup();
    }

    protected function database(): Database
    {
        return new Database($this->database);
    }

    /**
     * Allow users of this trait to provide custom properties that should be stored for __sleep
     *
     * @return mixed
     */
    abstract protected function completeSleep(): array;

    /**
     * Allow users of this trait to provide custom code that should be executed during __wakeup
     *
     * @return mixed
     */
    abstract protected function completeWakeup();
}
