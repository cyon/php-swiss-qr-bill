<?php

namespace Sprain\SwissQrBill\PaymentPart\Output\XslFoOutput\Template;

class PaymentPartTemplate
{
    public const TEMPLATE = <<<EOT
<fo:block-container height="110mm" start-indent="0" end-indent="0" keep-together="always" font-family="Arial,Frutiger,Helvetica,'Liberation Sans'">
    
    <!-- Disabled since scissors symbol was introduced.
    <fo:block-container height="5mm">
        <fo:block text-align="center" font-size="8pt" {{ hide-in-printable }}>
            {{ text.separate }}
        </fo:block>
    </fo:block-container>-->

    <fo:table width="210mm" table-layout="fixed" margin-top="0" border-top="{{ printable-content }}">
        <!-- Basic column layout definition -->
        <!-- Note: column 2 was extended by 3mm to avoid scanning issues of the qr code on the left when paper is inaccurately placed in the printer / scanner. -->
        <!-- Note: Because of that column 3 was decreased in size by 3mm. -->
        <fo:table-column column-number="1" column-width="62mm" border-right="{{ printable-content }}" />
        <fo:table-column column-number="2" column-width="59mm" /><!-- +3 (was 56mm before). -->
        <fo:table-column column-number="3" column-width="89mm" /><!-- -3 (was 92mm before). -->
        <fo:table-body>
        
            <!-- 1st row: titles, receipt information, qr-code -->
            <fo:table-row>
            
                <!-- 1st col: receipt (left) -->
                <fo:table-cell column-number="1" padding="5mm" font-size="8pt">
                      
                    <fo:block-container height="7mm"> 
                        <fo:block font-weight="bold" font-size="11pt">
                            {{ text.receipt }}
                        </fo:block>
                    </fo:block-container>
                    
                    <fo:block-container height="56mm" keep-together="auto">
                        <fo:block>
                            {{ information-content-receipt }}
                        </fo:block>
                    </fo:block-container>
                        
                    <fo:block-container height="14mm">
                        <fo:table>
                            <fo:table-body>
                                <fo:table-row>
                                    <fo:table-cell margin="0" width="12mm">
                                        <fo:block>{{ currency-content-receipt }}</fo:block>
                                    </fo:table-cell>
                                    <fo:table-cell margin="0">
                                        {{ amount-content-receipt }}
                                    </fo:table-cell>
                                </fo:table-row>
                            </fo:table-body>
                        </fo:table>
                    </fo:block-container>
                    
                    <fo:block-container height="18mm">
                        <fo:block font-weight="bold" font-size="6pt" text-align="right">
                            {{ text.acceptancePoint }}
                        </fo:block>
                    </fo:block-container>
                </fo:table-cell>
                
                <!-- 2nd col: payment part (middle) -->
                <!-- Note: column 2 was extended by 3mm to avoid scanning issues of the qr code on the left when paper is inaccurately placed in the printer / scanner. -->
                <fo:table-cell column-number="2" padding="5mm 0 0 8mm" font-size="10pt"><!-- Note: was padding="5mm 0 0 5mm" before -->
                
                    <fo:block-container height="7mm">
                        <fo:block font-weight="bold" font-size="11pt">
                            {{ text.paymentPart }}
                        </fo:block>
                    </fo:block-container>
                    
                    <fo:block-container height="56mm">
                        <fo:block padding-top="5mm" padding-bottom="5mm">
                            <fo:external-graphic src="{{ swiss-qr-image }}" content-height="46mm" content-width="46mm" />
                        </fo:block>
                    </fo:block-container>
                    
                    <fo:block-container height="22mm">
                        <fo:table>
                            <fo:table-body>
                                <fo:table-row>
                                    <fo:table-cell margin="0" width="17mm">
                                        <fo:block>{{ currency-content }}</fo:block>
                                    </fo:table-cell>
                                    <fo:table-cell margin="0">
                                        {{ amount-content }}
                                    </fo:table-cell>
                                </fo:table-row>
                            </fo:table-body>
                        </fo:table>
                    </fo:block-container>
                    
                    <!-- Payment part further info (bottom middle to right) -->
                    <fo:block-container height="10mm">
                        <fo:block font-size="7pt">
                            {{ further-information-content }}
                        </fo:block>
                    </fo:block-container>
                    
                </fo:table-cell>
                
                <!-- 3rd col: payment part info (right) -->
                <fo:table-cell column-number="3">
                    <fo:block-container height="85mm" keep-together="auto">
                        <fo:block font-size="10pt" padding="3mm 5mm">
                            {{ information-content }}
                        </fo:block>
                    </fo:block-container>
                </fo:table-cell>
                
            </fo:table-row>
           
        </fo:table-body>
    </fo:table>
</fo:block-container>
EOT;
}
