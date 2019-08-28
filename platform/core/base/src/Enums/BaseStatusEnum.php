<?php

namespace Botble\Base\Enums;

use Botble\Base\Supports\Enum;
use Html;

/**
 * @method static BaseStatusEnum DRAFT()
 * @method static BaseStatusEnum PUBLISH()
 * @method static BaseStatusEnum PENDING()
 */
class BaseStatusEnum extends Enum
{
    public const PUBLISH = 'publish';
    public const DRAFT = 'draft';
    public const PENDING = 'pending';

    /**
     * @var string
     */
    public static $langPath = 'core/base::enums.statuses';

    /**
     * @return string
     */
    public function toHtml()
    {
        switch ($this->value) {
            case self::DRAFT:
                return Html::tag('span', self::DRAFT()->label(), ['class' => 'label-info status-label'])
                    ->toHtml();
            case self::PENDING:
                return Html::tag('span', self::PENDING()->label(), ['class' => 'label-warning status-label'])
                    ->toHtml();
            case self::PUBLISH:
                return Html::tag('span', self::PUBLISH()->label(), ['class' => 'label-success status-label'])
                    ->toHtml();
            default:
                return null;
        }
    }
}