<?php declare(strict_types=1);
namespace Sprain\SwissQrBill\PaymentPart\Output;

use Sprain\SwissQrBill\PaymentPart\Output\Element\Placeholder;
use Sprain\SwissQrBill\PaymentPart\Output\Element\Text;
use Sprain\SwissQrBill\PaymentPart\Output\Element\Title;
use Sprain\SwissQrBill\PaymentPart\Output\AbstractOutput;
use Sprain\SwissQrBill\PaymentPart\Output\Element\FurtherInformation;
use Sprain\SwissQrBill\PaymentPart\Translation\Translation;

abstract class AbstractMarkupOutput extends AbstractOutput implements OutputInterface
{
    abstract public function getPaymentPartTemplate(): string;

    abstract public function getPlaceholderElementTemplate(): string;

    abstract public function getPrintableBordersTemplate(): string;

    abstract public function getHideInPrintableTemplate(): string;

    abstract public function getTextElementTemplate(): string;
    abstract public function getFurtherInformationElementTemplate(): string;

    abstract public function getTitleElementTemplate(): string;

    abstract public function getTitleElementReceiptTemplate(): string;

    abstract public function getNewlineElementTemplate(): string;

    public function getPaymentPart(): string
    {
        $paymentPart = $this->getPaymentPartTemplate();

        // Luba was here
        $paymentPart = $this->addCurrencyContentReceipt($paymentPart);
        $paymentPart = $this->addSeparatorContentIfNotPrintable($paymentPart);
        $paymentPart = $this->hideInPrintable($paymentPart);

        $paymentPart = $this->addSwissQrCodeImage($paymentPart);
        $paymentPart = $this->addInformationContent($paymentPart);
        $paymentPart = $this->addInformationContentReceipt($paymentPart);
        $paymentPart = $this->addCurrencyContent($paymentPart);
        $paymentPart = $this->addAmountContent($paymentPart);
        $paymentPart = $this->addAmountContentReceipt($paymentPart);
        $paymentPart = $this->addFurtherInformationContent($paymentPart);
//        $paymentPart = $this->hideSeparatorContentIfPrintable($paymentPart);

        $paymentPart = $this->translateContents($paymentPart, $this->getLanguage());
        return $paymentPart;
    }

    private function addSwissQrCodeImage(string $paymentPart): string
    {
        $qrCode = $this->getQrCode();
        $paymentPart = str_replace('{{ swiss-qr-image }}', $qrCode->writeDataUri(), $paymentPart);

        return $paymentPart;
    }

    private function addInformationContent(string $paymentPart): string
    {
        $informationContent = '';

        foreach ($this->getInformationElements() as $informationElement) {
            $informationContentPart = $this->getContentElement($informationElement);
            $informationContent .= $informationContentPart;
        }

        $paymentPart = str_replace('{{ information-content }}', $informationContent, $paymentPart);

        return $paymentPart;
    }

    private function addInformationContentReceipt(string $paymentPart): string
    {
        $informationContent = '';

        foreach ($this->getInformationElementsOfReceipt() as $informationElement) {
            $informationContent .= $this->getContentElement($informationElement, true);
        }

        $paymentPart = str_replace('{{ information-content-receipt }}', $informationContent, $paymentPart);

        return $paymentPart;
    }

    private function addCurrencyContent(string $paymentPart): string
    {
        $currencyContent = '';

        foreach ($this->getCurrencyElements() as $currencyElement) {
            $currencyContent .= $this->getContentElement($currencyElement);
        }

        $paymentPart = str_replace('{{ currency-content }}', $currencyContent, $paymentPart);

        return $paymentPart;
    }

    private function addCurrencyContentReceipt(string $paymentPart): string
    {
        $currencyContent = '';

        foreach ($this->getCurrencyElements() as $currencyElement) {
            $currencyContent .= $this->getContentElement($currencyElement, true);
        }

        $paymentPart = str_replace('{{ currency-content-receipt }}', $currencyContent, $paymentPart);

        return $paymentPart;
    }

    private function addAmountContent(string $paymentPart): string
    {
        $amountContent = '';

        foreach ($this->getAmountElements() as $amountElement) {
            $amountContent .= $this->getContentElement($amountElement);
        }

        $paymentPart = str_replace('{{ amount-content }}', $amountContent, $paymentPart);

        return $paymentPart;
    }

    private function addAmountContentReceipt(string $paymentPart): string
    {
        $amountContent = '';

        foreach ($this->getAmountElementsReceipt() as $amountElement) {
            $amountContent .= $this->getContentElement($amountElement, true);
        }

        $paymentPart = str_replace('{{ amount-content-receipt }}', $amountContent, $paymentPart);

        return $paymentPart;
    }

    private function addFurtherInformationContent(string $paymentPart): string
    {
        $furtherInformationContent = '';

        foreach ($this->getFurtherInformationElements() as $furtherInformationElement) {
            $furtherInformationContent .= $this->getContentElement($furtherInformationElement);
        }

        $paymentPart = str_replace('{{ further-information-content }}', $furtherInformationContent, $paymentPart);

        return $paymentPart;
    }

    /**
     * @param Title|Text|Placeholder $element Instance of OutputElementInterface.
     * @param bool $isReceiptPart
     * @return string
     */
    private function getContentElement(FurtherInformation|Title|Text|Placeholder $element, bool $isReceiptPart = false): string
    {
        return match ($element::class) {
            FurtherInformation::class => $this->getFurtherInformationElement($element),
            Title::class => $this->getTitleElement($element, $isReceiptPart),
            Text::class => $this->getTextElement($element),
            Placeholder::class => $this->getPlaceholderElement($element)
        };
    }

    private function getTitleElement(Title $element, $isReceiptPart = false): string
    {
        $elementTemplate = $this->getTitleElementTemplate();
        if (true === $isReceiptPart) {
            $elementTemplate = $this->getTitleElementReceiptTemplate();
        }
        $elementString = str_replace('{{ title }}', $element->getTitle(), $elementTemplate);
        return $elementString;



    }

    private function getTextElement(Text $element): string
    {
        $elementTemplate = $this->getTextElementTemplate();
        $elementTextString = str_replace(array("\r\n", "\r", "\n"), $this->getNewlineElementTemplate(), $element->getText());
        $elementString = str_replace('{{ text }}', $elementTextString, $elementTemplate);

        return $elementString;
    }

    private function getFurtherInformationElement(FurtherInformation $element): string
    {
        $elementTemplate = $this->getFurtherInformationElementTemplate();
        $elementTextString = str_replace(array("\r\n", "\r", "\n"), $this->getNewlineElementTemplate(), $element->getText());
        $elementString = str_replace('{{ text }}', $elementTextString, $elementTemplate);

        return $elementString;
    }

    private function getPlaceholderElement(Placeholder $element): string
    {
        $elementTemplate = $this->getPlaceholderElementTemplate();
        $elementString = $elementTemplate;

        $svgDoc = new \DOMDocument();
        $svgDoc->loadXML(file_get_contents($element->getFile())); // @phpstan-ignore argument.type (the loaded file is under our control)
        $svg = $svgDoc->getElementsByTagName('svg');
        $dataUri = 'data:image/svg+xml;base64,' . base64_encode($svg->item(0)->C14N());

        $elementString = str_replace('{{ file }}', $dataUri, $elementString);
        $elementString = str_replace('{{ width }}', (string) $element->getWidth(), $elementString);
        $elementString = str_replace('{{ height }}', (string) $element->getHeight(), $elementString);
        $elementString = str_replace('{{ id }}', $element->getType(), $elementString);
        $elementString = str_replace('{{ placeholder-float }}', $element->getFloat(), $elementString);
        $elementString = str_replace('{{ placeholder-margin-top }}', (string) $element->getMarginTop(), $elementString);

        return $elementString;
    }

    private function translateContents(string $paymentPart, string $language): string
    {
        $translations = Translation::getAllByLanguage($language);
        foreach ($translations as $key => $text) {
            $paymentPart = str_replace('{{ text.' . $key . ' }}', $text, $paymentPart);
        }

        return $paymentPart;
    }

    private function addSeparatorContentIfNotPrintable(string $paymentPart): string
    {
        $printableBorders = '';
        if (true !== $this->isPrintable()) {
            $printableBorders = $this->getPrintableBordersTemplate();
        }

        $paymentPart = str_replace('{{ printable-content }}', $printableBorders, $paymentPart);

        return $paymentPart;
    }

    private function hideInPrintable(string $paymentPart): string
    {
        $hideInPrintableContent = '';
        if (true === $this->isPrintable()) {
            $hideInPrintableContent = $this->getHideInPrintableTemplate();
        }

        $paymentPart = str_replace('{{ hide-in-printable }}', $hideInPrintableContent, $paymentPart);

        return $paymentPart;
    }

}
