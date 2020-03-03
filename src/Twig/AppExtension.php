<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use App\Entity\Admin\TagArea;
use App\Entity\Admin\TagType;
use App\Entity\Admin\AlarmTrigger;
use App\Entity\Admin\TagLoggerInterval;

class AppExtension extends AbstractExtension {
    
    public function getFunctions() {
        
        return [
            new TwigFunction('TagAreaName', [TagArea::class, 'getName']),
            new TwigFunction('TagAreaPrefix', [TagArea::class, 'getPrefix']),
            new TwigFunction('TagTypeName', [TagType::class, 'getName']),
            new TwigFunction('AlarmTriggerName', [AlarmTrigger::class, 'getName']),
            new TwigFunction('TagLoggerIntervalName', [TagLoggerInterval::class, 'getName']),
        ];
    }
}