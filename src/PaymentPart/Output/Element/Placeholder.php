<?php declare(strict_types=1);

namespace Sprain\SwissQrBill\PaymentPart\Output\Element;

/**
 * @internal
 */
final class Placeholder implements OutputElementInterface
{
    public const FILE_TYPE_SVG = 'svg';
    public const FILE_TYPE_PNG = 'png';

    public const PLACEHOLDER_TYPE_PAYABLE_BY = [
        'type' => 'placeholder_payable_by',
        'fileSvg' => __DIR__ . '/../../../../assets/marks_65x25mm.optimized.svg',
        'filePng' => __DIR__ . '/../../../../assets/marks_65x25mm.png',
        'width' => 65,
        'height' => 25,
        'float' => 'left',
        'marginTop' => 0, // In mm.
    ];

    public const PLACEHOLDER_TYPE_PAYABLE_BY_RECEIPT = [
        'type' => 'placeholder_payable_by_receipt',
        'fileSvg' => __DIR__ . '/../../../../assets/marks_52x20mm.optimized.svg',
        'filePng' => __DIR__ . '/../../../../assets/marks_52x20mm.png',
        'width' => 52,
        'height' => 20,
        'float' => 'right',
        'marginTop' => 0, // In mm.
    ];

    public const PLACEHOLDER_TYPE_AMOUNT = [
        'type' => 'placeholder_amount',
        'fileSvg' => __DIR__ . '/../../../../assets/marks_40x15mm.optimized.svg',
        'filePng' => __DIR__ . '/../../../../assets/marks_40x15mm.png',
        'width' => 40,
        'height' => 15,
        'float' => 'right',
        'marginTop' => -0.9 // In mm.
    ];

    public const PLACEHOLDER_TYPE_AMOUNT_RECEIPT = [
        'type' => 'placeholder_amount_receipt',
        'fileSvg' => __DIR__ . '/../../../../assets/marks_30x10mm.optimized.svg',
        'filePng' => __DIR__ . '/../../../../assets/marks_30x10mm.png',
        'width' => 30,
        'height' => 10,
        'float' => 'right',
        'marginTop' => -3.175 // In mm.
    ];

    private string $type;
    private string $fileSvg;
    private string $filePng;
    private int $width;
    private int $height;

    /** @var string */
    private $float;

    /** @var int */
    private $marginTop;

    public static function create(array $type): self
    {
        $placeholder = new self();
        $placeholder->type = $type['type'];
        $placeholder->fileSvg = $type['fileSvg'];
        $placeholder->filePng = $type['filePng'];
        $placeholder->width = $type['width'];
        $placeholder->height = $type['height'];
        $placeholder->float = $type['float'];
        $placeholder->marginTop = $type['marginTop'];

        return $placeholder;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function getFile(string $type = self::FILE_TYPE_SVG): string
    {
        return match ($type) {
            self::FILE_TYPE_PNG => $this->filePng,
            default => $this->fileSvg,
        };
    }

    public function getWidth(): ?int
    {
        return $this->width;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function getFloat(): ?string
    {
        return $this->float;
    }

    public function getMarginTop(): float
    {
        return $this->marginTop ?: 0;
    }
}
