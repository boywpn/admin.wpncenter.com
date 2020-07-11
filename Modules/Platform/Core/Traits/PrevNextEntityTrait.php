<?php

namespace Modules\Platform\Core\Traits;

/**
 * Trait PrevNextEntityTrait
 * @package Modules\Platform\Core\Traits
 */
trait PrevNextEntityTrait
{
    public function next()
    {
        $nextId = $this->where('id', '>', $this->id)->min('id');
        if ($nextId > 0) {
            return $this->find($nextId);
        }
    }

    public function prev()
    {
        $prevOd = $this->where('id', '<', $this->id)->max('id');
        if ($prevOd > 0) {
            return $this->find($prevOd);
        }
    }
}
