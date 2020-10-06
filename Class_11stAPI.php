<?php
class Class_11stAPI
{
    
    public  $args    =   array();
    
    public $baseurl     =   'https://api.11st.co.kr/rest';
    public $accessKey   =   '';
 	  public $datetime	=   '';
    
    public function __construct($args)
    {
        $this->args       =   $args;
        $this->datetime		=   date("ymd").'T'.date("His").'Z';
        $this->accessKey  =   $this->args['accessKey'];
    }// end function
    
    //  @ 상품판매중지처리
    public function set_products_seles_stop($sellerProductId=null):array
    {
        $path      		=   "/prodstatservice/stat/stopdisplay/{prdNo}";
        $path      		=   str_replace("{prdNo}", $sellerProductId, $path);
        
        $method         =   "PUT";
        $ReqUrl         =   $this->baseurl.$path;
        $result			=	$this->curlHttpRequest( $ReqUrl, $method);
        
        return json_decode( json_encode( $this->xmlPaser_01($result) ), true );
    }// end function
    
    //	@ 상품정보 조회 By sellerProductId
    public function get_products_info_sellerProductId($sellerProductId=null):array
    {
        $path      		=   "/prodmarketservice/prodmarket/{prdNo}";
        $path      		=   str_replace("{prdNo}", $sellerProductId, $path);
        
        $method         =   "GET";
        $ReqUrl         =   $this->baseurl.$path;
        $result			=	$this->curlHttpRequest( $ReqUrl, $method);

        return json_decode( json_encode( $this->xmlPaser_01($result) ), true );
        
    }// end function
    
    
    
    
    //	@ Seller - 주문 - 발주확인할 내역 (결재완료 목록조회)    
    public function get_ordservices_complete($startTime=null, $endTime=null ):array
    {
        if( ( is_null($startTime) == true )&&( is_null($endTime) == true ) )
        {
            $now_unixtime   =   time();
            $startTime      =   date("YmdHi", $now_unixtime - 86400);
            $endTime        =   date("YmdHi", $now_unixtime);
        }// end if
        
        $path      		=   "/ordservices/complete/{startTime}/{endTime}";
        $path      		=   str_replace("{startTime}"  , $startTime, $path);
        $path      		=   str_replace("{endTime}"    , $endTime  , $path);
        
        $method         =   "GET";
        $ReqUrl         =   $this->baseurl.$path;
        $result			=	$this->curlHttpRequest( $ReqUrl, $method);
        
        return json_decode( json_encode( $this->xmlPaser_02($result) ), true );
        
    }// end function
    
    
    
    private function xmlPaser_01( $xml )
    {
        return simplexml_load_string( $xml );
    }// end function
    
    private function xmlPaser_02($xml)
    {
/*        
        $xml    ='<?xml version="1.0" encoding="utf-8" standalone="yes"?>
                    <ns2:orders>
                      <ns2:order>
                        <addPrdNo>0</addPrdNo>
                        <addPrdYn>N</addPrdYn>
                        <bndlDlvSeq>4506571</bndlDlvSeq>
                        <bndlDlvYN>Y</bndlDlvYN>
                        <custGrdNm/>
                        <dlvCst>0</dlvCst>
                        <dlvCstType>03</dlvCstType>
                        <bmDlvCst>4500</bmDlvCst>
                        <bmDlvCstType>04</bmDlvCstType>
                        <dlvNo>40860365</dlvNo>
                        <gblDlvYn>N</gblDlvYn>
                        <giftCd/>
                        <memID>test11st</memID>
                        <memNo>1111111</memNo>
                        <ordAmt>19000</ordAmt>
                        <ordBaseAddr>충북 청주시 상당구 용암동</ordBaseAddr>
                        <ordDlvReqCont>null</ordDlvReqCont>
                        <ordDt>2010-01-10 04:07:11</ordDt>
                        <ordDtlsAddr>00번지</ordDtlsAddr>
                        <ordMailNo>360100</ordMailNo>
                        <ordNm>홍길동</ordNm>
                        <ordNo>201001108318120</ordNo>
                        <ordOptWonStl>0</ordOptWonStl>
                        <ordPayAmt>16310</ordPayAmt>
                        <ordPrdSeq>1</ordPrdSeq>
                        <ordPrtblTel>010-9999-9999</ordPrtblTel>
                        <ordQty>1</ordQty>
                        <ordStlEndDt>2010-01-12 16:20:59</ordStlEndDt>
                        <ordTlphnNo>070-9999-9999</ordTlphnNo>
                        <prdNm>셔링 브이넥 니트 티셔츠</prdNm>
                        <prdNo>29370295</prdNo>
                        <prdStckNo>999999999</prdStckNo>
                        <rcvrBaseAddr>충북 청주시 상당구 용암동</rcvrBaseAddr>
                        <rcvrDtlsAddr>00번지 8809호</rcvrDtlsAddr>
                        <rcvrMailNo>360100</rcvrMailNo>
                        <rcvrMailNoSeq>011</rcvrMailNoSeq>
                        <rcvrNm>홍길동</rcvrNm>
                        <rcvrPrtblNo>010-9999-9999</rcvrPrtblNo>
                        <rcvrTlphn>070-9999-9999</rcvrTlphn>
                        <selPrc>19000</selPrc>
                        <sellerDscPrc>2280</sellerDscPrc>
                        <sellerPrdCd>000000000133275</sellerPrdCd>
                        <slctPrdOptNm>사이즈/색상:사이즈 - S(66)/색상 - 아이보리 [0000346774]-1개</slctPrdOptNm>
                        <tmallDscPrc>410</tmallDscPrc>
                        <typeAdd>01</typeAdd>
                        <typeBilNo/>
                        <lstTmallDscPrc>0</lstTmallDscPrc>
                        <lstSellerDscPrc>0</lstSellerDscPrc>
                        <referSeq>455221112</referSeq>
                        <sellerStockCd>43434232</sellerStockCd>
                        <appmtDdDlvDy>20170420</appmtDdDlvDy>
                        <appmtEltRefuseYn/>
                        <appmtselStockCd/>
                        <engNm>CHULSU KIM</engNm>
                        <psnCscUniqNo>P000000000000</psnCscUniqNo>
                        <dlvSndDue>2019-05-30 04:07:11</dlvSndDue>
                        <delaySendDt>2019-05-30 04:07:11</delaySendDt>
                        <visitDlvYn/>
                      </ns2:order>
                    </ns2:orders>
                    ';
*/  
        
        $xml     =   str_ireplace(['ns2:','xmlns:'], '', $xml);
        
        return simplexml_load_string( $xml );
    }// end function
    
	
	private function curlHttpRequest( $ReqUrl, $method )
	{   
	    // echo $ReqUrl."<BR>";
	    
        switch( strtoupper( $method ) )
        {
            case 'GET':
                $options = array(
                                CURLOPT_URL         =>  $ReqUrl
                                ,CURLOPT_HTTPHEADER =>   array(
                                                        "openapikey:".$this->accessKey
                                                        ,"Content-Type: text/xml;charset=UTF-8"
                                                        ,"Cache-Control: no-cache"
                                                        ,"Pragma: no-cache"
                                                        
                                                    )
                                ,CURLOPT_CUSTOMREQUEST  =>  $method
                                ,CURLOPT_RETURNTRANSFER =>  TRUE
                            );
                break;
            case 'PUT':
            case 'POST':
                $options = array(
                                CURLOPT_URL         =>  $ReqUrl
                                ,CURLOPT_HTTPHEADER =>   array(
                                                        "openapikey:".$this->accessKey
                                                        ,"Content-Type: text/xml;charset=UTF-8"
                                                        ,"Cache-Control: no-cache"
                                                        ,"Pragma: no-cache"
                                                    )
                                ,CURLOPT_CUSTOMREQUEST  =>  $method
                                ,CURLOPT_RETURNTRANSFER =>  TRUE
                                ,CURLOPT_FRESH_CONNECT  =>  TRUE
                                ,CURLOPT_TIMEOUT        =>  20
                                ,CURLOPT_POST           =>  TRUE
                            );
                break;
        }// end switch
        
//         echo "<pre>";
//         var_export( $options );
//         echo "</pre>";
        
        $ch = curl_init();
        curl_setopt_array($ch, $options);
        
        $result		=	curl_exec($ch);
		$httpcode	=   curl_getinfo($ch);
		
        curl_close($ch);        
        
        return $result;
		
	}//	end function
  
    
}// end class
?> 
