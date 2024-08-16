<?php declare(strict_types=1);

namespace Sprain\SwissQrBill\PaymentPart\Output\HtmlOutput\Template;

class PlaceholderElementTemplate
{
    public const TEMPLATE = <<<EOT
<img src="{{ placeholder-file }}" style="width:{{ placeholder-width }}mm; height:{{ placeholder-height }}mm; float:{{ placeholder-float }}" class="qr-bill-placeholder" id="{{ placeholder-id }}">
EOT;
}
