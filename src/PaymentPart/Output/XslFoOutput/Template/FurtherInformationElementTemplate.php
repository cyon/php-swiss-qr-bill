<?php

namespace Sprain\SwissQrBill\PaymentPart\Output\XslFoOutput\Template;

class FurtherInformationElementTemplate
{
    public const TEMPLATE = <<<EOT
<fo:block>{{ text }}</fo:block>

EOT;
}
